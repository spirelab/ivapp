<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('payout/{code}', 'Admin\PayoutRecordController@payout')->name('payout');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('language/{id?}', 'Api\V1\HomeController@language');
Route::get('app/config', 'Api\V1\HomeController@appConfig');
Route::get('app/steps', 'Api\V1\HomeController@appSteps');
Route::get('/register/form', 'Api\V1\AuthController@registerUserForm');
Route::post('/register', 'Api\V1\AuthController@registerUser');
Route::post('/login', 'Api\V1\AuthController@loginUser');
Route::post('/recovery-pass/get-email', 'Api\V1\AuthController@getEmailForRecoverPass');
Route::post('/recovery-pass/get-code', 'Api\V1\AuthController@getCodeForRecoverPass');
Route::post('/update-pass', 'Api\V1\AuthController@updatePass');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware('userCheckApi')->group(function () {
        Route::get('/plan', 'Api\V1\HomeController@planList');
        Route::post('plan-buy/wallet', 'Api\V1\HomeController@planBuyWallet');

        Route::get('invest-history', 'Api\V1\HomeController@investHistory');
        Route::get('fund-history', 'Api\V1\HomeController@fundHistory');
        Route::get('fund-history/search', 'Api\V1\HomeController@fundHistorySearch');
        Route::post('money-transfer/post', 'Api\V1\HomeController@moneyTransferPost');

        Route::get('transaction', 'Api\V1\HomeController@transaction');
        Route::get('transaction/search', 'Api\V1\HomeController@transactionSearch');
        Route::get('payout-history', 'Api\V1\HomeController@payoutHistory');
        Route::get('payout-history/search', 'Api\V1\HomeController@payoutHistorySearch');
        Route::get('referral', 'Api\V1\HomeController@referral');
        Route::get('referral-bonus', 'Api\V1\HomeController@referralBonus');
        Route::get('referral-bonus/search', 'Api\V1\HomeController@referralBonusSearch');
        Route::get('badge', 'Api\V1\HomeController@badge');

        Route::get('support-ticket/list', 'Api\V1\SupportTicketController@ticketList');
        Route::post('support-ticket/create', 'Api\V1\SupportTicketController@ticketCreate');
        Route::get('support-ticket/view/{id}', 'Api\V1\SupportTicketController@ticketView');
        Route::get('support-ticket/download/{id}', 'Api\V1\SupportTicketController@ticketDownlaod')->name('api.ticket.download');
        Route::post('support-ticket/reply', 'Api\V1\SupportTicketController@ticketReply');

        Route::get('profile', 'Api\V1\ProfileController@profile');
        Route::post('profile/image/upload', 'Api\V1\ProfileController@profileImageUpload');
        Route::post('profile/information/update', 'Api\V1\ProfileController@profileInfoUpdate');
        Route::post('profile/password/update', 'Api\V1\ProfileController@profilePassUpdate');
        Route::post('profile/identity-verification/submit', 'Api\V1\ProfileController@profileIdentityVerificationSubmit');
        Route::post('profile/address-verification/submit', 'Api\V1\ProfileController@profileAddressVerificationSubmit');

        Route::get('2FA-security', 'Api\V1\TwoFASecurityController@twoFASecurity');
        Route::post('2FA-security/enable', 'Api\V1\TwoFASecurityController@twoFASecurityEnable');
        Route::post('2FA-security/disable', 'Api\V1\TwoFASecurityController@twoFASecurityDisable');

        Route::get('payout', 'Api\V1\PayoutController@payout');
        Route::post('payout/get-bank/list', 'Api\V1\PayoutController@payoutGetBankList');
        Route::post('payout/get-bank/from', 'Api\V1\PayoutController@payoutGetBankFrom');
        Route::post('payout/paystack/submit/{trx_id}', 'Api\V1\PayoutController@payoutPaystackSubmit');
        Route::post('payout/flutterwave/submit/{trx_id}', 'Api\V1\PayoutController@payoutFlutterwaveSubmit');
        Route::post('payout/submit/confirm', 'Api\V1\PayoutController@payoutSubmit');


        Route::get('payment', 'Api\V1\PaymentController@paymentGateways');
        Route::post('manual/payment/submit', 'Api\V1\PaymentController@manualPaymentSubmit');

        Route::get('dashboard', 'Api\V1\HomeController@dashboard');
        Route::post('payment/done', 'Api\V1\PaymentController@paymentDone');
        Route::get('pusher/config', 'Api\V1\HomeController@pusherConfig');

        Route::post('card/payment', 'Api\V1\PaymentController@cardPayment');

        Route::post('other/payment', 'Api\V1\PaymentController@showOtherPayment');
    });

    Route::post('/twoFA-Verify', 'Api\V1\VerificationController@twoFAverify');
    Route::post('/mail-verify', 'Api\V1\VerificationController@mailVerify');
    Route::post('/sms-verify', 'Api\V1\VerificationController@smsVerify');
    Route::get('/resend-code', 'Api\V1\VerificationController@resendCode');
});
