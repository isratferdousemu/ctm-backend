<?php

namespace App\Http\Services\Admin\PMTScore;

use App\Models\Variable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VariableService
{


    /* -------------------------------------------------------------------------- */
    /*                            Variable Service                              */
    /* -------------------------------------------------------------------------- */

    public function createVariable(Request $request)
    {

        DB::beginTransaction();
        try {

            $Variable                         = new Variable();
            $Variable->name_en                   = $request->name_en;
            $Variable->score              = $request->score;

            $Variable->save();

            DB::commit();
            return $Variable;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateVariable(Request $request)
    {

        DB::beginTransaction();
        try {

            $Variable                         = Variable::find($request->id);;
            $Variable->name_en                   = $request->name_en;
            $Variable->score              = $request->score;

            $Variable->save();

            DB::commit();
            return $Variable;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /* -------------------------------------------------------------------------- */
    /*                            Sub - Variable Service                              */
    /* -------------------------------------------------------------------------- */

    public function createSubVariable(Request $request)
    {

        DB::beginTransaction();
        try {

            $Variable                         = new Variable();
            $Variable->name_en                   = $request->name_en;
            $Variable->parent_id                   = $request->parent_id;
            $Variable->score              = $request->score;

            $Variable->save();

            DB::commit();
            return $Variable;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateSubVariable(Request $request)
    {

        DB::beginTransaction();
        try {

            $Variable                         = Variable::find($request->id);;
            $Variable->name_en                   = $request->name_en;
            $Variable->parent_id                   = $request->parent_id;
            $Variable->score              = $request->score;

            $Variable->save();

            DB::commit();
            return $Variable;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
