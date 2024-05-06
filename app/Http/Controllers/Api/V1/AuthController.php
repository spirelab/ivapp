<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiValidation;
use App\Http\Traits\Notify;
use App\Mail\SendMail;
use App\Models\Ranking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    use ApiValidation, Notify;

    public function registerUserForm()
    {
        try {
            if (config('basic.registration') == 0) {
                return response()->json($this->withErrors("Registration Has Been Disabled."));
            }

            $info = json_decode(json_encode(getIpInfo()), true);
            $country_code = null;
            if (!empty($info['code'])) {
                $data['country_code'] = @$info['code'][0];
            }
            $data['countries'] = config('country');
            return response()->json($this->withSuccess($data));
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function registerUser(Request $request)
    {
        $basic = (object)config('basic');
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'firstname' => 'required|string|max:91',
                    'lastname' => 'required|string|max:91',
                    'username' => 'required|alpha_dash|min:5|unique:users,username',
                    'email' => 'required|string|email|max:255|unique:users,email',
                    'country_code' => 'max:5',
                    'phone_code' => 'required',
                    'phone' => 'required',
                    'password' => 'required|min:6|confirmed'
                ]);

            if ($validateUser->fails()) {
                return response()->json($this->withErrors(collect($validateUser->errors())->collapse()[0]));
            }

            if ($request->sponsor != null) {
                $sponsorId = User::where('username', $request->sponsor)->first();
            } else {
                $sponsorId = null;
            }

            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'email' => $request->email,
                'referral_id' => ($sponsorId != null) ? $sponsorId->id : null,
                'country_code' => $request->country_code,
                'phone_code' => $request->phone_code,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'email_verification' => ($basic->email_verification) ? 0 : 1,
                'sms_verification' => ($basic->sms_verification) ? 0 : 1,
            ]);

            $msg = [
                'fullname' => $user->fullname,
            ];
            $action = [
                "link" => route('admin.user-edit', $user->id),
                "icon" => "fas fa-user text-white"
            ];
            $userAction = [
                "link" => "#",
                "icon" => "fas fa-user text-white"
            ];

            $this->adminPushNotification('REGISTER_NEW_USER_NOTIFY_TO_ADMIN', $msg, $action);
            $this->userPushNotification($user, 'REGISTER_NEW_USER_NOTIFY_TO_USER', $msg, $userAction);

            $currentDate = dateTime(Carbon::now());
            $this->sendMailSms($user, $type = 'REGISTER_CONFIRM_MAIL_TO_USER', [
                'name' => $user->fullname,
                'date' => $currentDate,
            ]);

            $this->mailToAdmin($type = 'REGISTER_CONFIRM_MAIL_TO_ADMIN', [
                'name' => $user->fullname,
                'email' => $user->email,
                'date' => $currentDate,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ]);


        } catch (\Throwable $th) {
            return response()->json($this->withErrors($th->getMessage()));
        }
    }

    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'username' => 'required',
                    'password' => 'required'
                ]);

            if ($validateUser->fails()) {
                return response()->json($this->withErrors(collect($validateUser->errors())->collapse()[0]));
            }

            if (!Auth::attempt($request->only(['username', 'password']))) {
                return response()->json($this->withErrors('Username & Password does not match with our record.'));
            }

            $user = User::where('username', $request->username)->first();
            $this->authenticated($user);

            if ($user->status == 0) {
                return response()->json($this->withErrors('You are banned from this application.Please contact with the administration'));
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ]);

        } catch (\Throwable $th) {
            return response()->json($this->withErrors($th->getMessage()));
        }
    }

    public function authenticated($user)
    {
        try {
            $user->last_login = Carbon::now();
            $user->two_fa_verify = ($user->two_fa == 1) ? 0 : 1;
            $user->save();

            if ($user) {

                $interestBalance = $user->total_interest_balance; //5
                $investBalance = $user->total_invest; //50
                $depositBalance = $user->total_deposit; //5.0

                $badges = Ranking::where([
                    ['min_invest', '<=', $investBalance],
                    ['min_deposit', '<=', $depositBalance],
                    ['min_earning', '<=', $interestBalance]])->where('status', 1)->get();


                if ($badges) {
                    foreach ($badges as $badge) {
                        if (($user->total_invest >= $badge->min_invest) && ($user->total_deposit >= $badge->min_deposit) && ($user->total_interest_balance >= $badge->min_earning)) {
                            $user->last_lavel = $badge->rank_lavel;
                            $user->save();
                            $userBadge = $badge;
                        }
                    }


                    if (isset($userBadge) && ($user->last_lavel == NULL || $userBadge->rank_lavel != $user->last_lavel)) {
                        $user->last_lavel = $userBadge->rank_lavel;
                        $user->save();

                        $msg = [
                            'user' => $user->fullname,
                            'badge' => $userBadge->rank_lavel,
                        ];

                        $adminAction = [
                            "link" => route('admin.users'),
                            "icon" => "fa fa-user text-white"
                        ];

                        $userAction = [
                            "link" => route('user.profile'),
                            "icon" => "fa fa-user text-white"
                        ];

                        $user->userPushNotification($user, 'BADGE_NOTIFY_TO_USER', $msg, $userAction);
                        $user->adminPushNotification('BADGE_NOTIFY_TO_ADMIN', $msg, $adminAction);

                        $currentDate = Carbon::now();
                        $user->sendMailSms($user, $type = 'BADGE_MAIL_TO_USER', [
                            'user' => $user->fullname,
                            'badge' => $userBadge->rank_lavel,
                            'date' => $currentDate
                        ]);

                        $user->mailToAdmin($type = 'BADGE_MAIL_TO_ADMIN', [
                            'user' => $user->fullname,
                            'badge' => $userBadge->rank_lavel,
                            'date' => $currentDate
                        ]);
                    }

                }
            }


            $currentDate = dateTime(Carbon::now());
            $msg = [
                'name' => $user->fullname,
            ];

            $action = [
                "link" => "#",
                "icon" => "fas fa-user text-white"
            ];

            $this->userPushNotification($user, 'LOGIN_NOTIFY_TO_USER', $msg, $action);

            $this->sendMailSms($user, $type = 'LOGIN_MAIL_TO_USER', [
                'name' => $user->fullname,
                'last_login_time' => $currentDate
            ]);
            return true;
        } catch (\Exception $e) {
            return true;
        }
    }

    public function getEmailForRecoverPass(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
            ]);

        if ($validateUser->fails()) {
            return response()->json($this->withErrors(collect($validateUser->errors())->collapse()[0]));
        }

        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json($this->withErrors('Email does not exit on record'));
            }

            $code = rand(10000, 99999);
            $data['email'] = $request->email;
            $data['message'] = 'OTP has been send';
            $user->verify_code = $code;
            $user->save();

            $basic = basicControl();
            $message = 'Your Password Recovery Code is ' . $code;
            $email_from = $basic->sender_email;
            @Mail::to($request->email)->send(new SendMail($email_from, "Recovery Code", $message));

            return response()->json($this->withSuccess($data));
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function getCodeForRecoverPass(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'code' => 'required',
                'email' => 'required|email',
            ]);

        if ($validateUser->fails()) {
            return response()->json($this->withErrors(collect($validateUser->errors())->collapse()[0]));
        }

        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json($this->withErrors('Email does not exit on record'));
            }

            if ($user->verify_code == $request->code && $user->updated_at > Carbon::now()->subMinutes(5)) {
                $user->verify_code = null;
                $user->save();
                return response()->json($this->withSuccess('Code Matching'));
            }

            return response()->json($this->withErrors('Invalid Code'));
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function updatePass(Request $request)
    {
        if (config('basic.strong_password') == 0) {
            $rules['password'] = ['required', 'min:6', 'confirmed'];
        } else {
            $rules['password'] = ["required", 'confirmed',
                Password::min(6)->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()];
        }
        $rules['email'] = ['required', 'email'];

        $validateUser = Validator::make($request->all(), $rules);

        if ($validateUser->fails()) {
            return response()->json($this->withErrors(collect($validateUser->errors())->collapse()[0]));
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json($this->withErrors('Email does not exit on record'));
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json($this->withSuccess('Password Updated'));
    }

}
