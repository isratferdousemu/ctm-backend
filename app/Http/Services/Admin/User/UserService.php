<?php

namespace App\Http\Services\Admin\User;

use App\Http\Traits\RoleTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserService
{
    use RoleTrait;
    public function createUser(Request $request,$password){
        DB::beginTransaction();
        try {

            $user                       = new User();
            $user->full_name = $request->full_name;
            $user->username = $request->username;
            $user->mobile = $request->mobile;
            $user->email = $request->email;
            $user->status = $request->status;
            // check request has division_id, district_id, thana_id, city_corpo_id
            if($request->has('division_id')){
                $user->division_id = $request->division_id;
            }
            if($request->has('district_id')){
                $user->district_id = $request->district_id;
            }
            if($request->has('thana_id')){
                $user->thana_id = $request->thana_id;
            }
            if($request->has('city_corpo_id')){
                $user->city_corpo_id = $request->city_corpo_id;
            }
            if($request->has('office_type') ){
                $user->office_type = $request->office_type;
            }
            $user->office_id = $request->office_id;
            $user->user_type = $this->staffId;
            $user->password = bcrypt($password);
            $user->email_verified_at = now();
            $user->save();

            // assign role to the user

            $user->assignRole([$request->role_id]);

            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
