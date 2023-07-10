<?php

namespace App\Http\Services\Admin\Location;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LocationService
{
    public function createDivision(Request $request){

        DB::beginTransaction();
        try {

            $location                       = new Location;
            $location->name_en                = $request->name_en;
            $location->name_bn                = $request->name_bn;
            $location->code                   = $request->code;
            $location->save();
            DB::commit();
            return $location;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function updateDivision(Request $request){

        DB::beginTransaction();
        try {

            $location                       = Location::find($request->id);
            $location->name_en                = $request->name_en;
            $location->name_bn                = $request->name_bn;
            $location->code                   = $request->code;
            $location->save();
            DB::commit();
            return $location;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
