<?php

namespace App\Http\Services\Admin\Systemconfig;

use App\Models\Office;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\OfficeTrait;
use App\Models\AllowanceProgram;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SystemconfigService
{


    /* -------------------------------------------------------------------------- */
    /*                              Allowance Service                              */
    /* -------------------------------------------------------------------------- */

    public function createallowance(Request $request){

        DB::beginTransaction();
        try {

            $allowance                         = new AllowanceProgram ;
            $allowance->name_en                = $request->name_en;
            $allowance->name_bn                = $request->name_bn;
            $allowance->guideline              = $request->guideline;
            $allowance->description            = $request->description;
            $allowance->service_type           = $request->service_type;
            $allowance->version                = $allowance->version+1;
            $allowance ->save();
            DB::commit();
            return $allowance;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    public function updateallowance(Request $request){

        DB::beginTransaction();
        try {
            $allowance                         = AllowanceProgram::find($request->id);
            $allowance->name_en                = $request->name_en;
            $allowance->name_bn                = $request->name_bn;
            $allowance->guideline              = $request->guideline;
            $allowance->description            = $request->description;
            $allowance->service_type           = $request->service_type;

            $allowance->version                = $allowance->version+1;
            $allowance->save();
            DB::commit();
            return $allowance;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
