<?php

namespace App\Http\Traits;

use App\Mail\SendMail;
use App\Models\Admin;
use App\Models\EmailTemplate;
use App\Models\NotifyTemplate;
use App\Models\SiteNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use  Facades\App\Services\BasicCurl;

trait Notify
{

    public function sendMailSms($user, $templateKey, $params = [], $subject = null, $requestMessage = null)
    {
        $this->mail($user, $templateKey, $params, $subject, $requestMessage);
        $this->sms($user, $templateKey, $params, $requestMessage = null);
    }

    public function mailToAdmin($templateKey = null, $params = [], $subject = null, $requestMessage = null)
    {
        $basic = basicControl();

        if ($basic->email_notification != 1) {
            return false;
        }
        $email_body = $basic->email_description;

        $templateObj = EmailTemplate::where('template_key', $templateKey)->where('mail_status', 1)->first();


        try {

            $admins = Admin::all();
            foreach ($admins as $user) {

                $message = str_replace("[[name]]", $user->username, $email_body);

                if (!$templateObj && $subject == null) {
                    return false;
                } else {

                    if ($templateObj) {
                        $message = str_replace("[[message]]", $templateObj->template, $message);
                        if (empty($message)) {
                            $message = $email_body;
                        }
                        foreach ($params as $code => $value) {
                            $message = str_replace('[[' . $code . ']]', $value, $message);
                        }
                    } else {
                        $message = str_replace("[[message]]", $requestMessage, $message);
                    }

                    $subject = ($subject == null) ? $templateObj->subject : $subject;
                    $email_from = ($templateObj) ? $templateObj->email_from : $basic->sender_email;

                    @Mail::to($user)->queue(new SendMail($email_from, $subject, $message));
                }
            }
        } catch (\Exception $exception) {

        }
    }


    public function mail($user, $templateKey = null, $params = [], $subject = null, $requestMessage = null)
    {
        try {
            $basic = basicControl();

            if ($basic->email_notification != 1) {
                return false;
            }
            $email_body = $basic->email_description;
            $templateObj = EmailTemplate::where('template_key', $templateKey)->where('language_id', $user->language_id)->where('mail_status', 1)->first();
            if (!$templateObj) {
                $templateObj = EmailTemplate::where('template_key', $templateKey)->where('mail_status', 1)->first();
            }
            $message = str_replace("[[name]]", $user->username, $email_body);

            if (!$templateObj && $subject == null) {
                return false;
            } else {

                if ($templateObj) {
                    $message = str_replace("[[message]]", $templateObj->template, $message);
                    if (empty($message)) {
                        $message = $email_body;
                    }
                    foreach ($params as $code => $value) {
                        $message = str_replace('[[' . $code . ']]', $value, $message);
                    }
                } else {
                    $message = str_replace("[[message]]", $requestMessage, $message);
                }

                $subject = ($subject == null) ? $templateObj->subject : $subject;
                $email_from = ($templateObj) ? $templateObj->email_from : $basic->sender_email;

                @Mail::to($user)->queue(new SendMail($email_from, $subject, $message));
            }
        } catch (\Exception $exception) {

        }


    }

    public function mailVerification($user, $templateKey = null, $params = [], $subject = null, $requestMessage = null)
    {
        $basic = basicControl();

        if ($basic->email_verification != 1) {
            return false;
        }
        $email_body = $basic->email_description;
        $templateObj = EmailTemplate::where('template_key', $templateKey)->where('language_id', $user->language_id)->where('mail_status', 1)->first();
        if (!$templateObj) {
            $templateObj = EmailTemplate::where('template_key', $templateKey)->where('mail_status', 1)->first();
        }
        $message = str_replace("[[name]]", $user->username, $email_body);

        if (!$templateObj && $subject == null) {
            return false;
        } else {

            if ($templateObj) {
                $message = str_replace("[[message]]", $templateObj->template, $message);
                if (empty($message)) {
                    $message = $email_body;
                }
                foreach ($params as $code => $value) {
                    $message = str_replace('[[' . $code . ']]', $value, $message);
                }
            } else {
                $message = str_replace("[[message]]", $requestMessage, $message);
            }

            $subject = ($subject == null) ? $templateObj->subject : $subject;
            $email_from = ($templateObj) ? $templateObj->email_from : $basic->sender_email;

            @Mail::to($user)->send(new SendMail($email_from, $subject, $message));
        }


    }


