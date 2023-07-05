<?php

namespace App\Http\Services\Auth;

use App\Exceptions\AuthBasicErrorException;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\RoleTrait;
use App\Http\Traits\UserTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class AuthService
{
    use RoleTrait,AuthenticatesUsers,UserTrait,MessageTrait;


    protected function sendNonAllowedAdminResponse()
    {
        throw new AuthBasicErrorException(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $this->NonAllowedAdminTextErrorCode,
            $this->NonAllowedAdminErrorResponse,
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
        $login = $request->input('user');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
  $request->merge([$field => $login]);
if($field=='email'){
    $request->validate(
        [
            'email'      => 'required|email|exists:users,email',
            'password'              => 'required|string|min:6',
        ],
        [
            'email.exists'     => 'This email does not match our database record!',
        ]
    );
}else if($field=='phone'){

    $request->validate(
        [
            'phone'      => 'required|exists:users',
            'password'              => 'required|string|min:6',
        ],
        [
            'phone.exists'     => 'This number does not match our database record!',
        ]
    );

}

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
        if (!$user->email_verified_at) return $this->authUnverifiedUserErrorCode;
        if (Hash::check($request->password, $user->password)) {
            return $this->authSuccessCode;
        }

        if ($user->user_type) {
            if($user->user_type==$this->superAdminUserType){
                if (Hash::check($request->password, $user->password)) {
                    return $this->authSuccessCode;
                }
            }else{
                return $this->nonAllowedUserErrorCode;
            }
        }

        return $this->authBasicErrorCode;
    }

    public function Adminlogin(Request $request)
    {

        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        $login = $request->input('user');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
  $request->merge([$field => $login]);
        $user = User::where($field, $request->user)->first();

        if ($user == null) {
            $this->incrementLoginAttempts($request);
            return $this->sendFailedLoginResponse($request);
        }

        if ($authCode = $this->verifyBeforeLogin($request, $user)) {

            if ($authCode == $this->nonAllowedUserErrorCode) return $this->sendNonAllowedAdminResponse();

            if ($authCode == $this->authUnverifiedUserErrorCode) return $this->sendUnVerifiedLoginResponse($request);

            if ($authCode == $this->authBasicErrorCode) {
                $this->incrementLoginAttempts($request);
                return $this->sendFailedLoginResponse($request);
            }
            if ($authCode == $this->authSuccessCode) {
                $this->clearLoginAttempts($request);
                //logging in
                $token = $user->createToken($this->generateTokenKey($request) . $user->id)->plainTextToken;
                return [
                    'user'      => $user->load('roles'),
                    'token'     => $token,
                ];
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }


}
