<?php

namespace App\Http\Services\Global;

use App\Models\AllowanceProgram;
use DB;
use Illuminate\Http\Request;

class GlobalService
{

    public function getPrograms()
    {
        return AllowanceProgram::with('lookup')->get();
    }
    public function getdropdownList($request)
    {
        // dd($request);
        $query = DB::table($request->table_name);
        // $conditions = json_decode($request->condition) ?? null;
        $conditions = $request->condition ?? [];

        foreach ($conditions as $key => $value) {
            if ($key == 'raw') {
                $query->whereRaw($value);
            } else {
                $query->where($key, $value);
            }
        }
        $query->select($request->field_name);
        $result = $query->get();
        return $result;
    }
}
