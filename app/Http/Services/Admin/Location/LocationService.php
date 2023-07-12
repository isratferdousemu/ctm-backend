<?php

namespace App\Http\Services\Admin\Location;

use App\Http\Traits\LocationTrait;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LocationService
{
    use LocationTrait;
    public function createDivision(Request $request){

        DB::beginTransaction();
        try {

            $location                       = new Location;
            $location->name_en                = $request->name_en;
            $location->name_bn                = $request->name_bn;
            $location->code                   = $request->code;
            $location->type                   = $this->division;
            $location->created_by                   = Auth()->user()->id;
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
            $location->version                  = $location->version+1;

            $location->save();
            DB::commit();
            return $location;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