    public function sms($user, $templateKey, $params = [], $requestMessage = null)
    {

        try {

            $basic = basicControl();
            if ($basic->sms_notification != 1) {
                return false;
            }

            $smsControl = smsConfig();

            $templateObj = EmailTemplate::where('template_key', $templateKey)->where('language_id', $user->language_id)->where('sms_status', 1)->first();
            if (!$templateObj) {
                $templateObj = EmailTemplate::where('template_key', $templateKey)->where('sms_status', 1)->first();
            }

            if (!$templateObj && $requestMessage == null) {
                return false;
            } else {
                if ($templateObj) {
                    $template = $templateObj->sms_body;
                    foreach ($params as $code => $value) {
                        $template = str_replace('[[' . $code . ']]', $value, $template);
                    }
                } else {
                    $template = $requestMessage;
                }
            }


            $paramData = is_null($smsControl->paramData) ? [] : json_decode($smsControl->paramData, true);
            $paramData = http_build_query($paramData);
            $actionUrl = $smsControl->actionUrl;
            $actionMethod = $smsControl->actionMethod;
            $formData = is_null($smsControl->formData) ? [] : json_decode($smsControl->formData, true);

            $headerData = is_null($smsControl->headerData) ? [] : json_decode($smsControl->headerData, true);
            if ($actionMethod == 'GET') {
                $actionUrl = $actionUrl . '?' . $paramData;
            }

            $formData = recursive_array_replace("[[receiver]]", $user->phone, recursive_array_replace("[[message]]", $template, $formData));
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $actionUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $actionMethod,
                CURLOPT_POSTFIELDS => http_build_query($formData),
                CURLOPT_HTTPHEADER => $headerData,
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } catch (\Exception $exception) {

        }

    }


    public function smsVerification($user, $templateKey, $params = [], $requestMessage = null)
    {
        try {
            $basic =  basicControl();
            if ($basic->sms_verification != 1) {
                return false;
            }
            $smsControl = smsConfig();

            $templateObj = EmailTemplate::where('template_key', $templateKey)->where('language_id', $user->language_id)->where('sms_status', 1)->first();
            if (!$templateObj) {
                $templateObj = EmailTemplate::where('template_key', $templateKey)->where('sms_status', 1)->first();
            }
            if (!$templateObj && $requestMessage == null) {
                return false;
            } else {
                if ($templateObj) {
                    $template = $templateObj->sms_body;
                    foreach ($params as $code => $value) {
                        $template = str_replace('[[' . $code . ']]', $value, $template);
                    }
                } else {
                    $template = $requestMessage;
                }
            }


            $paramData = is_null($smsControl->paramData) ? [] : json_decode($smsControl->paramData, true);
            $paramData = http_build_query($paramData);
            $actionUrl = $smsControl->actionUrl;
            $actionMethod = $smsControl->actionMethod;
            $formData = is_null($smsControl->formData) ? [] : json_decode($smsControl->formData, true);

            $headerData = is_null($smsControl->headerData) ? [] : json_decode($smsControl->headerData, true);
            if ($actionMethod == 'GET') {
                $actionUrl = $actionUrl . '?' . $paramData;
            }

            $formData = recursive_array_replace("[[receiver]]", $user->phone, recursive_array_replace("[[message]]", $template, $formData));
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $actionUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $actionMethod,
                CURLOPT_POSTFIELDS => http_build_query($formData),
                CURLOPT_HTTPHEADER => $headerData,
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } catch (\Exception $exception) {

        }

    }


