<?php

namespace App\Http\Services\Admin\Employee;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepartmentService
{
    public function createEmpDep(Request $request,$type){
        DB::beginTransaction();
        try {

            $Department                       = new Department;
            $Department->name                = $request->name;
            $Department->description            = $request->description;
            $Department->type                = $type;
            $Department->save();
            DB::commit();
            return $Department;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateEmpDep(Request $request, Department $Department){
        DB::beginTransaction();
        try {
            $Department->name                = $request->name;
            $Department->description            = $request->description;
            $Department->save();
            DB::commit();
            return $Department;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
