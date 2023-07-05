<?php

namespace App\Http\Services\Coverage;

use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ZoneService
{
    public function createZone(Request $request){
        DB::beginTransaction();
        try {

            $zone                       = new Zone;
            $zone->name                = $request->name;
            $zone->area_id                = $request->area_id;
            $zone->home_delivery                = $request->home_delivery;
            $zone->charge_one_kg                = $request->charge_one_kg;
            $zone->charge_two_kg                = $request->charge_two_kg;
            $zone->charge_three_kg                = $request->charge_three_kg;
            $zone->charge_extra_per_kg                = $request->charge_extra_per_kg;
            $zone->cod_charge                = $request->cod_charge;
            $zone->save();
            DB::commit();
            return $zone;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateZoneService(Request $request, Zone $zone){
        DB::beginTransaction();
        try {
            $zone->name                = $request->name;
            $zone->area_id                = $request->area_id;
            $zone->home_delivery                = $request->home_delivery;
            $zone->charge_one_kg                = $request->charge_one_kg;
            $zone->charge_two_kg                = $request->charge_two_kg;
            $zone->charge_three_kg                = $request->charge_three_kg;
            $zone->charge_extra_per_kg                = $request->charge_extra_per_kg;
            $zone->cod_charge                = $request->cod_charge;
            $zone->save();
            DB::commit();
            return $zone;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
