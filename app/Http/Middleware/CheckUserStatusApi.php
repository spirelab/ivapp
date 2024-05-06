<?php

namespace App\Http\Middleware;

use App\Http\Traits\Notify;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatusApi
{
    use Notify;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ((Auth::user()->sms_verification == 1) && (Auth::user()->email_verification == 1) && (Auth::user()->status == 1) && (Auth::user()->two_fa_verify == 1)) {
            return $next($request);
        } else {
            if (Auth::user()->email_verification == 0) {

                $user->verify_code = code(6);
                $user->sent_at = Carbon::now();
                $user->save();
                $this->mailVerification($user, 'VERIFICATION_CODE', [
                    'code' => $user->verify_code
                ]);

                $result['status'] = 'failed';
                $result['message'] = 'Email Verification Required';
                return response($result);
            } elseif (Auth::user()->sms_verification == 0) {

                $user->verify_code = code(6);
                $user->sent_at = Carbon::now();
                $user->save();

                $this->smsVerification($user, 'VERIFICATION_CODE', [
                    'code' => $user->verify_code
                ]);


                $result['status'] = 'failed';
                $result['message'] = 'Mobile Verification Required';
                return response($result);
            } elseif (Auth::user()->status == 0) {
                $result['status'] = 'failed';
                $result['message'] = 'Your account has been suspend';
                return response($result);
            } elseif (Auth::user()->two_fa_verify == 0) {
                $result['status'] = 'failed';
                $result['message'] = 'Two FA Verification Required';
                return response($result);
            }
        }
    }
}
