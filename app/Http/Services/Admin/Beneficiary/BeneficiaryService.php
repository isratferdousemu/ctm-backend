<?php

namespace App\Http\Services\Admin\Beneficiary;


use App\Http\Resources\Admin\Location\LocationResource;
use App\Models\Beneficiary;
use App\Models\BeneficiaryExit;
use App\Models\BeneficiaryReplace;
use App\Models\BeneficiaryShifting;
use Arr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class BeneficiaryService
{
    /**
     * @return array
     */
    public function getUserLocation(): array
    {
        $user = auth()->user()->load('assign_location.parent.parent.parent.parent');
        $assignLocation = $user->assign_location;
        $locationType = $user->assign_location?->localtion_type;
        // 1=District Pouroshava, 2=Upazila, 3=City Corporation
        $type = $user->assign_location?->type;
        // division->district
        // localtion_type=1; district-pouroshava->ward
        // localtion_type=2; thana->{union/pouro}->ward
        // localtion_type=3; thana->ward
        $userLocation = [];
        if ($assignLocation?->type == 'ward') {
            $userLocation['ward_id'] = $assignLocation?->id;
            // 1st parent
            if ($assignLocation?->parent?->type == 'union') {
                $userLocation['union'] = $assignLocation?->parent;
                $userLocation['sub_location_type'] = $assignLocation?->parent?->type;
            } elseif ($assignLocation?->parent?->type == 'pouro') {
                $userLocation['pourashava'] = $assignLocation?->parent;
                $userLocation['sub_location_type'] = $assignLocation?->parent?->type;
            } elseif ($assignLocation?->parent?->type == 'city') {
                $userLocation['district_pourashava'] = $assignLocation?->parent;
                $userLocation['location_type'] = $assignLocation?->parent?->location_type;
            } elseif ($assignLocation?->parent?->type == 'thana') {
                $userLocation['thana'] = $assignLocation?->parent;
            }

            // 2nd parent
            if ($assignLocation?->parent?->parent?->type == 'thana') {
                $userLocation['thana'] = $assignLocation?->parent?->parent;
                $userLocation['location_type'] = 2;//$assignLocation?->parent?->location_type;
            } elseif ($assignLocation?->parent?->parent?->type == 'city') {
                $userLocation['city_corp'] = $assignLocation?->parent;
                $userLocation['location_type'] = $assignLocation?->parent?->location_type;
            }
            // 3rd parent
            $userLocation['district'] = $assignLocation?->parent?->parent;
            // 4th parent
            $userLocation['division'] = $assignLocation?->parent?->parent?->parent;
        } elseif ($assignLocation?->type == 'union' || $assignLocation?->type == 'pouro') {
            if ($assignLocation?->type == 'union')
                $userLocation['union'] = $assignLocation;
            elseif ($assignLocation?->type == 'pouro')
                $userLocation['pourashava'] = $assignLocation;
            $userLocation['sub_location_type'] = 2;//$assignLocation?->type;
            $userLocation['location_type'] = $assignLocation?->location_type;
            // parents
            $userLocation['thana'] = $assignLocation?->parent;
            $userLocation['district'] = $assignLocation?->parent?->parent;
            $userLocation['division'] = $assignLocation?->parent?->parent?->parent;
        } elseif ($assignLocation?->type == 'thana') {
            $userLocation['thana'] = $assignLocation;
            $userLocation['location_type'] = $assignLocation?->location_type;
            if ($assignLocation?->location_type == 2) {
                // parents
                $userLocation['district'] = $assignLocation?->parent;
                $userLocation['division'] = $assignLocation?->parent?->parent;
            } elseif ($assignLocation?->location_type == 3) {
                // parents
                $userLocation['city_corp'] = $assignLocation?->parent;
                $userLocation['district'] = $assignLocation?->parent?->parent;
                $userLocation['division'] = $assignLocation?->parent?->parent?->parent;
            }

        } elseif ($assignLocation?->type == 'city') {
            if ($assignLocation?->location_type == 1)
                $userLocation['district_pourashava'] = $assignLocation;
            elseif ($assignLocation?->parent?->location_type == 3)
                $userLocation['city_corp'] = $assignLocation;
            $userLocation['location_type'] = $assignLocation?->location_type;
            // parents
            $userLocation['district'] = $assignLocation?->parent;
            $userLocation['division'] = $assignLocation?->parent?->parent;
        } elseif ($assignLocation?->type == 'district') {
            $userLocation['district'] = new LocationResource($assignLocation);
            $userLocation['division'] = new LocationResource($assignLocation?->parent);
        } elseif ($assignLocation?->type == 'division')
            $userLocation['division'] = $assignLocation;


        return $userLocation;
    }

    /**
     * @param $query
     * @param $request
     * @return mixed
     */
    private function applyLocationFilter($query, $request): mixed
    {
        $user = auth()->user()->load('assign_location.parent.parent.parent.parent');
        $assignedLocationId = $user->assign_location?->id;
        $subLocationType = $user->assign_location?->localtion_type;
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
//                if ($subLocationType == 2) {
//                    $upazila_id = $assignedLocationId;
//                    $division_id = $district_id = $city_corp_id = $district_pourashava_id = $thana_id = -1;
//                } elseif ($subLocationType == 3) {
//                    $thana_id = $assignedLocationId;
//                    $division_id = $district_id = $city_corp_id = $district_pourashava_id = $upazila_id = -1;
//                }
                $thana_id = $assignedLocationId;
                $division_id = $district_id = $city_corp_id = $district_pourashava_id = -1;
            } elseif ($locationType == 'city') {
                if ($subLocationType == 1) {
                    $district_pourashava_id = $assignedLocationId;
                    $division_id = $district_id = $city_corp_id = $upazila_id = $thana_id = -1;
                } elseif ($subLocationType == 3) {
                    $city_corp_id = $assignedLocationId;
                    $division_id = $district_id = $district_pourashava_id = $upazila_id = $thana_id = -1;
                }
//            } elseif ($locationType == 'district-pouroshava') {
//                $district_pourashava_id = $assignedLocationId;
//                $division_id = $district_id = $city_corp_id = $upazila_id = $thana_id = -1;
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
            $query = $query->where('permanent_division_id', $division_id);
        if ($district_id && $district_id > 0)
            $query = $query->where('permanent_district_id', $district_id);
        if ($city_corp_id && $city_corp_id > 0)
            $query = $query->where('permanent_city_corp_id', $city_corp_id);
        if ($district_pourashava_id && $district_pourashava_id > 0)
            $query = $query->where('permanent_district_pourashava_id', $district_pourashava_id);
//        if ($upazila_id && $upazila_id > 0)
//            $query = $query->where('permanent_upazila_id', $upazila_id);
        if ($pourashava_id && $pourashava_id > 0)
            $query = $query->where('permanent_pourashava_id', $pourashava_id);
        if ($thana_id && $thana_id > 0) {
            $query = $query->where(function ($q) use ($thana_id) {
                $q->where('permanent_upazila_id', $thana_id)->orWhere('permanent_thana_id', $thana_id);
            });
        }
        if ($union_id && $union_id > 0)
            $query = $query->where('permanent_union_id', $union_id);
        if ($ward_id && $ward_id > 0)
            $query = $query->where('permanent_ward_id', $ward_id);

        return $query;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function list(Request $request, $forPdf = false)
    {
        $program_id = $request->query('program_id');

        $beneficiary_id = $request->query('beneficiary_id');
        $nominee_name = $request->query('nominee_name');
        $account_number = $request->query('account_number');
        $verification_number = $request->query('nid');
        $status = $request->query('status');

        $perPage = $request->query('perPage', 10);
        $sortByColumn = $request->query('sortBy', 'created_at');
        $orderByDirection = $request->query('orderBy', 'asc');

        $query = Beneficiary::query();
        if ($program_id)
            $query = $query->where('program_id', $program_id);

        $query = $this->applyLocationFilter($query, $request);

        // advance search
        if ($beneficiary_id)
            $query = $query->where('application_id', $beneficiary_id);
        if ($nominee_name)
            $query = $query->whereRaw('UPPER(nominee_en) LIKE "%' . strtoupper($nominee_name) . '%"');
        if ($account_number)
            $query = $query->where('account_number', $account_number);
        if ($verification_number)
            $query = $query->where('verification_number', $verification_number);
        if ($status)
            $query = $query->where('status', $status);

        if ($forPdf)
            return $query->with('program',
                'permanentDivision',
                'permanentDistrict',
                'permanentCityCorporation',
                'permanentDistrictPourashava',
                'permanentUpazila',
                'permanentPourashava',
                'permanentThana',
                'permanentUnion',
                'permanentWard')->orderBy("$sortByColumn", "$orderByDirection")->get();
        else
            return $query->with('program',
                'permanentDivision',
                'permanentDistrict',
                'permanentCityCorporation',
                'permanentDistrictPourashava',
                'permanentUpazila',
                'permanentPourashava',
                'permanentThana',
                'permanentUnion',
                'permanentWard')->orderBy("$sortByColumn", "$orderByDirection")->paginate($perPage);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
     */
    public function detail($id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        return Beneficiary::with('program',
            'gender',
            'currentDivision',
            'currentDistrict',
            'currentCityCorporation',
            'currentDistrictPourashava',
            'currentUpazila',
            'currentPourashava',
            'currentThana',
            'currentUnion',
            'currentWard',
            'permanentDivision',
            'permanentDistrict',
            'permanentCityCorporation',
            'permanentDistrictPourashava',
            'permanentUpazila',
            'permanentPourashava',
            'permanentThana',
            'permanentUnion',
            'permanentWard')
            ->find($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id): mixed
    {
        return Beneficiary::find($id);
    }

    /**
     * @param $beneficiary_id
     * @return mixed
     */
    public function getByBeneficiaryId($beneficiary_id): mixed
    {
        return Beneficiary::with('program')->where('application_id', $beneficiary_id)->first();
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
     */
    public function edit($id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        return Beneficiary::with('program',
            'gender',
            'currentDivision',
            'currentDistrict',
            'currentCityCorporation',
            'currentDistrictPourashava',
            'currentUpazila',
            'currentPourashava',
            'currentThana',
            'currentUnion',
            'currentWard',
            'permanentDivision',
            'permanentDistrict',
            'permanentCityCorporation',
            'permanentDistrictPourashava',
            'permanentUpazila',
            'permanentPourashava',
            'permanentThana',
            'permanentUnion',
            'permanentWard')
            ->findOrFail($id);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
     */
    public function update(Request $request, $id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        DB::beginTransaction();
        try {
            $beneficiary = Beneficiary::findOrFail($id);
            $validatedData = $request->all();
            $beneficiary->fill($validatedData);
            $beneficiary->save();
            DB::commit();
            return $beneficiary;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function getListForReplace(Request $request): \Illuminate\Contracts\Pagination\Paginator
    {
        $program_id = $request->query('program_id');
        $division_id = $request->query('division_id');
        $district_id = $request->query('district_id');
        $city_corp_id = $request->query('city_corp_id');
        $district_pourashava_id = $request->query('district_pourashava_id');
//        $upazila_id = $request->query('upazila_id');
//        $pourashava_id = $request->query('pourashava_id');
        $thana_id = $request->query('thana_id');
        $union_id = $request->query('union_id');
        $ward_id = $request->query('ward_id');

        $beneficiary_id = $request->query('beneficiary_id');
        $nominee_name = $request->query('nominee_name');
        $account_number = $request->query('account_number');
        $verification_number = $request->query('nid');
        $status = $request->query('status');

        $perPage = $request->query('perPage', 10);
        $sortByColumn = $request->query('sortBy', 'created_at');
        $orderByDirection = $request->query('orderBy', 'asc');

        $query = Beneficiary::query();
        if ($program_id)
            $query = $query->where('program_id', $program_id);
        if ($division_id)
            $query = $query->where('permanent_division_id', $division_id);
        if ($district_id)
            $query = $query->where('permanent_district_id', $district_id);
        if ($city_corp_id)
            $query = $query->where('permanent_city_corp_id', $city_corp_id);
        if ($district_pourashava_id)
            $query = $query->where('permanent_district_pourashava_id', $district_pourashava_id);
//        if ($upazila_id)
//            $query = $query->where('permanent_upazila_id', $upazila_id);
//        if ($pourashava_id)
//            $query = $query->where('permanent_pourashava_id', $pourashava_id);
//        if ($thana_id)
//            $query = $query->where('permanent_thana_id', $thana_id);
        if ($thana_id)
            $query = $query->where(function ($q) use ($thana_id) {
                $q->where('permanent_thana_id', $thana_id)
                    ->orWhere('permanent_upazila_id', $thana_id);
            });
        if ($union_id)
            $query = $query->where(function ($q) use ($union_id) {
                $q->where('permanent_union_id', $union_id)
                    ->orWhere('permanent_pourashava_id', $union_id);
            });

//        if ($union_id)
//            $query = $query->where('permanent_union_id', $union_id);
        if ($ward_id)
            $query = $query->where('permanent_ward_id', $ward_id);

        // advance search
        if ($beneficiary_id)
            $query = $query->where('application_id', $beneficiary_id);
        if ($nominee_name)
            $query = $query->whereRaw('UPPER(nominee_en) LIKE "%' . strtoupper($nominee_name) . '%"');
        if ($account_number)
            $query = $query->where('account_number', $account_number);
        if ($verification_number)
            $query = $query->where('verification_number', $verification_number);
//        if ($status)
        $query = $query->where('status', 3); // only waiting beneficiaries


        return $query->with('program',
            'permanentDivision',
            'permanentDistrict',
            'permanentCityCorporation',
            'permanentDistrictPourashava',
            'permanentUpazila',
            'permanentPourashava',
            'permanentThana',
            'permanentUnion',
            'permanentWard')->orderBy("$sortByColumn", "$orderByDirection")->paginate($perPage);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
     * @throws \Throwable
     */
    public function replaceSave(Request $request, $id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        DB::beginTransaction();
        try {
            $beneficiary = Beneficiary::findOrFail($id);
            $beneficiary->status = 2; // Inactive
            $beneficiary->updated_at = now();
            $beneficiary->save();

            $replaceWithBeneficiaryId = $request->input('replace_with_ben_id');
            $beneficiaryReplaceWith = Beneficiary::findOrFail($replaceWithBeneficiaryId);
            $beneficiaryReplaceWith->status = 1; // Active
            $beneficiaryReplaceWith->updated_at = now();
            $beneficiaryReplaceWith->save();

            $beneficiaryReplace = new BeneficiaryReplace();
            $beneficiaryReplace->beneficiary_id = $id;
            $beneficiaryReplace->replace_with_ben_id = $replaceWithBeneficiaryId;
            $beneficiaryReplace->cause_id = $request->input('cause_id');
            $beneficiaryReplace->cause_detail = $request->input('cause_detail');
            $beneficiaryReplace->cause_date = $request->input('cause_date') ? Carbon::parse($request->input('cause_date')) : null;
            $beneficiaryReplace->cause_proof_doc = $request->input('cause_proof_doc');
            $beneficiaryReplace->created_at = now();
            $beneficiaryReplace->save();

            DB::commit();
            return $beneficiary;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @throws \Throwable
     */
    public function exitSave(Request $request): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|bool|\Illuminate\Database\Eloquent\Builder|array|null
    {
        DB::beginTransaction();
        try {

            if (!$request->has('beneficiaries')) {
                DB::rollBack();
                throw new \Exception('No beneficiaries was selected for replace!');
            }
            $exitDataList = [];
            foreach ($request->input('beneficiaries') as $beneficiary) {
                $exitDataList[] = [
                    'beneficiary_id' => $beneficiary['beneficiary_id'],
                    'exit_reason_id' => $request->input('exit_reason_id'),
                    'exit_reason_detail' => $request->input('exit_reason_detail'),
                    'exit_date' => $request->input('exit_date') ? Carbon::parse($request->input('exit_date')) : now(),
                ];
            }
            BeneficiaryExit::insert($exitDataList);
            $beneficiary_ids = Arr::pluck($exitDataList, 'beneficiary_id');

            Beneficiary::whereIn('id', $beneficiary_ids)->update(['status' => 2]); // Inactive

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
     * @throws \Throwable
     */
    public function shiftingSave(Request $request): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        DB::beginTransaction();
        try {
            if (!$request->has('beneficiaries')) {
                DB::rollBack();
                throw new \Exception('No beneficiaries was selected for shifting!');
            }
            $shiftingDataList = [];
            foreach ($request->input('beneficiaries') as $beneficiary) {
                $shiftingDataList[] = [
                    'beneficiary_id' => $beneficiary['beneficiary_id'],
                    'from_program_id' => $beneficiary['from_program_id'],
                    'to_program_id' => $request->input('to_program_id'),
//                    'shifting_cause_id' => $request->input('shifting_cause_id'),
                    'shifting_cause' => $request->input('shifting_cause'),
                    'activation_date' => $request->input('activation_date') ? Carbon::parse($request->input('activation_date')) : now(),
                ];
            }
            BeneficiaryShifting::insert($shiftingDataList);
            foreach ($shiftingDataList as $shiftingData) {
                Beneficiary::where('id', $shiftingData['beneficiary_id'])->update(['program_id' => $shiftingData['to_program_id']]);
            }
            DB::commit();
            return $shiftingDataList;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

}
