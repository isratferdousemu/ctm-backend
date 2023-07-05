<?php

namespace App\Http\Services\Merchant;

use App\Exceptions\AuthBasicErrorException;
use App\Http\Services\Messaging\SmsService;
use App\Http\Traits\MerchantTrait;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\SmsTrait;
use App\Http\Traits\UserTrait;
use App\Jobs\MerchantEmapilVerifyJob;
use App\Models\Shop;
use App\Models\User;
use Cache;
use Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Response;

class MerchantAuthService
{
    use UserTrait, MerchantTrait, SmsTrait,DispatchesJobs, MessageTrait,AuthenticatesUsers;

    protected function sendExpiredCodeResponse()
    {
        throw new AuthBasicErrorException(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $this->authExpiredCodeTextErrorCode,
            $this->authExpiredCodeMessage
        );
    }

    protected function sendInvalidCodeResponse()
    {
        throw new AuthBasicErrorException(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $this->authInvalidCodeTextErrorCode,
            $this->authInvalidCodeMessage
        );
    }

    public function validateUserIdConfirmRequest(Request $request)
    {
        $request->validate(User::$MerchantVerificationRules);
    }
    public function merchantLoginRules(Request $request)
    {
        $request->validate(User::$merchantLoginRules);
    }
    public function validateMerchantPasswordRequest(Request $request)
    {
        $request->validate(User::$MerchantPasswordRules);
    }
    public function validateMerchantDetailsRequest(Request $request)
    {
        $request->validate(User::$merchantDetailsRules);
    }
    public function validateMerchantPaymentRequest(Request $request)
    {
        $request->validate(User::$merchantPaymentDetailsRules);
    }

    public function UserIdType(Request $request)
    {
        $login = $request->input('userid');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        return $field;
    }

    // verify user email or phone number by otp code
    public function verifyUserId(Request $request,User $merchant){

        $code = $request->code;
        $userType = $this->UserIdType($request);

        // get cached code
        if ($userType == 'email') {
            $cachedCode = Cache::get($this->merchantEmailVerificationPrefix . $merchant->id);
        } else if($userType == 'phone') {
            $cachedCode = Cache::get($this->merchantPhoneOtpPrefix . $merchant->id);
        }

        // check code is valid or not
        if (!$cachedCode) {
            return   $this->sendExpiredCodeResponse();
        }
        // check code is valid or not
        if ($code != $cachedCode) {
            return $this->sendInvalidCodeResponse();
        }
        // if code is valid then update user status

        if ($userType == 'email') {
            $merchant->email_verified_at = now()->toDateTimeString();
        } else if($userType == 'phone') {
            $merchant->phone_verified_at = now()->toDateTimeString();
        }
        $merchant->merchent_step_1 = $this->MerchantStepVerifyCompleteStatus;

        $merchant->save();

        // forget cached code
        Cache::forget($this->merchantEmailVerificationPrefix . $merchant->id);
        Cache::forget($this->merchantPhoneOtpPrefix . $merchant->id);




    }

    public function validateMerchantUserId(Request $request){
        $login = $request->input('userid');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
  $request->merge([$field => $login]);
  $user = User::where($field, $request->userid)->first();
  if($user){
      if($user->user_type == $this->MerchantUserType){
                // check user step 1 is completed or not if not completed then send otp
                if($user->merchent_step_1 == $this->MerchantStepVerifyUncompletedStatus){
                    $this->MerchantSignupOtpSend($user,$field);
                }
                // check user step 2 is completed or not
                if($user->merchent_step_2 == $this->MerchantStepVerifyUncompletedStatus){

                }
                // check user step 3 is completed or not
                if($user->merchent_step_3 == $this->MerchantStepVerifyUncompletedStatus){

                }
                // check user step 4 is completed or not
                if($user->merchent_step_4 == $this->MerchantStepVerifyUncompletedStatus){

                }
            }else{
                if($field=='email'){
            $request->validate(
                [
                    'email'      => 'required|email|unique:users,email'
                ],
                [
                    'email.exists'     => 'This email already exists in our database record!',
                ]
            );
        }else if($field=='phone'){
            $request->validate(
                [
                    'phone'      => 'required|unique:users,phone',
                ],
                [
                    'phone.exists'     => 'This number already exists in our database record!',
                ]
            );

        }
            }


  }else{
            return $this->sendMerchantRegisterUser($field,$login);
  }

    }

