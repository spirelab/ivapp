<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiValidation;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\PayoutLog;
use App\Models\PayoutMethod;
use App\Models\PayoutSetting;
use Carbon\Carbon;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Stevebauman\Purify\Facades\Purify;
use Facades\App\Http\Controllers\User;

class PayoutController extends Controller
{
    use ApiValidation, Upload, Notify;

    public function payout()
    {
        try {
            $data['balance'] = trans('Deposit Balance - ' . config('basic.currency_symbol') . getAmount(auth()->user()->balance));
            $data['depositAmount'] = getAmount(auth()->user()->balance);
            $data['interest_balance'] = trans('Interest Balance - ' . config('basic.currency_symbol') . getAmount(auth()->user()->interest_balance));
            $data['interestAmount'] = getAmount(auth()->user()->interest_balance);

            $data['gateways'] = PayoutMethod::whereStatus(1)->get()->map(function ($query) {
                $method['id'] = $query->id;
                $method['name'] = $query->name;
                $method['image'] = getFile(config('location.withdraw.path') . $query->image);
                $method['currencySymbol'] = config('basic.currency_symbol');
                $method['currency'] = config('basic.currency');
                $method['minimumAmount'] = $query->minimum_amount;
                $method['maximumAmount'] = $query->maximum_amount;
                $method['fixedCharge'] = $query->fixed_charge;
                $method['percentCharge'] = $query->percent_charge;
                $method['dynamicForm'] = $query->input_form;
                $method['bankName'] = $query->banks ?? [];
                $method['supportedCurrency'] = (!empty($query->supported_currency)) ? $query->supported_currency : null;
                $method['convertRate'] = $query->convert_rate;
                $method['isAutomatic'] = $query->is_automatic;

                return $method;
            });

            $payoutSettings = PayoutSetting::first();
            $data['openDaysList'] = $this->openDaysList($payoutSettings);

            $data['today'] = Str::lower(Carbon::now()->format('l'));
            if ($payoutSettings[$data['today']]) {
                $data['isOffDay'] = false;
            } else {
                $data['isOffDay'] = true;
            }
            return response()->json($this->withSuccess($data));
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function openDaysList($payoutSettings)
    {
        $arr = [];
        if ($payoutSettings->monday) {
            array_push($arr, 'Monday');
        }
        if ($payoutSettings->tuesday) {
            array_push($arr, 'Tuesday');
        }
        if ($payoutSettings->tuesday) {
            array_push($arr, 'Wednesday');
        }
        if ($payoutSettings->tuesday) {
            array_push($arr, 'Thusday');
        }
        if ($payoutSettings->tuesday) {
            array_push($arr, 'Friday');
        }
        if ($payoutSettings->tuesday) {
            array_push($arr, 'Saturday');
        }
        if ($payoutSettings->tuesday) {
            array_push($arr, 'Sunday');
        }
        return $arr;
    }


    public function payoutGetBankList(Request $request)
    {
        try {
            $currencyCode = $request->currencyCode;
            $methodObj = 'App\\Services\\Payout\\paystack\\Card';
            $data = $methodObj::getBank($currencyCode);
            return response()->json($this->withSuccess($data));
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function payoutGetBankFrom(Request $request)
    {
        try {
            $bankName = $request->bankName;
            $bankArr = config('banks.' . $bankName);
            $value['bank'] = null;
            if ($bankArr['api'] != null) {

                $methodObj = 'App\\Services\\Payout\\flutterwave\\Card';
                $data = $methodObj::getBank($bankArr['api']);
                $value['bank'] = $data;
            }
            $value['input_form'] = $bankArr['input_form'];
            return response()->json($this->withSuccess($value));
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function payoutPaystackSubmit(Request $request, $trx_id)
    {
        $validateUser = Validator::make($request->all(),
            [
                'wallet_type' => ['required', Rule::in(['balance', 'interest_balance'])],
                'gateway' => 'required|integer',
                'amount' => ['required', 'numeric']
            ]);

        if ($validateUser->fails()) {
            return response()->json($this->withErrors(collect($validateUser->errors())->collapse()[0]));
        }

        $basic = (object)config('basic');

        try {
            $method = PayoutMethod::where('id', $request->gateway)->where('status', 1)->first();
            if (!$method) {
                return response()->json($this->withErrors('Method Not Found'));
            }
            $user = auth()->user();

            $charge = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);

            $finalAmo = $request->amount + $charge;

            if ($request->amount < $method->minimum_amount) {
                return response()->json($this->withErrors('Minimum payout Amount ' . round($method->minimum_amount, 2) . ' ' . $basic->currency));
            }
            if ($request->amount > $method->maximum_amount) {
                return response()->json($this->withErrors('Maximum payout Amount ' . round($method->maximum_amount, 2) . ' ' . $basic->currency));
            }

            if (getAmount($finalAmo) > $user[$request->wallet_type]) {
                return response()->json($this->withErrors('Insufficient ' . snake2Title($request->wallet_type) . ' For Withdraw.'));
            } else {
                $trx = strRandom();
                $payout = new PayoutLog();
                $payout->user_id = $user->id;
                $payout->method_id = $method->id;
                $payout->amount = getAmount($request->amount);
                $payout->charge = $charge;
                $payout->net_amount = $finalAmo;
                $payout->trx_id = $trx;
                $payout->status = 0;
                $payout->balance_type = $request->wallet_type;
                $payout->save();

                $purifiedData = Purify::clean($request->all());

                if (empty($purifiedData['bank'])) {
                    return response()->json($this->withErrors('Bank field is required'));
                }

                $rules = [];
                $inputField = [];
                if ($method->input_form != null) {
                    foreach ($method->input_form as $key => $cus) {

                        $rules[$key] = [$cus->validation];
                        if ($cus->type == 'file') {
                            array_push($rules[$key], 'image');
                            array_push($rules[$key], 'mimes:jpeg,jpg,png');
                            array_push($rules[$key], 'max:2048');
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

                $rules['type'] = 'required';
                $rules['currency'] = 'required';

                $validate = Validator::make($request->all(), $rules);

                if ($validate->fails()) {
                    return response()->json($this->withErrors(collect($validate->errors())->collapse()[0]));
                }

                if (getAmount($payout->net_amount) > $user[$payout->balance_type]) {
                    return response()->json($this->withErrors('Insufficient balance For Payout.'));
                }

                $collection = collect($purifiedData);
                $reqField = [];
                if ($method->input_form != null) {
                    foreach ($collection as $k => $v) {
                        foreach ($method->input_form as $inKey => $inVal) {
                            if ($k != $inKey) {
                                continue;
                            } else {
                                if ($inVal->type == 'file') {
                                    if ($request->file($inKey) && $request->file($inKey)->isValid()) {
                                        $extension = $request->$inKey->extension();
                                        $fileName = strtolower(strtotime("now") . '.' . $extension);
                                        $storedPath = config('location.withdrawLog.path');
                                        $imageMake = Image::make($purifiedData[$inKey]);
                                        $imageMake->save($storedPath);

                                        $reqField[$inKey] = [
                                            'fieldValue' => $fileName,
                                            'type' => $inVal->type,
                                        ];
                                    }
                                } else {
                                    $reqField[$inKey] = [
                                        'fieldValue' => $v,
                                        'type' => $inVal->type,
                                    ];
                                }
                            }
                        }
                    }
                    $reqField['type'] = [
                        'fieldValue' => $request->type,
                        'type' => 'text',
                    ];
                    $reqField['bank_code'] = [
                        'fieldValue' => $request->bank,
                        'type' => 'text',
                    ];
                    $reqField['amount'] = [
                        'fieldValue' => $payout->amount * convertRate($request->currency, $payout),
                        'type' => 'text',
                    ];
                    $payout->information = $reqField;
                } else {
                    $payout->information = null;
                }
                $payout->currency_code = $request->currency_code;
                $payout->status = 1;
                $payout->save();


                $user[$payout->balance_type] = $user[$payout->balance_type] - $payout->net_amount;
                $user->save();

                $remarks = 'Withdraw Via ' . optional($payout->method)->name;
                BasicService::makeTransaction($user, $payout->amount, $payout->charge, '-', $payout->balance_type, $payout->trx_id, $remarks);

                $this->userNotify($user, $payout);

                return response()->json($this->withSuccess('Withdraw request Successfully Submitted. Wait For Confirmation.'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function userNotify($user, $withdraw)
    {
        try {
            $basic = (object)config('basic');

            $msg = [
                'username' => $user->username,
                'amount' => getAmount($withdraw->amount),
                'currency' => $basic->currency_symbol,
            ];
            $action = [
                "link" => route('admin.payout-request', $user->id),
                "icon" => "fa fa-money-bill-alt "
            ];
            $userAction = [
                "link" => route('user.payout.history'),
                "icon" => "fa fa-money-bill-alt "
            ];

            $this->userPushNotification($user, 'USER_NOTIFY_PAYOUT_REQUEST', $msg, $userAction);
            $this->adminPushNotification('ADMIN_NOTIFY_PAYOUT_REQUEST', $msg, $action);

            $this->sendMailSms($user, $type = 'USER_MAIL_PAYOUT_REQUEST', [
                'method_name' => optional($withdraw->method)->name,
                'amount' => getAmount($withdraw->amount),
                'charge' => getAmount($withdraw->charge),
                'currency' => $basic->currency_symbol,
                'trx' => $withdraw->trx_id,
            ]);

            $this->mailToAdmin($type = 'ADMIN_MAIL_PAYOUT_REQUEST', [
                'method_name' => optional($withdraw->method)->name,
                'amount' => getAmount($withdraw->amount),
                'charge' => getAmount($withdraw->charge),
                'currency' => $basic->currency_symbol,
                'trx' => $withdraw->trx_id,
            ]);
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function payoutFlutterwaveSubmit(Request $request, $trx_id)
    {
        $validateUser = Validator::make($request->all(),
            [
                'wallet_type' => ['required', Rule::in(['balance', 'interest_balance'])],
                'gateway' => 'required|integer',
                'amount' => ['required', 'numeric']
            ]);

        if ($validateUser->fails()) {
            return response()->json($this->withErrors(collect($validateUser->errors())->collapse()[0]));
        }

        $basic = (object)config('basic');
        try {
            $method = PayoutMethod::where('id', $request->gateway)->where('status', 1)->first();
            if (!$method) {
                return response()->json($this->withErrors('Method Not Found'));
            }
            $user = auth()->user();

            $charge = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);

            $finalAmo = $request->amount + $charge;

            if ($request->amount < $method->minimum_amount) {
                return response()->json($this->withErrors('Minimum payout Amount ' . round($method->minimum_amount, 2) . ' ' . $basic->currency));
            }
            if ($request->amount > $method->maximum_amount) {
                return response()->json($this->withErrors('Maximum payout Amount ' . round($method->maximum_amount, 2) . ' ' . $basic->currency));
            }

            if (getAmount($finalAmo) > $user[$request->wallet_type]) {
                return response()->json($this->withErrors('Insufficient ' . snake2Title($request->wallet_type) . ' For Withdraw.'));
            } else {
                $trx = strRandom();
                $payout = new PayoutLog();
                $payout->user_id = $user->id;
                $payout->method_id = $method->id;
                $payout->amount = getAmount($request->amount);
                $payout->charge = $charge;
                $payout->net_amount = $finalAmo;
                $payout->trx_id = $trx;
                $payout->status = 0;
                $payout->balance_type = $request->wallet_type;
                $payout->save();

                $purifiedData = Purify::clean($request->all());

                if (empty($purifiedData['transfer_name'])) {
                    return response()->json($this->withErrors('Transfer field is required'));
                }
                $validation = config('banks.' . $purifiedData['transfer_name'] . '.validation');

                $rules = [];
                $inputField = [];
                if ($validation != null) {
                    foreach ($validation as $key => $cus) {
                        $rules[$key] = 'required';
                        $inputField[] = $key;
                    }
                }

                if (getAmount($payout->net_amount) > $user[$payout->balance_type]) {
                    return response()->json($this->withErrors('Insufficient balance For Withdraw.'));
                }

                if ($request->transfer_name == 'NGN BANK' || $request->transfer_name == 'NGN DOM' || $request->transfer_name == 'GHS BANK'
                    || $request->transfer_name == 'KES BANK' || $request->transfer_name == 'ZAR BANK' || $request->transfer_name == 'ZAR BANK') {
                    $rules['bank'] = 'required';
                }

                $rules['currency_code'] = 'required';

                $validate = Validator::make($request->all(), $rules);

                if ($validate->fails()) {
                    return response()->json($this->withErrors(collect($validate->errors())->collapse()[0]));
                }

                $collection = collect($purifiedData);
                $reqField = [];
                $metaField = [];

                if (config('banks.' . $purifiedData['transfer_name'] . '.input_form') != null) {
                    foreach ($collection as $k => $v) {
                        foreach (config('banks.' . $purifiedData['transfer_name'] . '.input_form') as $inKey => $inVal) {
                            if ($k != $inKey) {
                                continue;
                            } else {

                                if ($inVal == 'meta') {
                                    $metaField[$inKey] = [
                                        'fieldValue' => $v,
                                        'type' => 'text',
                                    ];
                                } else {
                                    $reqField[$inKey] = [
                                        'fieldValue' => $v,
                                        'type' => 'text',
                                    ];
                                }
                            }
                        }
                    }

                    if ($request->transfer_name == 'NGN BANK' || $request->transfer_name == 'NGN DOM' || $request->transfer_name == 'GHS BANK'
                        || $request->transfer_name == 'KES BANK' || $request->transfer_name == 'ZAR BANK' || $request->transfer_name == 'ZAR BANK') {

                        $reqField['account_bank'] = [
                            'fieldValue' => $request->bank,
                            'type' => 'text',
                        ];
                    } elseif ($request->transfer_name == 'XAF/XOF MOMO') {
                        $reqField['account_bank'] = [
                            'fieldValue' => 'MTN',
                            'type' => 'text',
                        ];
                    } elseif ($request->transfer_name == 'FRANCOPGONE' || $request->transfer_name == 'mPesa' || $request->transfer_name == 'Rwanda Momo'
                        || $request->transfer_name == 'Uganda Momo' || $request->transfer_name == 'Zambia Momo') {
                        $reqField['account_bank'] = [
                            'fieldValue' => 'MPS',
                            'type' => 'text',
                        ];
                    }

                    if ($request->transfer_name == 'Barter') {
                        $reqField['account_bank'] = [
                            'fieldValue' => 'barter',
                            'type' => 'text',
                        ];
                    } elseif ($request->transfer_name == 'flutterwave') {
                        $reqField['account_bank'] = [
                            'fieldValue' => 'barter',
                            'type' => 'text',
                        ];
                    }


                    $reqField['amount'] = [
                        'fieldValue' => $payout->amount * convertRate($request->currency_code, $payout),
                        'type' => 'text',
                    ];

                    $payout->information = $reqField;
                    $payout->meta_field = $metaField;
                } else {
                    $payout->information = null;
                    $payout->meta_field = null;
                }

                $payout->status = 1;
                $payout->currency_code = $request->currency_code;
                $payout->save();

                $user[$payout->balance_type] = $user[$payout->balance_type] - $payout->net_amount;
                $user->save();

                $remarks = 'Withdraw Via ' . optional($payout->method)->name;
                BasicService::makeTransaction($user, $payout->amount, $payout->charge, '-', $payout->balance_type, $payout->trx_id, $remarks);

                $this->userNotify($user, $payout);
            }

            return response()->json($this->withSuccess('Withdraw request Successfully Submitted. Wait For Confirmation.'));
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function payoutSubmit(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'wallet_type' => ['required', Rule::in(['balance', 'interest_balance'])],
                'gateway' => 'required|integer',
                'amount' => ['required', 'numeric']
            ]);

        if ($validateUser->fails()) {
            return response()->json($this->withErrors(collect($validateUser->errors())->collapse()[0]));
        }

        $basic = (object)config('basic');

        try {
            $method = PayoutMethod::where('id', $request->gateway)->where('status', 1)->first();
            if (!$method) {
                return response()->json($this->withErrors('Method Not Found'));
            }
            $authWallet = auth()->user();

            $charge = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);

            $finalAmo = $request->amount + $charge;

            if ($request->amount < $method->minimum_amount) {
                return response()->json($this->withErrors('Minimum payout Amount ' . round($method->minimum_amount, 2) . ' ' . $basic->currency));
            }
            if ($request->amount > $method->maximum_amount) {
                return response()->json($this->withErrors('Maximum payout Amount ' . round($method->maximum_amount, 2) . ' ' . $basic->currency));
            }

            if (getAmount($finalAmo) > $authWallet[$request->wallet_type]) {
                return response()->json($this->withErrors('Insufficient ' . snake2Title($request->wallet_type) . ' For Withdraw.'));
            } else {
                $trx = strRandom();
                $withdraw = new PayoutLog();
                $withdraw->user_id = $authWallet->id;
                $withdraw->method_id = $method->id;
                $withdraw->amount = getAmount($request->amount);
                $withdraw->charge = $charge;
                $withdraw->net_amount = $finalAmo;
                $withdraw->trx_id = $trx;
                $withdraw->status = 0;
                $withdraw->balance_type = $request->wallet_type;
                $withdraw->save();

                $rules = [];
                $inputField = [];
                if (optional($withdraw->method)->input_form != null) {
                    foreach ($withdraw->method->input_form as $key => $cus) {
                        $rules[$key] = [$cus->validation];
                        if ($cus->type == 'file') {
                            array_push($rules[$key], 'image');
                            array_push($rules[$key], 'mimes:jpeg,jpg,png');
                            array_push($rules[$key], 'max:2048');
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

                if (optional($withdraw->method)->is_automatic == 1) {
                    $rules['currency_code'] = 'required';
                    if (optional($withdraw->method)->code == 'paypal') {
                        $rules['recipient_type'] = 'required';
                    }
                }

                $validateUser = Validator::make($request->all(), $rules);
                if ($validateUser->fails()) {
                    return response()->json($this->withErrors(collect($validateUser->errors())->collapse()[0]));
                }
                $user = auth()->user();

                if (getAmount($withdraw->net_amount) > $user[$withdraw->balance_type]) {
                    return response()->json($this->withErrors('Insufficient ' . snake2Title($withdraw->balance_type) . ' For Payout.'));
                } else {
                    $collection = collect($request);
                    $reqField = [];
                    if ($withdraw->method->input_form != null) {
                        foreach ($collection as $k => $v) {
                            foreach ($withdraw->method->input_form as $inKey => $inVal) {
                                if ($k != $inKey) {
                                    continue;
                                } else {
                                    if ($inVal->type == 'file') {
                                        if ($request->hasFile($inKey)) {
                                            $image = $request->file($inKey);
                                            $filename = time() . uniqid() . '.jpg';
                                            $location = config('location.withdrawLog.path');
                                            $reqField[$inKey] = [
                                                'fieldValue' => $filename,
                                                'type' => $inVal->type,
                                            ];
                                            try {
                                                $this->uploadImage($image, $location, $size = null, $old = null, $thumb = null, $filename);
                                            } catch (\Exception $exp) {
                                                return response()->json($this->withErrors('Image could not be uploaded.'));
                                            }

                                        }
                                    } else {
                                        $reqField[$inKey] = [
                                            'fieldValue' => $v,
                                            'type' => $inVal->type,
                                        ];
                                    }
                                }
                            }
                        }
                        if (optional($withdraw->method)->is_automatic == 1) {
                            $reqField['amount'] = [
                                'fieldValue' => $withdraw->amount * convertRate($request->currency_code, $withdraw),
                                'type' => 'text',
                            ];
                        }
                        if (optional($withdraw->method)->code == 'paypal') {
                            $reqField['recipient_type'] = [
                                'fieldValue' => $request->recipient_type,
                                'type' => 'text',
                            ];
                        }
                        $withdraw['information'] = $reqField;
                    } else {
                        $withdraw['information'] = null;
                    }

                    $withdraw->currency_code = @$request->currency_code;
                    $withdraw->status = 1;
                    $withdraw->save();

                    $user[$withdraw->balance_type] -= $withdraw->net_amount;
                    $user->save();


                    $remarks = 'Withdraw Via ' . optional($withdraw->method)->name;
                    BasicService::makeTransaction($user, $withdraw->amount, $withdraw->charge, '-', $withdraw->balance_type, $withdraw->trx_id, $remarks);

                    $this->userNotify($user, $withdraw);
                    return response()->json($this->withSuccess('Payout request Successfully Submitted. Wait For Confirmation.'));
                }
            }
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }
}
