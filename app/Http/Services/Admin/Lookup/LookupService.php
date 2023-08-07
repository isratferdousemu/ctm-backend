<?php

namespace App\Http\Services\Admin\Lookup;

use App\Http\Traits\LookupTrait;
use App\Models\Lookup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LookupService
{


    /* -------------------------------------------------------------------------- */
    /*                              Lookup Service                              */
    /* -------------------------------------------------------------------------- */

    public function createLookup(Request $request){

        DB::beginTransaction();
        try {

            $lookup                         = new Lookup;
            $lookup->type                   = $request->type;
            $lookup->value_en               = $request->value_en;
            $lookup->value_bn               = $request->value_bn;
            $lookup->keyword                = $request->keyword;




            $lookup ->save();
            DB::commit();
            return $lookup;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    public function updateLookup(Request $request){

        DB::beginTransaction();
        try {
            $lookup                         = Lookup::find($request->id);
            $lookup->type                   = $request->type;
            $lookup->value_en               = $request->value_en;
            $lookup->value_bn               = $request->value_bn;
            $lookup->keyword                = $request->keyword;

            $lookup->version                = $lookup->version+1;
            $lookup->save();
            DB::commit();
            return $lookup;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


}
