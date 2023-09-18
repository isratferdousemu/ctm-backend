<?php

namespace App\Http\Services\Admin\Office;

use App\Http\Traits\OfficeTrait;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OfficeService
{


    /* -------------------------------------------------------------------------- */
    /*                              Office Service                              */
    /* -------------------------------------------------------------------------- */

    public function createOffice(Request $request){

        DB::beginTransaction();
        try {

            $office                          = new Office;
            $office->division_id            = $request->division_id;
            $office->district_id            = $request->district_id;
            $office->thana_id               = $request->thana_id;
            $office->name_en                = $request->name_en;
            $office->name_bn                = $request->name_bn;
            $office->office_type            = $request->office_type;
            $office->office_address         = $request->office_address;
            $office->comment                = $request->comment;
            $office->status                 = $request->status;



            $office ->save();
            DB::commit();
            return $office;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    public function updateOffice(Request $request){

        DB::beginTransaction();
        try {
            $office                           = Office::find($request->id);
            $office ->division_id            = $request->division_id;
            $office ->district_id            = $request->district_id;
            $office ->thana_id               = $request->thana_id;
            $office ->name_en                = $request->name_en;
            $office ->name_bn                = $request->name_bn;
            $office ->thana_id               = $request->thana_id;
            $office ->office_type            = $request->office_type;
            $office ->office_address         = $request->office_address;
            $office ->comment                = $request->comment;
            $office ->status                 = $request->status;
            $office->version                = $office->version+1;
            $office->save();
            DB::commit();
            return $office;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
