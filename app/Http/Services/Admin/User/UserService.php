<?php

namespace App\Http\Services\Admin\User;

use App\Helpers\Helper;
use App\Http\Traits\RoleTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserService
{
    use RoleTrait;
    public function createUser(Request $request, $password)
    {
        DB::beginTransaction();
        // try {

            $user                       = new User();
            $user->full_name = $request->full_name;
            $user->username = $request->username;
            $user->mobile = $request->mobile;
            $user->email = $request->email;
            $user->status = $request->status;
            // check request has division_id, district_id, thana_id, city_corpo_id

            if ($request->has('office_type')) {
                $user->office_type = $request->office_type;
                if ($request->office_type != 4 || $request->office_type != 5) {
                    if ($request->office_type == 6) {
                        if ($request->has('division_id')) {
                            $user->assign_location_id = $request->division_id;
                        }
                    } elseif ($request->office_type == 7) {
                        if ($request->has('district_id')) {
                            $user->assign_location_id = $request->district_id;
                        }
                    } elseif ($request->office_type == 8 || $request->office_type == 10 || $request->office_type == 11) {
                        if ($request->has('thana_id')) {
                            $user->assign_location_id = $request->thana_id;
                        }
                    } elseif ($request->office_type == 9) {
                        if ($request->has('city_corpo_id')) {
                            $user->assign_location_id = $request->city_corpo_id;
                        }
                    }
                }
            }
            $user->office_id = $request->office_id;
            $user->user_type = $this->staffId;
            $user->salt = Helper::generateSalt();

            // password encryption with salt
            $user->password = bcrypt($user->salt . $password);

            $user->user_id = $this->generateUserId();
            $user->email_verified_at = now();
            $user->save();
            // assign role to the user
            $user->assignRole([$request->role_id]);

            DB::commit();
            return $user;
        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     throw $th;
        // }
    }

    public function upddateUser(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $user            = User::findOrFail($id);
            $user->full_name = $request->full_name;
            $user->username = $request->username;
            $user->mobile = $request->mobile;
            $user->email = $request->email;
            $user->status = $request->status;
            // check request has division_id, district_id, thana_id, city_corpo_id

            if($request->has('office_type')){
                $user->office_type = $request->office_type;
                if($request->office_type!=4 || $request->office_type!=5){
                    if($request->office_type==6){
                        if($request->has('division_id')){
                            $user->assign_location_id = $request->division_id;
                        }
                    }elseif ($request->office_type==7) {
                        if($request->has('district_id')){
                            $user->assign_location_id = $request->district_id;
                        }
                    }elseif ($request->office_type==8 || $request->office_type==10 || $request->office_type==11) {
                        if($request->has('thana_id')){
                            $user->assign_location_id = $request->thana_id;
                        }
                    }elseif ($request->office_type==9) {
                        if($request->has('city_corpo_id')){
                            $user->assign_location_id = $request->city_corpo_id;
                        }
                    }
                }
            }

            $user->office_id = $request->office_id;
            $user->save();
            // assign role to the user
            $user->syncRoles([$request->role_id]);

            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    // public function upddateUser(Request $request, $id)
    // {

    //     DB::beginTransaction();
    //     try {
    //         $user = User::findOrFail($id);

    //         // Update user attributes
    //         $user->fill([
    //             'full_name' => $request->full_name,
    //             'username' => $request->username,
    //             'mobile' => $request->mobile,
    //             'email' => $request->email,
    //             'status' => $request->status,
    //             'office_id' => $request->office_id,
    //         ]);

    //         // Handle office_type and assign_location_id based on conditions
    //         // if ($request->has('office_type')) {
    //         //     $user->office_type = $request->office_type;

    //         //     if ($request->office_type != 4 && $request->office_type != 5) {
    //         //         if ($request->office_type == 6 && $request->has('division_id')) {
    //         //             $user->assign_location_id = $request->division_id;
    //         //         } elseif ($request->office_type == 7 && $request->has('district_id')) {
    //         //             $user->assign_location_id = $request->district_id;
    //         //         } elseif (in_array($request->office_type, [8, 10, 11]) && $request->has('thana_id')) {
    //         //             $user->assign_location_id = $request->thana_id;
    //         //         } elseif ($request->office_type == 9 && $request->has('city_corpo_id')) {
    //         //             $user->assign_location_id = $request->city_corpo_id;
    //         //         }
    //         //     }
    //         // }

    //         // Save the updated user record
    //         $user->save();

    //         // Assign a role to the user
    //         $user->syncRoles([$request->role_id]);

    //         DB::commit();

    //         return $user;
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         throw $th;
    //     }
    // }

    public function generateUserId()
    {
        $user_id = User::count() + 1;
        $user = User::where('user_id', $user_id)->first();
        if ($user) {
            $this->generateUserId();
        }
        return $user_id;
    }
}
