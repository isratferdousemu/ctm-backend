<?php

namespace App\Http\Services\Notification;

use App\Models\PushNotificationDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PushNotificationService
{
    public function SaveFcmToken(Request $request,$user){
        DB::beginTransaction();
        try{
            if(Str::contains($_SERVER["HTTP_USER_AGENT"], 'Mobile')==1 || Str::contains($_SERVER["HTTP_USER_AGENT"], 'Dart')==1 ){
                $dev = 'Mobile';
            }else{
                $dev = 'web';
            }
            $checkExiest=PushNotificationDevice::where([
                ['device_type', $_SERVER["HTTP_USER_AGENT"]],
                ['device_key', $request->token],
                ['user_id', $user->id]
                ])->first();
                if(!$checkExiest){
                    PushNotificationDevice::create([
                        'user_id' =>$user->id,
                        'device_key' =>$request->token,
                        'device_type' =>$_SERVER["HTTP_USER_AGENT"],
                        'ip_address' =>$_SERVER["REMOTE_ADDR"],
                        'device' =>$dev
                    ]);
                }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