    public function userPushNotification($user, $templateKey, $params = [], $action = [])
    {
        $basic =  basicControl();
        if ($basic->push_notification != 1) {
            return false;
        }

        $templateObj = NotifyTemplate::where('template_key', $templateKey)->where('language_id', $user->language_id)->where('status', 1)->first();
        if (!$templateObj) {
            $templateObj = NotifyTemplate::where('template_key', $templateKey)->where('status', 1)->first();
        }
        if ($templateObj) {
            $template = $templateObj->body;
            foreach ($params as $code => $value) {
                $template = str_replace('[[' . $code . ']]', $value, $template);
            }
            $action['text'] = $template;
        }
        $siteNotification = new SiteNotification();
        $siteNotification->description = $action;
        $user->siteNotificational()->save($siteNotification);

        try {
            event(new \App\Events\UserNotification($siteNotification, $user->id));
        } catch (\Exception $e) {

        }
    }


    public function adminPushNotification($templateKey, $params = [], $action = [])
    {

        $basic = basicControl();
        if ($basic->push_notification != 1) {
            return false;
        }

        $templateObj = NotifyTemplate::where('template_key', $templateKey)->where('status', 1)->first();
        if (!$templateObj) {
            return false;
        }

        if ($templateObj) {
            $template = $templateObj->body;
            foreach ($params as $code => $value) {
                $template = str_replace('[[' . $code . ']]', $value, $template);
            }
            $action['text'] = $template;
        }

        $admins = Admin::all();
        foreach ($admins as $admin) {
            $siteNotification = new SiteNotification();
            $siteNotification->description = $action;
            $admin->siteNotificational()->save($siteNotification);
            try {
                event(new \App\Events\AdminNotification($siteNotification, $admin->id));
            } catch (\Exception $e) {

            }

        }
    }


    public function adminFirebasePushNotification($templateKey, $params = [], $action = null)
    {
        $notify = fireBaseControl();
        if (!$notify) {
            return false;
        }

        $templateObj = NotifyTemplate::where('template_key', $templateKey)->where('firebase_notify_status', 1)->first();
        if (!$templateObj) {
            return false;
        }

        $template = '';
        if ($templateObj) {
            $template = $templateObj->body;
            foreach ($params as $code => $value) {
                $template = str_replace('[[' . $code . ']]', $value, $template);
            }
        }

        $admins = Admin::all();
        foreach ($admins as $admin) {
            $data = [
                "to" => $admin->fcm_token,
                "notification" => [
                    "title" => $templateObj->name,
                    "body" => $template,
                    "icon" => config('location.logoIcon.path') . 'favicon.png',
                    "data" => [
                        "foreground" => (int)$notify->admin_foreground,
                        "background" => (int)$notify->admin_background,
                        "click_action" => $action
                    ],
                    "content_available" => true,
                    "mutable_content" => true
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'key=' . $notify->server_key
            ])
                ->acceptJson()
                ->post('https://fcm.googleapis.com/fcm/send', $data);
        }
    }

    public function userFirebasePushNotification($user, $templateKey, $params = [], $action = null)
    {
        $notify = fireBaseControl();
        if (!$notify) {
            return false;
        }
        if ($notify->user_foreground == 0 && $notify->user_background == 0) {
            return false;
        }

        try {
            $templateObj = NotifyTemplate::where('template_key', $templateKey)->where('language_id', $user->language_id)->where('firebase_notify_status', 1)->where('status', 1)->first();
            if (!$templateObj) {
                $templateObj = NotifyTemplate::where('template_key', $templateKey)->where('firebase_notify_status', 1)->first();
            }
            $template = '';
            if ($templateObj) {
                $template = $templateObj->body;
                foreach ($params as $code => $value) {
                    $template = str_replace('[[' . $code . ']]', $value, $template);
                }
            }

            $data = [
                "to" => $user->fcm_token,
                "notification" => [
                    "title" => $templateObj->name . ' from ' . config('basic.site_title'),
                    "body" => $template,
                    "icon" => getFile(config('location.logoIcon.path') . 'favicon.png'),
                ],
                "data" => [
                    "foreground" => (int)$notify->user_foreground,
                    "background" => (int)$notify->user_background,
                    "click_action" => $action
                ],
                "content_available" => true,
                "mutable_content" => true
            ];

            $response = Http::withHeaders([
                'Authorization' => 'key=' . $notify->server_key
            ])
                ->acceptJson()
                ->post('https://fcm.googleapis.com/fcm/send', $data);
        }catch (\Exception $e){

        }
    }
}
