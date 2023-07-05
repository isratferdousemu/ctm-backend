<?php

namespace App\Http\Services\Coverage;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CityService
{
    public function createCity(Request $request){
        DB::beginTransaction();
        try {

            $city                       = new City;
            $city->name                = $request->name;
            $city->post_code                = $request->post_code;
            $city->division_id                = $request->division_id;
            $city->save();
            DB::commit();
            return $city;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateCityService(Request $request, City $city){
        DB::beginTransaction();
        try {
            $city->name                = $request->name;
            $city->post_code                = $request->post_code;
            $city->division_id                = $request->division_id;
            $city->save();
            DB::commit();
            return $city;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
