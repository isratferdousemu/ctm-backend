<?php

namespace App\Http\Services\Admin\Systemconfig;

use App\Helpers\Helper;
use App\Models\Office;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FinancialYear;
use App\Http\Traits\OfficeTrait;
use App\Models\AllowanceProgram;
use Carbon\Carbon;
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



    /* -------------------------------------------------------------------------- */
    /*                               financial Year                               */
    /* -------------------------------------------------------------------------- */
    public function createFinancialYear(Request $request){
        $financialYear = $request->financial_year;
        $financialYearArray = explode('-', $financialYear);
        $seventhMonth = 7;
        $sixthMonth = 6;
        $startDate = Carbon::create($financialYearArray[0], $seventhMonth, 1);
        $lastDate = Carbon::create($financialYearArray[1], $sixthMonth + 1, 1)->subDay();
        DB::beginTransaction();
        try {
            $financial                         = new FinancialYear;
            $financial->financial_year         = $financialYear;
            $financial->start_date             = $startDate;
            $financial->end_date               = $lastDate;
            $financial->status               = Helper::FinancialYear()==$financialYear ? true:false;
            $financial->version                = $financial->version+1;
            $financial ->save();
            DB::commit();
            return $financial;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }





}
