<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiValidation;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\IdentifyForm;
use App\Models\KYC;
use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Stevebauman\Purify\Facades\Purify;

class ProfileController extends Controller
{
    use ApiValidation, Notify, Upload;

    public function profile(Request $request)
    {
        try {
            $user = auth()->user();
            $data['userImage'] = getFile(config('location.user.path') . $user->image);
            $data['username'] = trans(ucfirst($user->username));
            $data['userLevel'] = optional($user->rank)->rank_lavel ?? null;
            $data['userRankName'] = optional($user->rank)->rank_name ?? null;
            $data['userJoinDate'] = $user->created_at->format('d M, Y g:i A') ?? null;
            $data['userFirstName'] = $user->firstname ?? null;
            $data['userLastName'] = $user->lastname ?? null;
            $data['userUsername'] = $user->username ?? null;
            $data['userEmail'] = $user->email ?? null;
            $data['userPhone'] = $user->phone ?? null;
            $data['userLanguageId'] = $user->language_id ?? null;
            $data['userAddress'] = $user->address ?? null;

            if ($user->identity_verify == 1) {
                $data['userIdentityVerifyFromShow'] = false;
                $data['userIdentityVerifyMsg'] = 'Your KYC submission has been pending';
            } elseif ($user->identity_verify == 2) {
                $data['userIdentityVerifyFromShow'] = false;
                $data['userIdentityVerifyMsg'] = 'Your KYC already verified';
            } elseif ($user->identity_verify == 3) {
                $data['userIdentityVerifyFromShow'] = true;
                $data['userIdentityVerifyMsg'] = 'You previous request has been rejected';
            } else {
                $data['userIdentityVerifyFromShow'] = true;
                $data['userIdentityVerifyMsg'] = null;
            }


            if ($user->address_verify == 1) {
                $data['userAddressVerifyFromShow'] = false;
                $data['userAddressVerifyMsg'] = 'Your KYC submission has been pending';
            } elseif ($user->address_verify == 2) {
                $data['userAddressVerifyFromShow'] = false;
                $data['userAddressVerifyMsg'] = 'Your KYC already verified';
            } elseif ($user->address_verify == 3) {
                $data['userAddressVerifyFromShow'] = true;
                $data['userAddressVerifyMsg'] = 'You previous request has been rejected';
            } else {
                $data['userAddressVerifyFromShow'] = true;
                $data['userAddressVerifyMsg'] = null;
            }


            $data['languages'] = Language::all();
            $data['identityFormList'] = IdentifyForm::where('status', 1)->get();
            return response()->json($this->withSuccess($data));

        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function profileImageUpload(Request $request)
    {
        $allowedExtensions = array('jpg', 'png', 'jpeg');

        try {
            $image = $request->image;
            $this->validate($request, [
                'image' => [
                    'required',
                    'max:4096',
                    function ($fail) use ($image, $allowedExtensions) {
                        $ext = strtolower($image->getClientOriginalExtension());
                        if (($image->getSize() / 1000000) > 2) {
                            return response()->json($this->withErrors('Images MAX  2MB ALLOW!'));
                        }
                        if (!in_array($ext, $allowedExtensions)) {
                            return response()->json($this->withErrors('Only png, jpg, jpeg images are allowed'));
                        }
                    }
                ]
            ]);
            $user = auth()->user();
            if ($request->hasFile('image')) {
                $path = config('location.user.path');
                try {
                    $user->image = $this->uploadImage($image, $path);
                } catch (\Exception $exp) {
                    return response()->json($this->withErrors('Could not upload your ' . $image));
                }
            }
            $user->save();

            return response()->json($this->withSuccess('Updated Successfully.'));
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function profileInfoUpdate(Request $request)
    {
        try {
            $languages = Language::all()->map(function ($item) {
                return $item->id;
            });
            $user = auth()->user();
            $validateUser = Validator::make($request->all(),
                [
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'username' => "sometimes|required|alpha_dash|min:5|unique:users,username," . $user->id,
                    'address' => 'required',
                    'language_id' => Rule::in($languages),
                ]);

            if ($validateUser->fails()) {
                return response()->json($this->withErrors(collect($validateUser->errors())->collapse()[0]));
            }

            $user->language_id = $request['language_id'];
            $user->firstname = $request['firstname'];
            $user->lastname = $request['lastname'];
            $user->username = $request['username'];
            $user->address = $request['address'];
            $user->save();

            return response()->json($this->withSuccess('Updated Successfully.'));
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function profilePassUpdate(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'current_password' => "required",
                'password' => "required|min:5|confirmed",
            ]);

        if ($validateUser->fails()) {
            return response()->json($this->withErrors(collect($validateUser->errors())->collapse()[0]));
        }

        $user = auth()->user();
        try {
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = bcrypt($request->password);
                $user->save();

                return response()->json($this->withSuccess('Password Changes successfully.'));
            } else {
                return response()->json($this->withErrors('Current password did not match'));
            }
        } catch (\Exception $e) {
            return response()->json($this->withErrors($e->getMessage()));
        }
    }

    public function profileIdentityVerificationSubmit(Request $request)
    {
        $identityFormList = IdentifyForm::where('status', 1)->get();
        $rules['identity_type'] = ["required", Rule::in($identityFormList->pluck('slug')->toArray())];
        $identity_type = $request->identity_type;
        $identityForm = IdentifyForm::where('slug', trim($identity_type))->where('status', 1)->first();
        if (!$identityForm) {
            return response()->json($this->withErrors('Data not found'));
        }

        $params = $identityForm->services_form;

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

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($this->withErrors(collect($validator->errors())->collapse()[0]));
        }


        $path = config('location.kyc.path') . date('Y') . '/' . date('m') . '/' . date('d');
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
                                    return response()->json($this->withErrors('Could not upload your ' . $inKey));
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
        }

        try {

            DB::beginTransaction();

            $user = auth()->user();
            $kyc = new KYC();
            $kyc->user_id = $user->id;
            $kyc->kyc_type = $identityForm->slug;
            $kyc->details = $reqField;
            $kyc->save();

            $user->identity_verify = 1;
            $user->save();

            if (!$kyc) {
                DB::rollBack();
                return response()->json($this->withErrors('Failed to submit request'));
            }
            DB::commit();

            $msg = [
                'name' => $user->fullname,
            ];

            $adminAction = [
                "link" => route('admin.kyc.users.pending'),
                "icon" => "fas fa-user text-white"
            ];
            $userAction = [
                "link" => route('user.profile'),
                "icon" => "fas fa-user text-white"
            ];

            $this->adminPushNotification('ADMIN_NOTIFY_USER_KYC_REQUEST', $msg, $adminAction);
            $this->userPushNotification($user, 'USER_NOTIFY_HIS_KYC_REQUEST_SEND', $msg, $userAction);

            $currentDate = dateTime(Carbon::now());
            $this->sendMailSms($user, $type = 'USER_MAIL_HIS_KYC_REQUEST_SEND', [
                'name' => $user->fullname,
                'date' => $currentDate,
            ]);

            $this->mailToAdmin($type = 'ADMIN_MAIL_USER_KYC_REQUEST', [
                'name' => $user->fullname,
                'date' => $currentDate,
            ]);

            return response()->json($this->withSuccess('KYC request has been submitted.'));

        } catch (\Exception $e) {
            return response()->json($this->withSuccess($e->getMessage()));
        }
    }

    public function profileAddressVerificationSubmit(Request $request)
    {
        $rules = [];
        $rules['addressProof'] = ['image', 'mimes:jpeg,jpg,png', 'max:2048'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($this->withErrors($validator));
        }

        $path = config('location.kyc.path') . date('Y') . '/' . date('m') . '/' . date('d');

        $reqField = [];
        try {
            if ($request->hasFile('addressProof')) {
                $reqField['addressProof'] = [
                    'field_name' => $this->uploadImage($request['addressProof'], $path),
                    'type' => 'file',
                ];
            } else {
                return response()->json($this->withErrors('Please select a ' . 'address Proof'));
            }
        } catch (\Exception $exp) {
            return response()->json($this->withErrors('Could not upload your ' . 'address Proof'));
        }

        try {

            DB::beginTransaction();
            $user = auth()->user();
            $kyc = new KYC();
            $kyc->user_id = $user->id;
            $kyc->kyc_type = 'address-verification';
            $kyc->details = $reqField;
            $kyc->save();
            $user->address_verify = 1;
            $user->save();

            if (!$kyc) {
                DB::rollBack();
                return response()->json($this->withErrors("Failed to submit request"));
            }
            DB::commit();

            $msg = [
                'name' => $user->fullname,
            ];

            $adminAction = [
                "link" => route('admin.kyc.users.pending'),
                "icon" => "fas fa-user text-white"
            ];

            $this->adminPushNotification('ADMIN_NOTIFY_USER_ADDRESS_VERIFICATION_REQUEST', $msg, $adminAction);

            $currentDate = dateTime(Carbon::now());

            $this->mailToAdmin($type = 'ADMIN_MAIL_USER_ADDRESS_VERIFICATION_REQUEST', [
                'name' => $user->fullname,
                'date' => $currentDate,
            ]);
            return response()->json($this->withSuccess("Your request has been submitted."));

        } catch (\Exception $e) {
            return response()->json($this->withSuccess($e->getMessage()));
        }
    }

}
