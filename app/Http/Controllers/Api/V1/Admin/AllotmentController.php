<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Allotment\AlotmentRequest;
use App\Models\Allotment;
use App\Models\AllotmentDetails;
use App\Models\AllotmentExtraBeneficiary;
use App\Models\AllowanceProgram;
use App\Models\AllowanceProgramAge;
use App\Models\AllowanceProgramAmount;
use App\Models\FinancialYear;
use App\Models\Location;
use Illuminate\Http\Response;

class AllotmentController extends Controller
{
    public function index()
    {

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

    public function getLocation($id)
    {
        //$locations = Location::whereId($id)->with(['children.children.office','children.office'])->get();

        $locations = Location::whereId($id)->with(['children.children'])->get();

        return response()->json([
            'data' => $locations
        ],Response::HTTP_OK);
    }

    public function getFinancialYear()
    {
        $financial_year = FinancialYear::latest()->get();

        return \response()->json([
            'data' => $financial_year
        ]);
    }

    public function store(AlotmentRequest $request)
    {
        if ($request->isMethod('post'))
        {
            \DB::beginTransaction();

            try {

                $allotment = new Allotment();

                $allotment->program_id = $request->program_id;
                $allotment->location_id = $request->location_id;
                $allotment->financial_year_id = $request->financial_year_id;

                $allotment->save();

                foreach ($request->allotment_details as $value)
                {
                    $allotment_details = new AllotmentDetails();

                    $allotment_details->allotment_id = $allotment->id;

                    $allotment_details->location_id = $value['location_id'];
                    $allotment_details->office_id = $value['office_id'];
                    $allotment_details->beneficiary_regular = $value['beneficiary_regular'];
                    $allotment_details->beneficiary_total = $value['beneficiary_total'];
                    $allotment_details->allocated_money = $value['allocated_money'];

                    $allotment_details->save();
                }

                foreach ($request->allotment_extra as $value)
                {
                    $allotment_extra = new AllotmentExtraBeneficiary();

                    $allotment_extra->allotment_id = $allotment->id;
                    $allotment_extra->gender_id = $value['gender_id'];
                    $allotment_extra->beneficiary_additional = $value['beneficiary_additional'];

                    $allotment_extra->save();
                }

                \DB::commit();

                return \response()->json([
                    'message' => 'insert success'
                ],Response::HTTP_CREATED);

            }catch (\Exception $e){
                \DB::rollBack();

                $error = $e->getMessage();

                return \response()->json([
                    'error' => $error
                ],Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function edit($id)
    {
        $allotment = Allotment::findOrFail($id);

        $allotment_details = AllotmentDetails::where('allotment_id', $id)->get();

        $allotment_extra = AllotmentExtraBeneficiary::where('allotment_id', $id)->get();

        return \response()->json([
            "allotment" => $allotment,
            "allotment_details" => $allotment_details,
            "allotment_extra" => $allotment_extra
        ],Response::HTTP_OK);
    }

    public function update(AlotmentRequest $request ,$id)
    {
        if ($request->_method == 'PUT')
        {
            \DB::beginTransaction();

            try {

                $allotment = Allotment::where('id', $id)->first();

                $allotment->program_id = $request->program_id;
                $allotment->location_id = $request->location_id;
                $allotment->financial_year_id = $request->financial_year_id;

                $allotment->save();

                foreach ($request->allotment_details as $value)
                {
                   AllotmentDetails::updateOrInsert(
                       ["id" => $value['id']],
                       [
                           "allotment_id" => $allotment->id,
                            "location_id" => $value['location_id'],
                            "office_id" => $value['office_id'],
                            "beneficiary_regular" => $value['beneficiary_regular'],
                            "beneficiary_total" => $value['beneficiary_total'],
                            "allocated_money" => $value['allocated_money']
                       ]
                   );
                }

                foreach ($request->allotment_extra as $value)
                {
                    AllotmentExtraBeneficiary::updateOrInsert(
                        ["id" => $value['id']],
                        [
                            "allotment_id" => $allotment->id,
                            "gender_id" => $value['gender_id'],
                            "beneficiary_additional" => $value['beneficiary_additional']
                        ]
                    );
                }

                \DB::commit();

                return \response()->json([
                    'message' => 'update success'
                ],Response::HTTP_OK);

            }catch (\Exception $e){
                \DB::rollBack();

                $error = $e->getMessage();

                return \response()->json([
                    'error' => $error
                ],Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function destroy($id)
    {
        if ($id != null)
        {
            $allotment = Allotment::findOrFail($id);

            $allotment_details = AllotmentDetails::where('allotment_id', $id)->get();

            if ($allotment_details != null)
            {
                $allotment_details->delete();
            }

            $allotment_extra = AllotmentExtraBeneficiary::where('allotment_id', $io)->get();

            if ($allotment_extra != null)
            {
                $allotment_extra->delete();
            }

            $allotment->delete();

            return \response()->json([
                'message' => 'delete success'
            ],Response::HTTP_OK);

        }
    }
}
