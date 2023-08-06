<?php

namespace App\Http\Services\Auth;

use App\Exceptions\AuthBasicErrorException;
use App\Helpers\Helper;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\RoleTrait;
use App\Http\Traits\UserTrait;
use App\Models\Device;
use App\Models\User;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class AuthService
{
    use RoleTrait, AuthenticatesUsers, UserTrait, MessageTrait;

    protected $maxAttempts = 5;
    protected $decayMinutes = 1;
    protected $warning = 3;
    protected function sendNonAllowedAdminResponse()
    {
        throw new AuthBasicErrorException(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $this->NonAllowedAdminTextErrorCode,
            $this->NonAllowedAdminErrorResponse,
        );
    }
    protected function sendBannedLoginResponse()
    {
        throw new AuthBasicErrorException(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $this->bannedUserTextErrorCode,
            $this->bannedUserErrorResponse,
        );
    }

    protected function sendUnVerifiedLoginResponse(Request $request)
    {
        throw new AuthBasicErrorException(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $this->authUnverifiedUserTextErrorCode,
            $this->unverifiedUserErrorResponse,
        );

        // throw ValidationException::withMessages([
        //     'message' => $this->unverifiedUserErrorResponse,
        // ]);
    }

    public function generateTokenKey(Request $request)
    {
        $key = $this->accessTokenSpaDevice;
        if ($request->filled('device')) {
            $key = $request->device;
        }
        return $key;
    }

    public function validateLogin(Request $request)
    {

        $request->validate(
            [
                'email'      => 'required|email|exists:users,email',
                'password'              => 'required|string|min:6',
            ],
            [
                'email.exists'     => 'This email does not match our database record!',
            ]
        );
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw new AuthBasicErrorException(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $this->authWrongCredentialTextErrorCode,
            trans('auth.failed')
        );
        // throw ValidationException::withMessages([
        //     'message' => [trans('auth.failed')],
        // ]);
    }


    protected function verifyBeforeLogin(Request $request, User $user)
    {

        if ($user->status == $this->userAccountDeactivate) return $this->authDeactivateUserErrorCode;
        if ($user->status == $this->userAccountBanned) return $this->authBannedUserErrorCode;
        if (!$user->email_verified_at) return $this->authUnverifiedUserErrorCode;
        if ($user->user_type) {
            if ($user->user_type == $this->superAdminUserType || $user->user_type == $this->staffType) {
                if (Hash::check($request->password, $user->password)) {
                    return $this->authSuccessCode;
                }
            } else {
                return $this->nonAllowedUserErrorCode;
            }
        }
        if (Hash::check($request->password, $user->password)) {
            return $this->authSuccessCode;
        }

        return $this->authBasicErrorCode;
    }

    protected function bannedUser($user){
        $user->status= $this->userAccountBanned;
        $user->save();
        activity("Automation")
         ->causedBy(auth()->user())
         ->performedOn($user)
         ->log('User Blocked For Attempt Many time!!');
    }

    public function Adminlogin(Request $request,$type=1)
    {

        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);
            $user = User::where("email", $request->email)->first();
            $this->bannedUser($user);
            return $this->sendLockoutResponse($request);
        }
        $user = User::where("email", $request->email)->first();

        if ($user == null) {
            $this->incrementLoginAttempts($request);
            return $this->sendFailedLoginResponse($request);
        }

        if ($authCode = $this->verifyBeforeLogin($request, $user)) {

            if ($authCode == $this->nonAllowedUserErrorCode) return $this->sendNonAllowedAdminResponse();

            if ($authCode == $this->authBannedUserErrorCode) return $this->sendBannedLoginResponse();

            if ($authCode == $this->authUnverifiedUserErrorCode) return $this->sendUnVerifiedLoginResponse($request);

            if ($authCode == $this->authBasicErrorCode) {

                $this->incrementLoginAttempts($request);
                return $this->sendFailedLoginResponse($request);
            }
            if ($authCode == $this->authSuccessCode) {
                $this->clearLoginAttempts($request);
                if($type==1){
                    return $otp = $this->sendLoginOtp($user);
                }
                if($type==2){
                    // check OTP
                    $code = $request->otp;
                    $cachedCode = Cache::get($this->userLoginOtpPrefix . $user->id);
                    if (!$cachedCode || $code != $cachedCode) {
                        throw new AuthBasicErrorException(
                            Response::HTTP_UNPROCESSABLE_ENTITY,
                            'verify_failed',
                            "Verification code invalid !",
                        );
                    }
                // check device registration
                $device = Device::whereId($user->id)->whereDeviceId($request->device_token)->whereIpAddress($request->ip())->first();
                if(!$device){
                    throw new AuthBasicErrorException(
                        Response::HTTP_UNPROCESSABLE_ENTITY,
                        'device_not_found',
                        "your device is not registered",
                    );
                }

                    //logging in
                    $token = $user->createToken($this->generateTokenKey($request) . $user->id)->plainTextToken;
                    return [
                        'user'      => $user->load('roles'),
                        'token'     => $token,
                    ];
                }

            }
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    public function generateOtpCode($user, $time)
    {
        Cache::forget($this->userLoginOtpPrefix . $user->id);
        //generate code
        $code =  mt_rand(100000, 999999);
        //put them in cache
        Cache::put($this->userLoginOtpPrefix . $user->id, $code, now()->addMinutes($time));
        //return generated code
        return $code;
    }
    protected function sendLoginOtp($user){

        return $code = $this->generateOtpCode($user, 1);
    }

    public function logout(Request $request)
    {

        DB::beginTransaction();
        try {
            $user = User::findOrFail(Auth::user()->id);

            if ($request->filled('device') && !empty($request->device)) {
                $user->tokens()->where('name', $this->generateTokenKey($request) . $user->id)->delete();
            } else {
                Auth::user()->tokens->each(function ($token, $key) {
                    $token->delete();
                });
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
