<?php

namespace App\Http\Services\Coverage;

use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DivisionService
{
    public function createDivision(Request $request){
        DB::beginTransaction();
        try {

            $division                       = new Division;
            $division->name                = $request->name;
            $division->details                = $request->details;
            $division->save();
            DB::commit();
            return $division;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateDivisionService(Request $request, Division $division){
        DB::beginTransaction();
        try {
            $division->name                = $request->name;
            $division->details    = $request->details;
            $division->save();
            DB::commit();
            return $division;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
