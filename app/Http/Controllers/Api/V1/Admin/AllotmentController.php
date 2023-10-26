<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\AllowanceProgram;
use App\Models\AllowanceProgramAge;
use App\Models\AllowanceProgramAmount;
use App\Models\Location;
use Illuminate\Http\Response;

class AllotmentController extends Controller
{
    public function index()
    {
        //start
    }

    public function getAllowanceProgram()
    {
        $allowances = AllowanceProgram::where('is_active',1)->get();

        return response()->json([
            'data' => $allowances
        ],Response::HTTP_OK);
    }

    public function getAllowanceProgramAmount($id)
    {
        $allowance = AllowanceProgram::where('id', $id)->first();

        if ($allowance->is_disable_class == 0)
        {
            $allowance_age = AllowanceProgramAge::where('allowance_program_id', $id)->get();

            return response()->json([
                'data' => $allowance_age,
                'is_disable' => $allowance->is_disable_class
            ],Response::HTTP_OK);
        }else{
            $allowance_disable = AllowanceProgramAmount::where('allowance_program_id', $id)->get();

            $gender_allowance_age = AllowanceProgramAge::where('allowance_program_id', $id)->get();

            return response()->json([
                'data' => $allowance_disable,
                'education_gender' => $gender_allowance_age,
                'is_disable' => $allowance->is_disable_class
            ],Response::HTTP_OK);
        }
    }

    public function getDistrict()
    {
        $districts = Location::where('type','=','district')->get();

        return response()->json([
            'data' => $districts
        ],Response::HTTP_OK);
    }

    public function store()
    {
        //start
    }

    public function edit($id)
    {
        //start
    }

    public function update($id)
    {
        //start
    }

    public function destroy($id)
    {
        //
    }
}