    public function sendMerchantRegisterUser($type,$user){

        try {

            $merchant = new User;
            $merchant->user_type = $this->MerchantUserType;
            $merchant->merchant_id = $this->generateMerchantId();
            if($type=="email"){
                $merchant->email = $user;
            }
            if($type=="phone"){
                $merchant->phone = $user;

            }
            $merchant->save();

            $this->MerchantSignupOtpSend($merchant,$type);
            return 'OTP send Successfully';
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function MerchantSignupOtpSend($merchant,$type){
        $GlobalSettings = '';
        if($type=="email"){
            $code = $this->generateOtpCode($merchant, 10,$this->merchantEmailVerificationPrefix); //10 munite validity
            $message = str_replace('{OTP}', $code, $this->MerchantMailRegOtpTemplate);
            $this->dispatch(new MerchantEmapilVerifyJob($merchant->email,$message,$GlobalSettings));
        }
        if($type=="phone"){
            $code = $this->generateOtpCode($merchant, 10,$this->merchantPhoneOtpPrefix); //10 munite validity
            $message = str_replace('{OTP}', $code, $this->MerchantRegOtpTemplate);
            $smsService = new SmsService;
            $smsService->sendSMS($merchant->phone, $message);
                    return 'OTP send Successfully';
                }
    }

    public function generateMerchantId(){
        $id = $this->MerPrefix . rand(10000, 99999);
        $check=User::whereMerchantId($id)->first();
        if(!$check){
            return $id;
        }
        return $this->MerPrefix . rand(10000, 99999);
    }

    public function generateOtpCode($user, $time, $prefix)
    {
        //forget existing otp from cache
        Cache::forget($prefix . $user->id);
        //generate code
        $code =  mt_rand(100000, 999999);
        //put them in cache
        Cache::put($prefix . $user->id, $code, now()->addMinutes($time));
        //return generated code
        return $code;
    }

    // merchant password setup
    public function MerchantPasswordSetup(Request $request,User $merchant){
        DB::beginTransaction();
        try {
            $merchant->password = Hash::make($request->password);
        $merchant->merchent_step_2 = $this->MerchantStepVerifyCompleteStatus;
        $merchant->save();
        DB::commit();
        return $merchant;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

    }

    // merchant details setup
    public function MerchantDetailsSetup(Request $request,User $merchant){
        DB::beginTransaction();

    try {
        $merchant->full_name = $request->full_name;

            $merchant->merchent_step_3 = $this->MerchantStepVerifyCompleteStatus;
            $merchant->save();

            // store shop
            $this->MerchantShopStore($request,$merchant);
            DB::commit();
            return $merchant;
    } catch (\Throwable $th) {
        DB::rollback();
        throw $th;
    }

    }

    // merchant shop store
    public function MerchantShopStore(Request $request,User $merchant){
        DB::beginTransaction();

        try {

            $shop = new Shop;
            $shop->user_id = $merchant->id;
            $shop->link = $request->link;
            $shop->shop_name = $request->shop_name;
            $shop->pickup_address = $request->pickup_address;
            $shop->product_category_id = $request->product_category_id;
            $shop->pickup_phone = $request->pickup_phone;
            $shop->pickup_address = $request->pickup_address;
            $shop->area_id = $request->area_id;
            $shop->save();

            DB::commit();

            return $shop;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

    }

    // merchant payment setup
    public function MerchantPaymentSetup(Request $request,User $merchant){
        DB::beginTransaction();

        try {
            $this->MerchantPaymentStore($request,$merchant);

            $merchant->merchent_step_4 = $this->MerchantStepVerifyCompleteStatus;
            $merchant->save();

            // store payment
            DB::commit();
            return $merchant;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

    }

    // merchant payment store
    public function MerchantPaymentStore(Request $request,User $merchant){
        DB::beginTransaction();

        try {
            $merchant->payment_type = $request->payment_type;
            $merchant->wallet_type = $request->wallet_type;
            $merchant->wallet_number = $request->wallet_number;
            $merchant->bank_id = $request->bank_id;
            $merchant->bank_branch_name = $request->bank_branch_name;
            $merchant->bank_account_type = $request->bank_account_type;
            $merchant->bank_routing_num = $request->bank_routing_num;
            $merchant->bank_account_holder_name = $request->bank_account_holder_name;
            $merchant->bank_account_num = $request->bank_account_num;
            $merchant->save();

            DB::commit();

            return $merchant;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

    }

    // merchant login with email or phone
    public function MerchantLogin(Request $request){
        $this->merchantLoginRules($request);

        $user = User::where('email', $request->userid)->orWhere('phone', $request->userid)->first();

        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if($user){
            if (Hash::check($request->password, $user->password)) {
                if($user->user_type == $this->MerchantUserType){
                    if($user->merchent_step_1 == $this->MerchantStepVerifyCompleteStatus && $user->merchent_step_2 == $this->MerchantStepVerifyCompleteStatus && $user->merchent_step_3 == $this->MerchantStepVerifyCompleteStatus && $user->merchent_step_4 == $this->MerchantStepVerifyCompleteStatus){
                        $token = $user->createToken($this->generateTokenKey($request) . $user->id)->plainTextToken;
                        return [
                            'user'      => $user,
                            'token'     => $token,
                        ];
                    }else{
                        return response()->json(['error' => 'Please complete your registration'], 401);
                    }
                }else{
                    return response()->json(['error' => 'Invalid User'], 401);
                }
            }else{
                return response()->json(['error' => 'Invalid Password'], 401);
            }
        }else{
            return response()->json(['error' => 'Invalid User'], 401);
        }
    }

    public function generateTokenKey(Request $request)
    {
        $key = $this->accessTokenSpaDevice;
        if ($request->filled('device')) {
            $key = $request->device;
        }
        return $key;
    }

}
