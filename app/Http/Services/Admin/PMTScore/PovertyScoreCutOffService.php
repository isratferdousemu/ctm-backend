<?php

namespace App\Http\Services\Admin\PMTScore;
use App\Models\PovertyScoreCutOff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PovertyScoreCutOffService
{


    /* -------------------------------------------------------------------------- */
    /*                            PovertyScoreCutOff Service                              */
    /* -------------------------------------------------------------------------- */

    public function createPovertyScoreCutOff(Request $request){

        DB::beginTransaction();
        try {

            $povertyScoreCutOff                         = new PovertyScoreCutOff();
            $povertyScoreCutOff->type                   = $request->type;
            $povertyScoreCutOff->location_id             = $request->location_id;
            $povertyScoreCutOff->score              = $request->score;

            $povertyScoreCutOff ->save();

            DB::commit();
            return $povertyScoreCutOff;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    public function updatePovertyScoreCutOff(Request $request){

        DB::beginTransaction();
        try {

            $povertyScoreCutOff                         = PovertyScoreCutOff::find($request->id);;
            $povertyScoreCutOff->type                   = $request->type;
            $povertyScoreCutOff->location_id             = $request->location_id;
            $povertyScoreCutOff->score              = $request->score;

            $povertyScoreCutOff ->save();

            DB::commit();
            return $povertyScoreCutOff;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }



}
