<?php

namespace App\Http\Services\Coverage;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AreaService
{
    public function createArea(Request $request){
        DB::beginTransaction();
        try {

            $area                       = new Area;
            $area->name                = $request->name;
            $area->city_id                = $request->city_id;
            $area->save();
            DB::commit();
            return $area;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateAreaService(Request $request, Area $area){
        DB::beginTransaction();
        try {
            $area->name                = $request->name;
            $area->city_id                = $request->city_id;
            $area->save();
            DB::commit();
            return $area;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
