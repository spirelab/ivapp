<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiValidation;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\Fund;
use App\Models\Gateway;
use Carbon\Carbon;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    use ApiValidation, Upload, Notify;

    public function paymentGateways()
    {
        try {
            $data['baseCurrency'] = config('basic.currency');
            $data['baseSymbol'] = config('basic.currency_symbol');
            $data['gateways'] = Gateway::where('status', 1)->orderBy('sort_by', 'ASC')->get()->map(function ($query) {
                $query->image = getFile(config('location.gateway.path') . $query->image);
                return $query;
            });
            return $this->withSuccess($data);
        } catch (\Exception $e) {
            return $this->withErrors($e->getMessage());
        }
    }

    public function manualPaymentSubmit(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'gateway' => 'required',
            'amount' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->withErrors(collect($validator->messages())->collapse()[0]);
        }

        try {
            $basic = (object)config('basic');
            $gate = Gateway::where('id', $request->gateway)->where('status', 1)->first();
            if (!$gate) {
                return $this->withErrors('Invalid Gateway');
            }

            $reqAmount = $request->amount;
            if ($gate->min_amount > $reqAmount || $gate->max_amount < $reqAmount) {
                return $this->withErrors('Please Follow Transaction Limit');
            }

            $charge = getAmount($gate->fixed_charge + ($reqAmount * $gate->percentage_charge / 100));
            $payable = getAmount($reqAmount + $charge);
            $final_amo = getAmount($payable * $gate->convention_rate);
            $user = auth()->user();

            DB::beginTransaction();

            $fund = $this->newFund($request, $user, $gate, $charge, $final_amo, $reqAmount, $request->plan_id ?? null);

            $data = Fund::where('transaction', $fund['transaction'])->orderBy('id', 'DESC')->with(['gateway', 'user'])->first();
            if (is_null($data)) {
                return $this->withErrors('Invalid Fund Request');
            }
            if ($data->status != 0) {
                return $this->withErrors('Invalid Fund Request');
            }

            $gateway = $data->gateway;
            $params = optional($data->gateway)->parameters;

            $rules = [];
            $inputField = [];

            $verifyImages = [];

            if ($params != null) {
                foreach ($params as $key => $cus) {
                    $rules[$key] = [$cus->validation];
                    if ($cus->type == 'file') {
                        array_push($rules[$key], 'image');
                        array_push($rules[$key], 'mimes:jpeg,jpg,png');
                        array_push($rules[$key], 'max:2048');
                        array_push($verifyImages, $key);
                    }
                    if ($cus->type == 'text') {
                        array_push($rules[$key], 'max:191');
                    }
                    if ($cus->type == 'textarea') {
                        array_push($rules[$key], 'max:300');
                    }
                    $inputField[] = $key;
                }
            }

            $validator = validator()->make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->withErrors(collect($validator->messages())->collapse()[0]);
            }

            $path = config('location.deposit.path') . date('Y') . '/' . date('m') . '/' . date('d');
            $collection = collect($request);

            $reqField = [];
            if ($params != null) {
                foreach ($collection as $k => $v) {
                    foreach ($params as $inKey => $inVal) {
                        if ($k != $inKey) {
                            continue;
                        } else {
                            if ($inVal->type == 'file') {
                                if ($request->hasFile($inKey)) {
                                    try {
                                        $reqField[$inKey] = [
                                            'field_name' => $this->uploadImage($request[$inKey], $path),
                                            'type' => $inVal->type,
                                        ];
                                    } catch (\Exception $exp) {
                                        return $this->withErrors('Could not upload your ' . $inKey);
                                    }
                                }
                            } else {
                                $reqField[$inKey] = [
                                    'field_name' => $v,
                                    'type' => $inVal->type,
                                ];
                            }
                        }
                    }
                }
                $data->detail = $reqField;
            } else {
                $data->detail = null;
            }

            $data->created_at = Carbon::now();
            $data->status = 2; // pending
            $data->update();
            DB::commit();
            $msg = [
                'username' => $data->user->username,
                'amount' => getAmount($data->amount),
                'currency' => $basic->currency,
                'gateway' => $gateway->name
            ];
            $action = [
                "link" => route('admin.payment.pending', $data->user_id),
                "icon" => "fa fa-money-bill-alt text-white"
            ];

            $user = auth()->user();
            $currentDate = dateTime(Carbon::now());
            $this->adminPushNotification('ADMIN_NOTIFY_PAYMENT_REQUEST', $msg, $action);
            $this->mailToAdmin($type = 'ADMIN_MAIL_PAYMENT_REQUEST', [
                'username' => $data->user->username,
                'amount' => getAmount($data->amount),
                'currency' => $basic->currency,
                'gateway' => $gateway->name,
                'date' => $currentDate
            ]);

            $userAction = [
                "link" => route('user.fund-history'),
                "icon" => "fa fa-money-bill-alt text-white"
            ];

            $this->userPushNotification($user, 'USER_NOTIFY_PAYMENT_REQUEST', $msg, $userAction);
            $this->sendMailSms($user, 'USER_MAIL_PAYMENT_REQUEST', [
                'username' => $data->user->username,
                'amount' => getAmount($data->amount),
                'currency' => $basic->currency,
                'gateway' => $gateway->name,
                'date' => $currentDate
            ]);

            return $this->withSuccess('You request has been taken.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->withErrors($e->getMessage());
        }
    }

    public function paymentDone(Request $request)
    {
        try {
            $reqAmount = $request->amount;
            $gate = Gateway::where('id', $request->gateway)->first();
            $charge = getAmount($gate->fixed_charge + ($reqAmount * $gate->percentage_charge / 100));
            $payable = getAmount($reqAmount + $charge);
            $final_amo = getAmount($payable * $gate->convention_rate);
            $user = auth()->user();
            $order = $this->newFund($request, $user, $gate, $charge, $final_amo, $reqAmount, $request->plan_id ?? null);

            BasicService::preparePaymentUpgradation($order);
            return $this->withSuccess('Payment has been completed');
        } catch (\Exception $e) {
            return $this->withErrors($e->getMessage());
        }
    }

    public function cardPayment(Request $request)
    {
        $reqAmount = $request->amount;
        $gate = Gateway::where('id', $request->gateway)->first();
        $charge = getAmount($gate->fixed_charge + ($reqAmount * $gate->percentage_charge / 100));
        $payable = getAmount($reqAmount + $charge);
        $final_amo = getAmount($payable * $gate->convention_rate);
        $user = auth()->user();
        $order = $this->newFund($request, $user, $gate, $charge, $final_amo, $reqAmount, null);

        $getwayObj = 'App\\Services\\Gateway\\' . $gate->code . '\\Payment';
        $data = $getwayObj::mobileIpn($request, $gate, $order);
        if ($data == 'success') {
            return $this->withSuccess('Payment has been complete');
        } else {
            return $this->withErrors('unsuccessful transaction.');
        }
    }

    public function newFund(Request $request, $user, $gate, $charge, $final_amo, $amount, $plan_id = null): Fund
    {
        $fund = new Fund();
        $fund->user_id = $user->id;
        $fund->gateway_id = $gate->id;
        $fund->plan_id = $plan_id;
        $fund->gateway_currency = strtoupper($gate->currency);
        $fund->amount = $amount;
        $fund->charge = $charge;
        $fund->rate = $gate->convention_rate;
        $fund->final_amount = getAmount($final_amo);
        $fund->btc_amount = 0;
        $fund->btc_wallet = "";
        $fund->transaction = strRandom();
        $fund->try = 0;
        $fund->status = 0;
        $fund->save();
        return $fund;
    }

    public function showOtherPayment(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'amount' => 'required',
                'gateway' => 'required'
            ]);

        if ($validateUser->fails()) {
            return response()->json($this->withErrors(collect($validateUser->errors())->collapse()[0]));
        }

        $reqAmount = $request->amount;
        $gate = Gateway::where('id', $request->gateway)->first();
        $charge = getAmount($gate->fixed_charge + ($reqAmount * $gate->percentage_charge / 100));
        $payable = getAmount($reqAmount + $charge);
        $final_amo = getAmount($payable * $gate->convention_rate);
        $user = auth()->user();
        $order = $this->newFund($request, $user, $gate, $charge, $final_amo, $reqAmount, null);

        $val['url'] = route('paymentView', $order->id);
        return response()->json($this->withSuccess($val));
    }

    public function paymentView($fundId)
    {
        $order = Fund::latest()->find($fundId);
        try {
            if ($order) {
                $getwayObj = 'App\\Services\\Gateway\\' . $order->gateway->code . '\\Payment';
                $data = $getwayObj::prepareData($order, $order->gateway, true);
                $data = json_decode($data);

                if (isset($data->error)) {
                    return response()->json($this->withErrors($data->message));
                }

                if (isset($data->redirect)) {
                    return redirect($data->redirect_url);
                }
                return view($data->view, compact('data', 'order'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }
}
