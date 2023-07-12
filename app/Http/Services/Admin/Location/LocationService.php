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


    /* -------------------------------------------------------------------------- */
    /*                              District Service                              */
    /* -------------------------------------------------------------------------- */

    public function createDistrict(Request $request){

        DB::beginTransaction();
        try {

            $location                         = new Location;
            $location->parent_id              = $request->division_id;
            $location->name_en                = $request->name_en;
            $location->name_bn                = $request->name_bn;
            $location->code                   = $request->code;
            $location->type                   = $this->district;
            $location->created_by             = Auth()->user()->id;
            $location->save();
            DB::commit();
            return $location;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateDistrict(Request $request){

        DB::beginTransaction();
        try {

            $location                       = Location::find($request->id);
            $location->name_en                = $request->name_en;
            $location->parent_id              = $request->division_id;
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


    /* -------------------------------------------------------------------------- */
    /*                                City Services                               */
    /* -------------------------------------------------------------------------- */
    public function createCity(Request $request){

        DB::beginTransaction();
        try {

            $location                         = new Location;
            $location->parent_id              = $request->district_id;
            $location->name_en                = $request->name_en;
            $location->name_bn                = $request->name_bn;
            $location->code                   = $request->code;
            $location->type                   = $this->city;
            $location->created_by             = Auth()->user()->id;
            $location->save();
            DB::commit();
            return $location;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    public function updateCity(Request $request){

        DB::beginTransaction();
        try {

            $location                       = Location::find($request->id);
            $location->parent_id              = $request->district_id;
            $location->name_en                = $request->name_en;
            $location->name_bn                = $request->name_bn;
            $location->code                   = $request->code;
            $location->version                = $location->version+1;

            $location->save();
            DB::commit();
            return $location;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    /* -------------------------------------------------------------------------- */
    /*                               Thana Services                               */
    /* -------------------------------------------------------------------------- */

    public function createThana(Request $request){

        DB::beginTransaction();
        try {

            $location                         = new Location;
            $location->parent_id              = $request->district_id;
            $location->name_en                = $request->name_en;
            $location->name_bn                = $request->name_bn;
            $location->code                   = $request->code;
            $location->type                   = $this->thana;
            $location->created_by             = Auth()->user()->id;
            $location->save();
            DB::commit();
            return $location;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    /* -------------------------------------------------------------------------- */
    /*                               Union Services                               */
    /* -------------------------------------------------------------------------- */


    public function createUnion(Request $request){

        DB::beginTransaction();
        try {

            $location                         = new Location;
            $location->parent_id              = $request->thana_id;
            $location->name_en                = $request->name_en;
            $location->name_bn                = $request->name_bn;
            $location->code                   = $request->code;
            $location->type                   = $this->union;
            $location->created_by             = Auth()->user()->id;
            $location->save();
            DB::commit();
            return $location;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateUnion(Request $request){

        DB::beginTransaction();
        try {

            $location                       = Location::find($request->id);
            $location->parent_id              = $request->thana_id;
            $location->name_en                = $request->name_en;
            $location->name_bn                = $request->name_bn;
            $location->code                   = $request->code;
            $location->version                = $location->version+1;

            $location->save();
            DB::commit();
            return $location;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /* -------------------------------------------------------------------------- */
    /*                               Ward Services                               */
    /* -------------------------------------------------------------------------- */


    public function createWard(Request $request){

        DB::beginTransaction();
        try {

            $location                         = new Location;
            $location->parent_id              = $request->union_id;
            $location->name_en                = $request->name_en;
            $location->name_bn                = $request->name_bn;
            $location->code                   = $request->code;
            $location->type                   = $this->ward;
            $location->created_by             = Auth()->user()->id;
            $location->save();
            DB::commit();
            return $location;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateWard(Request $request){

        DB::beginTransaction();
        try {

            $location                       = Location::find($request->id);
            $location->parent_id              = $request->union_id;
            $location->name_en                = $request->name_en;
            $location->name_bn                = $request->name_bn;
            $location->code                   = $request->code;
            $location->version                = $location->version+1;

            $location->save();
            DB::commit();
            return $location;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
