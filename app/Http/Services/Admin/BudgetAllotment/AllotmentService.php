<?php

namespace App\Http\Services\Admin\BudgetAllotment;


use App\Http\Requests\Admin\Allotment\StoreAllotmentRequest;
use App\Http\Requests\Admin\Allotment\UpdateAllotmentRequest;
use App\Models\Allotment;
use App\Models\AllotmentDetails;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Allotment Service
 */
class AllotmentService
{
    /**
     * @param $query
     * @param $request
     * @return mixed
     */
    private function applyLocationFilter($query, $request): mixed
    {
        $user = auth()->user()->load('assign_location.parent.parent.parent.parent');
        $assignedLocationId = $user->assign_location?->id;
        $subLocationType = $user->assign_location?->location_type;
        // 1=District Pouroshava, 2=Upazila, 3=City Corporation
        $locationType = $user->assign_location?->type;
        // division->district
        // localtion_type=1; district-pouroshava->ward
        // localtion_type=2; thana->{union/pouro}->ward
        // localtion_type=3; thana->ward

        $division_id = $request->query('division_id');
        $district_id = $request->query('district_id');
//        $location_type_id = $request->query('location_type_id');
        $city_corp_id = $request->query('city_corp_id');
        $district_pourashava_id = $request->query('district_pourashava_id');
        $upazila_id = $request->query('upazila_id');
//        $sub_location_type_id = $request->query('sub_location_type_id');
        $pourashava_id = $request->query('pourashava_id');
        $thana_id = $request->query('thana_id');
        $union_id = $request->query('union_id');
        $ward_id = $request->query('ward_id');

        if ($user->assign_location) {
            if ($locationType == 'ward') {
                $ward_id = $assignedLocationId;
                $division_id = $district_id = $city_corp_id = $district_pourashava_id = $upazila_id = $thana_id = $pourashava_id = $union_id = -1;
            } elseif ($locationType == 'union') {
                $union_id = $assignedLocationId;
                $division_id = $district_id = $city_corp_id = $district_pourashava_id = $upazila_id = $thana_id = $pourashava_id = -1;
            } elseif ($locationType == 'pouro') {
                $pourashava_id = $assignedLocationId;
                $division_id = $district_id = $city_corp_id = $district_pourashava_id = $upazila_id = $thana_id = $union_id = -1;
            } elseif ($locationType == 'thana') {
                if ($subLocationType == 2) {
                    $upazila_id = $assignedLocationId;
                    $division_id = $district_id = $city_corp_id = $district_pourashava_id = $thana_id = -1;
                } elseif ($subLocationType == 3) {
                    $thana_id = $assignedLocationId;
                    $division_id = $district_id = $city_corp_id = $district_pourashava_id = $upazila_id = -1;
                } else {
                    $query = $query->where('id', -1); // wrong location type
                }
            } elseif ($locationType == 'city') {
                if ($subLocationType == 1) {
                    $district_pourashava_id = $assignedLocationId;
                    $division_id = $district_id = $city_corp_id = $upazila_id = $thana_id = -1;
                } elseif ($subLocationType == 3) {
                    $city_corp_id = $assignedLocationId;
                    $division_id = $district_id = $district_pourashava_id = $upazila_id = $thana_id = -1;
                } else {
                    $query = $query->where('id', -1); // wrong location type
                }
            } elseif ($locationType == 'district') {
                $district_id = $assignedLocationId;
                $division_id = -1;
            } elseif ($locationType == 'division') {
                $division_id = $assignedLocationId;
            } else {
                $query = $query->where('id', -1); // wrong location assigned
            }
        }

        if ($division_id && $division_id > 0)
            $query = $query->where('division_id', $division_id);
        if ($district_id && $district_id > 0)
            $query = $query->where('district_id', $district_id);
        if ($city_corp_id && $city_corp_id > 0)
            $query = $query->where('city_corp_id', $city_corp_id);
        if ($district_pourashava_id && $district_pourashava_id > 0)
            $query = $query->where('district_pourashava_id', $district_pourashava_id);
        if ($upazila_id && $upazila_id > 0)
            $query = $query->where('upazila_id', $upazila_id);
        if ($pourashava_id && $pourashava_id > 0)
            $query = $query->where('pourashava_id', $pourashava_id);
        if ($thana_id && $thana_id > 0)
            $query = $query->where('thana_id', $thana_id);
        if ($union_id && $union_id > 0)
            $query = $query->where('union_id', $union_id);
        if ($ward_id && $ward_id > 0)
            $query = $query->where('ward_id', $ward_id);

        return $query;
    }

    /**
     * @param Request $request
     * @param $getAllRecords
     * @return mixed
     */
    public function list(Request $request, $getAllRecords = false)
    {
        $program_id = $request->query('program_id');
        $financial_year_id = $request->query('financial_year_id');

        $perPage = $request->query('perPage', 10);

        $query = AllotmentDetails::query();
        if ($program_id)
            $query = $query->whereRelation('budget', 'program_id', $program_id);

        if ($financial_year_id)
            $query = $query->whereRelation('budget', 'financial_year_id', $financial_year_id);

        $query = $this->applyLocationFilter($query, $request);


        if ($getAllRecords)
            return $query->with('upazila', 'cityCorporation', 'districtPourosova', 'location')
                ->orderBy('location_id')
                ->get();
        else
            return $query->with('upazila', 'cityCorporation', 'districtPourosova', 'location')
                ->orderBy('location_id')
                ->paginate($perPage);

    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function get($id)
    {
        return AllotmentDetails::with('budget.program', 'budget.financialYear', 'location')->find($id);
    }

    /**
     * @param UpdateAllotmentRequest $request
     * @param $id
     * @return mixed
     * @throws \Throwable
     */
    public function update(UpdateAllotmentRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $allotmentDetail = AllotmentDetails::findOrFail($id);
            $validated = $request->validated(['additional_beneficiaries', 'total_amount']);
            $allotmentDetail->fill($validated);
            $allotmentDetail->save();
            DB::commit();
            return $allotmentDetail;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Throwable
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $allotmentDetail = AllotmentDetails::findOrFail($id);
            $resp = $allotmentDetail->delete();
            DB::commit();
            return $resp;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

}
