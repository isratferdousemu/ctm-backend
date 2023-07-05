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
        DB::beginTransaction();;
        try {
            // store role
            $role= new Role;
            $role->guard_name="sanctum";
            $role->name=$request->name;
            $role->save();
            // assing permissions
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
