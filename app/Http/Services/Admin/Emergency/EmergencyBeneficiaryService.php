<?php

namespace App\Http\Services\Admin\Emergency;

use App\Models\Beneficiary;
use App\Models\EmergencyAllotment;
use App\Models\EmergencyBeneficiary;



class EmergencyBeneficiaryService
{


    public function getExistingBeneficaries($request)
    {
        $queryParams = $request->only([
            'division_id', 'district_id', 'location_type', 'thana_id',
            'union_id', 'city_id', 'city_thana_id', 'district_pouro_id',
            'searchBy', 'status', 'program_id', 'perPage'
        ]);
        
        $perPage = $request->query('perPage', 10);
        $query = Beneficiary::query();

        if (!empty($queryParams['division_id'])) {
            $query->where('current_division_id', $queryParams['division_id']);
        }

        if (!empty($queryParams['district_id'])) {
            $query->where('current_district_id', $queryParams['district_id']);
        }

        if (!empty($queryParams['location_type'])) {
            $query->where('current_location_type_id', $queryParams['location_type']);
        }

        if (!empty($queryParams['thana_id'])) {
            $query->where('current_upazila_id', $queryParams['thana_id']);
        }

        if (!empty($queryParams['union_id'])) {
            $query->where('current_union_id', $queryParams['union_id']);
        }

        if (!empty($queryParams['city_id'])) {
            $query->where('current_city_corp_id', $queryParams['city_id']);
        }

        // if (!empty($queryParams['city_thana_id'])) {
        //     $query->where('current_city_thana_id', $queryParams['city_thana_id']);
        // }

        if (!empty($queryParams['district_pouro_id'])) {
            $query->where('current_district_pourashava_id', $queryParams['district_pouro_id']);
        }

        if (!empty($queryParams['status'])) {
            $query->where('status', $queryParams['status']);
        }

        // Handle searchBy - Assuming 'searchBy' can be used to filter by name or other text fields
        // if (!empty($queryParams['searchBy'])) {
        //     $searchBy = $queryParams['searchBy'];
        //     $query->where(function ($q) use ($searchBy) {
        //         $q->where('name_en', 'LIKE', "%{$searchBy}%")
        //             ->orWhere('name_bn', 'LIKE', "%{$searchBy}%")
        //             ->orWhere('email', 'LIKE', "%{$searchBy}%");
        //         // Add more fields as needed
        //     });
        // }

        $data = $query->with(
            'program',
            'permanentDivision',
            'permanentDistrict',
            'permanentCityCorporation',
            'permanentDistrictPourashava',
            'permanentUpazila',
            'permanentPourashava',
            'permanentThana',
            'permanentUnion',
            'permanentWard'
        )->paginate($perPage);

        return $data;
    }



    public function store($request)
    {

        dd($request->all());
        try {

            $beneficiary = new EmergencyBeneficiary();

            if ($request->has('city_id')) {
                $beneficiary->city_corp_id = $request->city_id;
            }
            if ($request->has('city_thana_id')) {
                $beneficiary->thana_id = $request->city_thana_id;
            }
            if ($request->has('thana_id')) {
                $beneficiary->upazila_id = $request->thana_id;
            }
            if ($request->has('union_id')) {
                $beneficiary->union_id = $request->union_id;
            }

            if ($request->has('district_pouro_id')) {
                $beneficiary->district_pourashava_id = $request->district_pouro_id;
            }

            $beneficiary->program_id                            = $request->program_id;
            $beneficiary->division_id                           = $request->division_id;
            $beneficiary->district_id                           = $request->district_id;
            $beneficiary->location_type                         = $request->location_type;
            $beneficiary->status                                = 1;
            $beneficiary->save();

            return $beneficiary;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
