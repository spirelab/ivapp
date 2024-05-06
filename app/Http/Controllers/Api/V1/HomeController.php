<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiValidation;
use App\Http\Traits\Notify;
use App\Models\ContentDetails;
use App\Models\Fund;
use App\Models\Gateway;
use App\Models\Investment;
use App\Models\Language;
use App\Models\ManagePlan;
use App\Models\ManageTime;
use App\Models\MoneyTransfer;
use App\Models\PayoutLog;
use App\Models\Ranking;
use App\Models\Template;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    use ApiValidation, Notify;

    public function planList()
    {
        $templateSection = ['investment'];
        $template = Template::templateMedia()->where('section_name', $templateSection)->first();
        if ($template) {
            $data['title'] = optional($template->description)->title ?? null;
            $data['sub_title'] = optional($template->description)->sub_title ?? null;
            $data['short_details'] = optional($template->description)->short_details ?? null;
        }

        $data['balance'] = trans('Deposit Balance - ' . config('basic.currency_symbol') . getAmount(auth()->user()->balance));
        $data['interest_balance'] = trans('Interest Balance - ' . config('basic.currency_symbol') . getAmount(auth()->user()->interest_balance));

        $planArr = [];
        $data['plans'] = ManagePlan::where('status', 1)->get()->map(function ($query) use ($planArr) {
            $planArr['id'] = $query->id;
            $planArr['name'] = $query->name;
            $planArr['min'] = $query->minimum_amount??0;
            $planArr['max'] = $query->maximum_amount??0;
            $planArr['price'] = $query->price;
            $planArr['profit'] = getAmount($query->profit);
            $planArr['profitType'] = ($query->profit_type == 1) ? '%' : config('basic.currency_symbol');
            $planArr['profitFor'] = ($query->is_lifetime == 1) ? 'Lifetime' : 'Every ' . $query->profitFor();
            $planArr['capitalBack'] = ($query->is_capital_back == 1) ? 'Yes' : 'No';
            $planArr['capitalEarning'] = $query->capitalCal();
            return $planArr;
        });
        return response()->json($this->withSuccess($data));
    }

    public function investHistory()
    {

        $basic = (object)config('basic');
        try {
            $array = [];
            $investments = tap(auth()->user()->invests()
                ->paginate(config('basic.paginate')), function ($paginatedInstance) use ($array, $basic) {
                return $paginatedInstance->getCollection()->transform(function ($query) use ($array, $basic) {
                    $array['planName'] = optional($query->plan)->name ?? null;
                    $array['amount'] = $query->amount ?? null;
                    $array['currency'] = $basic->currency ?? null;
                    $array['returnInvest'] = getAmount($query->profit) . ' ' . $basic->currency . ' ' . (($query->period == '-1') ? trans('For Lifetime') : 'per ' . trans($query->point_in_text));
                    $array['receivedAmount'] = $query->recurring_time . 'x' . $query->profit . ' = ' . getAmount($query->recurring_time * $query->profit) . ' ' . $basic->currency;
                    $array['percentPayment'] = ($query->status == 1) ? $query->nextPayment : null;
                    $array['nextPaymentDate'] = ($query->status == 1) ? $query->afterward : null;
                    return $array;
                });
            });

            if ($investments) {
                return response()->json($this->withSuccess($investments));
            } else {
                return response()->json($this->withErrors('No data found'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function fundHistory()
    {
        $basic = (object)config('basic');
        try {
            $array = [];
            $funds = tap(Fund::query()->where('user_id', auth()->id())->where('status', '!=', 0)
                ->where('plan_id', null)->orderBy('id', 'DESC')->with('gateway')
                ->paginate(config('basic.paginate')), function ($paginatedInstance) use ($array, $basic) {
                return $paginatedInstance->getCollection()->transform(function ($query) use ($array, $basic) {
                    $array['transactionId'] = $query->transaction ?? null;
                    $array['gateway'] = optional($query->gateway)->name ?? null;
                    $array['currency'] = $basic->currency ?? null;
                    $array['amount'] = getAmount($query->amount) . ' ' . $basic->currency;
                    $array['charge'] = getAmount($query->charge) . ' ' . $basic->currency;
                    $array['status'] = ($query->status == 1) ? 'Complete' : (($query->status == 2) ? 'Pending' : 'Cancel');
                    $array['time'] = dateTime($query->created_at, 'd M Y h:i A');
                    return $array;
                });
            });

            if ($funds) {
                return response()->json($this->withSuccess($funds));
            } else {
                return response()->json($this->withErrors('No data found'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function fundHistorySearch(Request $request)
    {
        $basic = (object)config('basic');
        $search = $request->all();

        $dateSearch = $request->date_time;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        try {
            $array = [];
            $funds = tap(Fund::query()->orderBy('id', 'DESC')
                ->where('user_id', auth()->id())->where('status', '!=', 0)
                ->when(isset($search['name']), function ($query) use ($search) {
                    return $query->where('transaction', 'LIKE', $search['name']);
                })
                ->when($date == 1, function ($query) use ($dateSearch) {
                    return $query->whereDate("created_at", $dateSearch);
                })
                ->when(isset($search['status']), function ($query) use ($search) {
                    return $query->where('status', $search['status']);
                })
                ->with('gateway')
                ->paginate(config('basic.paginate')), function ($paginatedInstance) use ($array, $basic) {
                return $paginatedInstance->getCollection()->transform(function ($query) use ($array, $basic) {
                    $array['transactionId'] = $query->transaction ?? null;
                    $array['gateway'] = optional($query->gateway)->name ?? null;
                    $array['currency'] = $basic->currency ?? null;
                    $array['amount'] = getAmount($query->amount) . ' ' . $basic->currency;
                    $array['charge'] = getAmount($query->charge) . ' ' . $basic->currency;
                    $array['status'] = ($query->status == 1) ? 'Complete' : (($query->status == 2) ? 'Pending' : 'Cancel');
                    $array['time'] = dateTime($query->created_at, 'd M Y h:i A');
                    return $array;
                });
            });

            if ($funds) {
                return response()->json($this->withSuccess($funds));
            } else {
                return response()->json($this->withErrors('No data found'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function moneyTransferPost(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'email' => 'required',
                'amount' => 'required',
                'wallet_type' => ['required', Rule::in(['balance', 'interest_balance'])],
                'password' => 'required'
            ]);

        if ($validateUser->fails()) {
            return response()->json($this->withErrors(collect($validateUser->errors())->collapse()[0]));
        }

        $basic = (object)config('basic');
        $email = trim($request->email);

        $receiver = User::where('email', $email)->first();


        if (!$receiver) {
            return response()->json($this->withErrors('This Email  could not Found!'));
        }
        if ($receiver->id == Auth::id()) {
            return response()->json($this->withErrors('This Email  could not Found!'));
        }

        if ($receiver->status == 0) {
            return response()->json($this->withErrors('Invalid User!'));
        }


        if ($request->amount < $basic->min_transfer) {
            return response()->json($this->withErrors('Minimum Transfer Amount ' . $basic->min_transfer . ' ' . $basic->currency));
        }
        if ($request->amount > $basic->max_transfer) {
            return response()->json($this->withErrors('Maximum Transfer Amount ' . $basic->max_transfer . ' ' . $basic->currency));
        }

        try {

            $transferCharge = ($request->amount * $basic->transfer_charge) / 100;

            $user = Auth::user();
            $wallet_type = $request->wallet_type;
            if ($user[$wallet_type] >= ($request->amount + $transferCharge)) {

                if (Hash::check($request->password, $user->password)) {

                    $sendMoneyCheck = MoneyTransfer::where('sender_id', $user->id)->where('receiver_id', $receiver->id)->latest()->first();

                    if (isset($sendMoneyCheck) && Carbon::parse($sendMoneyCheck->send_at) > Carbon::now()) {

                        $time = Carbon::parse($sendMoneyCheck->send_at);
                        $delay = $time->diffInSeconds(Carbon::now());
                        $delay = gmdate('i:s', $delay);

                        return response()->json($this->withErrors('You can send money to this user after  delay ' . $delay . ' minutes'));
                    } else {

                        $user[$wallet_type] = round(($user[$wallet_type] - ($transferCharge + $request->amount)), 2);
                        $user->save();

                        $receiver[$wallet_type] += round($request->amount, 2);
                        $receiver->save();

                        $trans = strRandom();

                        $sendTaka = new MoneyTransfer();
                        $sendTaka->sender_id = $user->id;
                        $sendTaka->receiver_id = $receiver->id;
                        $sendTaka->amount = round($request->amount, 2);
                        $sendTaka->charge = $transferCharge;
                        $sendTaka->trx = $trans;
                        $sendTaka->send_at = Carbon::parse()->addMinutes(1);
                        $sendTaka->save();

                        $transaction = new Transaction();
                        $transaction->user_id = $user->id;
                        $transaction->amount = round($request->amount, 2);
                        $transaction->charge = $transferCharge;
                        $transaction->trx_type = '-';
                        $transaction->balance_type = $wallet_type;
                        $transaction->remarks = 'Balance Transfer to  ' . $receiver->email;
                        $transaction->trx_id = $trans;
                        $transaction->final_balance = $user[$wallet_type];
                        $transaction->save();


                        $transaction = new Transaction();
                        $transaction->user_id = $receiver->id;
                        $transaction->amount = round($request->amount, 2);
                        $transaction->charge = 0;
                        $transaction->trx_type = '+';
                        $transaction->balance_type = $wallet_type;
                        $transaction->remarks = 'Balance Transfer From  ' . $user->email;
                        $transaction->trx_id = $trans;
                        $transaction->final_balance = $receiver[$wallet_type];
                        $transaction->save();

                        $this->moneyTransNotify($user, $receiver, $request->amount, $basic);

                        return response()->json($this->withSuccess('Balance Transfer  has been Successful'));
                    }
                } else {
                    return response()->json($this->withErrors('Password Does Not Match!'));
                }
            } else {
                return response()->json($this->withErrors('Insufficient Balance!'));
            }

        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function moneyTransNotify($user, $receiver, $amount, $basic)
    {
        try {

            $currentDate = dateTime(Carbon::now());
            $msg = [
                'send_user' => $user->fullname,
                'to_user' => $receiver->fullname,
                'amount' => $amount,
                'currency' => $basic->currency,
            ];
            $action = [
                "link" => "#",
                "icon" => "fa fa-money-bill-alt text-white"
            ];

            $userAction = [
                "link" => route('user.home'),
                "icon" => "fa fa-money-bill-alt text-white"
            ];

            $this->adminPushNotification('ADMIN_NOTIFY_BALANCE_TRANSFER', $msg, $action);
            $this->userPushNotification($user, 'SENDER_NOTIFY_BALANCE_TRANSFER', $msg, $userAction);
            $this->userPushNotification($receiver, 'RECEIVER_NOTIFY_BALANCE_TRANSFER', $msg, $userAction);

            $this->mailToAdmin($type = 'ADMIN_MAIL_BALANCE_TRANSFER', [
                'send_user' => $user->fullname,
                'to_user' => $receiver->fullname,
                'amount' => $amount,
                'currency' => $basic->currency,
                'date' => $currentDate
            ]);

            $this->sendMailSms($user, 'SENDER_MAIL_BALANCE_TRANSFER', [
                'send_user' => $user->fullname,
                'to_user' => $receiver->fullname,
                'amount' => $amount,
                'currency' => $basic->currency,
                'date' => $currentDate
            ]);

            $this->sendMailSms($receiver, 'RECEIVER_MAIL_BALANCE_TRANSFER', [
                'send_user' => $user->fullname,
                'to_user' => $receiver->fullname,
                'amount' => $amount,
                'currency' => $basic->currency,
                'date' => $currentDate
            ]);

            return true;

        } catch (\Exception $e) {
            return true;
        }
    }

    public function transaction()
    {
        $basic = (object)config('basic');
        try {
            $array = [];
            $transactions = tap(auth()->user()->transaction()->orderBy('id', 'DESC')
                ->paginate(config('basic.paginate')), function ($paginatedInstance) use ($array, $basic) {
                return $paginatedInstance->getCollection()->transform(function ($query) use ($array, $basic) {
                    $array['transactionId'] = $query->trx_id ?? null;
                    $array['color'] = ($query->trx_type == '+') ? 'success' : 'danger';
                    $array['amount'] = $query->trx_type . '' . getAmount($query->amount, $basic->fraction_number);
                    $array['currency'] = $basic->currency ?? null;
                    $array['symbol'] = $basic->currency_symbol ?? null;
                    $array['remarks'] = $query->remarks ?? null;
                    $array['time'] = dateTime($query->created_at, 'd M Y h:i A') ?? null;
                    return $array;
                });
            });

            if ($transactions) {
                return response()->json($this->withSuccess($transactions));
            } else {
                return response()->json($this->withErrors('No data found'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function transactionSearch(Request $request)
    {
        $basic = (object)config('basic');
        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        try {
            $array = [];
            
            $transactions = tap(Transaction::where('user_id', auth()->id())->with('user')
                ->when(@$search['transaction_id'], function ($query) use ($search) {
                    return $query->where('trx_id', 'LIKE', "%{$search['transaction_id']}%");
                })
                ->when(@$search['remark'], function ($query) use ($search) {
                    return $query->where('remarks', 'LIKE', "%{$search['remark']}%");
                })
                ->when($date == 1, function ($query) use ($dateSearch) {
                    return $query->whereDate("created_at", $dateSearch);
                })
                ->orderBy('id', 'DESC')
                ->paginate(config('basic.paginate')), function ($paginatedInstance) use ($array, $basic) {
                return $paginatedInstance->getCollection()->transform(function ($query) use ($array, $basic) {
                    $array['transactionId'] = $query->trx_id ?? null;
                    $array['color'] = ($query->trx_type == '+') ? 'success' : 'danger';
                    $array['amount'] = $query->trx_type . '' . getAmount($query->amount, $basic->fraction_number);
                    $array['currency'] = $basic->currency ?? null;
                    $array['symbol'] = $basic->currency_symbol ?? null;
                    $array['remarks'] = $query->remarks ?? null;
                    $array['time'] = dateTime($query->created_at, 'd M Y h:i A') ?? null;
                    return $array;
                });
            });

            if ($transactions) {
                return response()->json($this->withSuccess($transactions));
            } else {
                return response()->json($this->withErrors('No data found'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function payoutHistory()
    {
        $basic = (object)config('basic');
        try {
            $array = [];
            $payoutLogs = tap(PayoutLog::whereUser_id(auth()->id())->where('status', '!=', 0)->latest()
                ->with('user', 'method')
                ->paginate(config('basic.paginate')), function ($paginatedInstance) use ($array, $basic) {
                return $paginatedInstance->getCollection()->transform(function ($query) use ($array, $basic) {
                    $array['transactionId'] = $query->trx_id ?? null;
                    $array['gateway'] = optional($query->method)->name ?? null;
                    $array['amount'] = getAmount($query->amount, $basic->fraction_number) . ' ' . $basic->currency;
                    $array['charge'] = getAmount($query->charge, $basic->fraction_number) . ' ' . $basic->currency;
                    $array['currency'] = $basic->currency ?? null;
                    $array['status'] = ($query->status == 1) ? 'Pending' : (($query->status == 2) ? 'Complete' : 'Cancel');
                    $array['time'] = dateTime($query->created_at, 'd M Y h:i A') ?? null;
                    $array['adminFeedback'] = $query->feedback ?? null;
                    $array['paymentInformation'] = [];
                    if ($query->information) {
                        foreach ($query->information as $key => $info) {
                            $array['paymentInformation'][$key] = $info->field_name ?? $info->fieldValue;
                        }
                    }
                    return $array;
                });
            });

            if ($payoutLogs) {
                return response()->json($this->withSuccess($payoutLogs));
            } else {
                return response()->json($this->withErrors('No data found'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function payoutHistorySearch(Request $request)
    {
        $basic = (object)config('basic');
        $search = $request->all();

        $dateSearch = $request->date_time;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        try {
            $array = [];
            $payoutLogs = tap(PayoutLog::orderBy('id', 'DESC')->where('user_id', auth()->id())
                ->where('status', '!=', 0)
                ->when(isset($search['name']), function ($query) use ($search) {
                    return $query->where('trx_id', 'LIKE', $search['name']);
                })
                ->when($date == 1, function ($query) use ($dateSearch) {
                    return $query->whereDate("created_at", $dateSearch);
                })
                ->when(isset($search['status']), function ($query) use ($search) {
                    return $query->where('status', $search['status']);
                })
                ->with('user', 'method')
                ->paginate(config('basic.paginate')), function ($paginatedInstance) use ($array, $basic) {
                return $paginatedInstance->getCollection()->transform(function ($query) use ($array, $basic) {
                    $array['transactionId'] = $query->trx_id ?? null;
                    $array['gateway'] = optional($query->method)->name ?? null;
                    $array['amount'] = getAmount($query->amount, $basic->fraction_number) . ' ' . $basic->currency;
                    $array['charge'] = getAmount($query->charge, $basic->fraction_number) . ' ' . $basic->currency;
                    $array['currency'] = $basic->currency ?? null;
                    $array['status'] = ($query->status == 1) ? 'Pending' : (($query->status == 2) ? 'Complete' : 'Cancel');
                    $array['time'] = dateTime($query->created_at, 'd M Y h:i A') ?? null;
                    $array['adminFeedback'] = $query->feedback ?? null;
                    $array['paymentInformation'] = [];
                    if ($query->information) {
                        foreach ($query->information as $key => $info) {
                            $array['paymentInformation'][$key] = $info->field_name ?? $info->fieldValue;
                        }
                    }
                    return $array;
                });
            });

            if ($payoutLogs) {
                return response()->json($this->withSuccess($payoutLogs));
            } else {
                return response()->json($this->withErrors('No data found'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function referral()
    {
        try {
            $data['referralLink'] = route('register.sponsor', [Auth::user()->username]);
            $data['referrals'] = getLevelUser(auth()->id());
            $data['levelCount'] = count($data['referrals']);
            if (empty($data['referrals'])) {
                $data['referrals'] = [
                    "1" => []
                ];
            }
            if ($data) {
                return response()->json($this->withSuccess($data));
            } else {
                return response()->json($this->withErrors('No data found'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function referralBonus()
    {
        $basic = (object)config('basic');
        try {
            $array = [];
            $transactions = tap(auth()->user()->referralBonusLog()->latest()->with('bonusBy:id,firstname,lastname')
                ->paginate(config('basic.paginate')), function ($paginatedInstance) use ($array, $basic) {
                return $paginatedInstance->getCollection()->transform(function ($query) use ($array, $basic) {
                    $array['bonusFrom'] = optional($query->bonusBy)->fullname ?? null;
                    $array['amount'] = getAmount($query->amount, $basic->fraction_number) . ' ' . $basic->currency;
                    $array['remarks'] = $query->remarks;
                    $array['time'] = dateTime($query->created_at, 'd M Y h:i A') ?? null;
                    return $array;
                });
            });

            if ($transactions) {
                return response()->json($this->withSuccess($transactions));
            } else {
                return response()->json($this->withErrors('No data found'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function referralBonusSearch(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $basic = (object)config('basic');

        try {
            $array = [];
            $transactions = tap(auth()->user()->referralBonusLog()->latest()->with('bonusBy:id,firstname,lastname')
                ->when(isset($search['search_user']), function ($query) use ($search) {
                    return $query->whereHas('bonusBy', function ($q) use ($search) {
                        $q->where(DB::raw('concat(firstname, " ", lastname)'), 'LIKE', "%{$search['search_user']}%")
                            ->orWhere('firstname', 'LIKE', '%' . $search['search_user'] . '%')
                            ->orWhere('lastname', 'LIKE', '%' . $search['search_user'] . '%')
                            ->orWhere('username', 'LIKE', '%' . $search['search_user'] . '%');
                    });
                })
                ->when($date == 1, function ($query) use ($dateSearch) {
                    return $query->whereDate("created_at", $dateSearch);
                })
                ->paginate(config('basic.paginate')), function ($paginatedInstance) use ($array, $basic) {
                return $paginatedInstance->getCollection()->transform(function ($query) use ($array, $basic) {
                    $array['bonusFrom'] = optional($query->bonusBy)->fullname ?? null;
                    $array['amount'] = getAmount($query->amount, $basic->fraction_number) . ' ' . $basic->currency;
                    $array['remarks'] = $query->remarks;
                    $array['time'] = dateTime($query->created_at, 'd M Y h:i A') ?? null;
                    return $array;
                });
            });

            if ($transactions) {
                return response()->json($this->withSuccess($transactions));
            } else {
                return response()->json($this->withErrors('No data found'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function badge()
    {
        $basic = (object)config('basic');
        try {
            $allBadges = Ranking::select(['rank_icon', 'rank_lavel', 'description', 'min_invest', 'min_deposit', 'min_earning'])
                ->orderBy('sort_by', 'ASC')->get()->map(function ($query) use ($basic) {
                    $query->rank_icon = getFile(config('location.rank.path') . $query->rank_icon);
                    $query->min_invest = $basic->currency_symbol . '' . getAmount($query->min_invest);
                    $query->min_deposit = $basic->currency_symbol . '' . getAmount($query->min_deposit);
                    $query->min_earning = $basic->currency_symbol . '' . getAmount($query->min_earning);
                    $query->is_current_rank = false;
                    if (isset(auth()->user()->last_lavel) && auth()->user()->last_lavel == $query->rank_lavel) {
                        $query->is_current_rank = true;
                    }
                    return $query;
                });

            if ($allBadges) {
                return response()->json($this->withSuccess($allBadges));
            } else {
                return response()->json($this->withErrors('No data found'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function planBuyWallet(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'balance_type' => 'required',
                'amount' => 'required|numeric',
                'plan_id' => 'required',
            ]);

        if ($validateUser->fails()) {
            return response()->json($this->withErrors(collect($validateUser->errors())->collapse()[0]));
        }

        $user = auth()->user();
        $plan = ManagePlan::where('id', $request->plan_id)->where('status', 1)->first();
        if (!$plan) {
            return response()->json($this->withErrors('Invalid Plan Request'));
        }

        $timeManage = ManageTime::where('time', $plan->schedule)->first();

        $balance_type = $request->balance_type;
        if (!in_array($balance_type, ['balance', 'interest_balance', 'checkout'])) {
            return response()->json($this->withErrors('Invalid Wallet Type'));
        }


        $amount = $request->amount;
        $basic = (object)config('basic');
        if ($plan->fixed_amount == '0' && $amount < $plan->minimum_amount) {
            return response()->json($this->withErrors("Invest Limit " . $plan->price));
        } elseif ($plan->fixed_amount == '0' && $amount > $plan->maximum_amount) {
            return response()->json($this->withErrors("Invest Limit " . $plan->price));
        } elseif ($plan->fixed_amount != '0' && $amount != $plan->fixed_amount) {
            return response()->json($this->withErrors("Please invest " . $plan->price));
        }

        if ($amount > $user->$balance_type) {
            return response()->json($this->withErrors("Insufficient Balance"));
        }

        $new_balance = getAmount($user->$balance_type - $amount);
        $user->$balance_type = $new_balance;
        $user->total_invest += $request->amount;
        $user->save();

        $trx = strRandom();
        $remarks = 'Invested On ' . $plan->name;
        BasicService::makeTransaction($user, $amount, 0, $trx_type = '-', $balance_type, $trx, $remarks);


        $profit = ($plan->profit_type == 1) ? ($amount * $plan->profit) / 100 : $plan->profit;
        $maturity = ($plan->is_lifetime == 1) ? '-1' : $plan->repeatable;

        //// For Fixed Plan
        if ($plan->fixed_amount != 0 && ($plan->fixed_amount == $amount)) {
            BasicService::makeInvest($user, $plan, $amount, $profit, $maturity, $timeManage, $trx);
        } elseif ($plan->fixed_amount == 0) {
            BasicService::makeInvest($user, $plan, $amount, $profit, $maturity, $timeManage, $trx);
        }

        if ($basic->investment_commission == 1) {
            BasicService::setBonus($user, $request->amount, $type = 'invest');
        }

        $currentDate = Carbon::now();
        $msg = [
            'username' => $user->username,
            'amount' => getAmount($amount),
            'currency' => $basic->currency_symbol,
            'plan_name' => $plan->name
        ];
        $action = [
            "link" => route('admin.user.plan-purchaseLog', $user->id),
            "icon" => "fa fa-money-bill-alt "
        ];
        $userAction = [
            "link" => route('user.invest-history'),
            "icon" => "fa fa-money-bill-alt "
        ];
        $this->adminPushNotification('PLAN_PURCHASE_NOTIFY_TO_ADMIN', $msg, $action);
        $this->userPushNotification($user, 'PLAN_PURCHASE_NOTIFY_TO_USER', $msg, $userAction);

        $this->sendMailSms($user, $type = 'PLAN_PURCHASE_MAIL_TO_USER', [
            'transaction_id' => $trx,
            'amount' => getAmount($amount),
            'currency' => $basic->currency_symbol,
            'profit_amount' => $profit,
        ]);

        $this->mailToAdmin($type = 'PLAN_PURCHASE_MAIL_TO_ADMIN', [
            'username' => $user->username,
            'amount' => getAmount($amount),
            'currency' => $basic->currency_symbol,
            'plan_name' => $plan->name,
            'date' => $currentDate,
        ]);

        return response()->json($this->withSuccess('Plan has been Purchased Successfully'));
    }

    public function dashboard()
    {
        $basic = (object)config('basic');
        try {
            $user = auth()->user();
            $data['currency'] = config('basic.currency_symbol');
            $data['mainBalance'] = getAmount($user->balance);
            $data['interestBalance'] = getAmount($user->interest_balance);
            $data['totalDeposit'] = getAmount($user->funds()->whereNull('plan_id')->whereStatus(1)->sum('amount'));
            $data['totalEarn'] = getAmount($user->transaction()->where('balance_type', 'interest_balance')->where('trx_type', '+')->sum('amount'));
            $data['totalPayout'] = getAmount($user->payout()->whereStatus(2)->sum('amount'));
            $data['totalReferralBonus'] = getAmount($user->referralBonusLog()->where('type', 'deposit')->sum('amount')) + getAmount($user->referralBonusLog()->where('type', 'invest')->sum('amount'));

            $roi = Investment::where('user_id', $user->id)
                ->selectRaw('SUM( amount ) AS totalInvestAmount')
                ->selectRaw('COUNT( id ) AS totalInvest')
                ->selectRaw('COUNT(CASE WHEN status = 0  THEN id END) AS completed')
                ->get()->makeHidden('nextPayment')->toArray();
            $data['roi'] = collect($roi)->collapse();

            $data['investComplete'] = getPercent($data['roi']['totalInvest'], $data['roi']['completed']);
            $data['ticket'] = Ticket::where('user_id', $user->id)->count();

            $user_rankings = Ranking::where('rank_lavel', $user->last_lavel)->first();
            $data['rankLevel'] = null;
            $data['rankName'] = null;
            $data['rankImage'] = null;

            if ($user_rankings) {
                $data['rankLevel'] = $user_rankings->rank_lavel;
                $data['rankName'] = $user_rankings->rank_name;
                $data['rankImage'] = getFile(config('location.rank.path') . $user_rankings->rank_icon);
            }
            $data['userImage'] = getFile(config('location.user.path') . $user->image);

            $array = [];

            $data['transaction'] = Transaction::where('user_id', $user->id)->orderBy('id', 'DESC')
                ->limit(10)->get()->map(function ($query) {
                    $array['amount'] = getAmount($query->amount, 2) ?? 0;
                    $array['charge'] = getAmount($query->charge, 2) ?? 0;
                    $array['trx_type'] = $query->trx_type;
                    $array['balance_type'] = $query->balance_type;
                    $array['remarks'] = $query->remarks;
                    $array['trx_id'] = $query->trx_id;
                    $array['time'] = $query->created_at;
                    return $array;
                });

            return $this->withSuccess($data);
        } catch (\Exception $e) {
            return $this->withErrors($e->getMessage());
        }
    }

    public function pusherConfig()
    {
        try {
            $data['apiKey'] = env('PUSHER_APP_KEY');
            $data['cluster'] = env('PUSHER_APP_CLUSTER');
            $data['channel'] = 'user-notification.' . Auth::id();
            $data['event'] = 'UserNotification';

            return $this->withSuccess($data);
        } catch (\Exception $e) {
            return $this->withErrors($e->getMessage());
        }
    }

    public function appConfig()
    {
        try {
            $basic = basicControl();
            $data['baseColor'] = $basic->app_color;
            $data['version'] = $basic->app_version;
            $data['appBuild'] = $basic->app_build;
            $data['isMajor'] = $basic->is_major;
            $data['paymentSuccessUrl'] = route('success');
            $data['paymentFailedUrl'] = route('failed');
            return response()->json($this->withSuccess($data));
        } catch (\Exception $exception) {
            return response()->json($this->withErrors($exception->getMessage()));
        }
    }

    public function appSteps()
    {
        try {
            $contentSection = ['app-steps'];
            $data = [];
            $data['steps'] = ContentDetails::select('id', 'content_id', 'description', 'created_at')
                ->whereHas('content', function ($query) use ($contentSection) {
                    return $query->whereIn('name', $contentSection);
                })
                ->with(['content:id,name',
                    'content.contentMedia' => function ($q) {
                        $q->select(['content_id', 'description']);
                    }])
                ->get()->map(function ($query) use ($data) {
                    $data['title'] = $query->description->title;
                    $data['description'] = $query->description->description;
                    $data['image'] = getFile(config('location.content.path') . $query->content->contentMedia->description->image);
                    return $data;
                });
            return response()->json($this->withSuccess($data));
        } catch (\Exception $exception) {
            return response()->json($this->withErrors($exception->getMessage()));
        }
    }

    public function language($id = null)
    {
        try {
            if (!$id) {
                $data['languages'] = Language::select(['id', 'name', 'short_name'])->where('is_active', 1)->get();
                return response()->json($this->withSuccess($data));
            }
            $lang = Language::where('is_active', 1)->find($id);
            if (!$lang) {
                return response()->json($this->withErrors('Record not found'));
            }

            $json = file_get_contents(resource_path('lang/') . $lang->short_name . '.json');
            if (empty($json)) {
                return response()->json($this->withErrors('File Not Found.'));
            }

            $json = json_decode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            return response()->json($this->withSuccess($json));
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

}
