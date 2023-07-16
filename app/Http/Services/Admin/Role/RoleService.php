<?php

namespace App\Http\Services\Admin\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function createRole(Request $request){
        DB::beginTransaction();
        try {
            // store role
            $role= new Role;
            $role->guard_name="sanctum";
            $role->name=$request->name_en;
            $role->name_en=$request->name_en;
            $role->name_bn=$request->name_bn;
            $role->code=$request->code;
            $role->status=$request->status;
            $role->save();
            db::commit();
            return $role;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateRole(Request $request){
        DB::beginTransaction();
        try {
            // update role
            $role= Role::find($request->id);
            $role->name=$request->name_en;
            $role->name_en=$request->name_en;
            $role->name_bn=$request->name_bn;
            $role->code=$request->code;
            $role->status=$request->status;
            $role->save();
            db::commit();
            return $role;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /* -------------------------------------------------------------------------- */
    /*                             Permission Services                            */
    /* -------------------------------------------------------------------------- */


    public function AssignPermissionToRole(Request $request){
        DB::beginTransaction();
        try {
            $role= Role::find($request->role_id);
            // assign permissions
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
            db::commit();
            return $role;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
