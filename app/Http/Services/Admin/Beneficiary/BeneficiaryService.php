<?php

namespace App\Http\Services\Admin\Beneficiary;


use App\Http\Resources\Admin\Location\LocationResource;
use App\Http\Resources\Admin\Lookup\LookupResource;
use App\Models\Beneficiary;
use App\Models\BeneficiaryExit;
use App\Models\BeneficiaryReplace;
use App\Models\BeneficiaryShifting;
use App\Models\Lookup;
use Arr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

/**
 *
 */
class BeneficiaryService
{
    /**
     * @param $locationTypeId
     * @return LookupResource
     */
    public function getLocationType($locationTypeId): LookupResource
    {
        return new LookupResource(Lookup::find($locationTypeId));
    }

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
            $userLocation['ward'] = new LocationResource($assignLocation);
            // 1st parent
            if ($assignLocation?->parent?->type == 'union') {
                $userLocation['union'] = new LocationResource($assignLocation?->parent);
                $userLocation['sub_location_type'] = $assignLocation?->parent?->type;
            } elseif ($assignLocation?->parent?->type == 'pouro') {
                $userLocation['pourashava'] = new LocationResource($assignLocation?->parent);
                $userLocation['sub_location_type'] = $assignLocation?->parent?->type;
            } elseif ($assignLocation?->parent?->type == 'city') {
                $userLocation['district_pourashava'] = new LocationResource($assignLocation?->parent);
                $userLocation['location_type'] = $this->getLocationType($assignLocation?->parent?->location_type);
            } elseif ($assignLocation?->parent?->type == 'thana') {
                $userLocation['thana'] = new LocationResource($assignLocation?->parent);
                $userLocation['location_type'] = $this->getLocationType($assignLocation?->parent?->location_type);
            }

            // 2nd parent
            if ($assignLocation?->parent?->parent?->type == 'thana') {
                $userLocation['upazila'] = new LocationResource($assignLocation?->parent?->parent);
                $userLocation['location_type'] = $this->getLocationType($assignLocation?->parent?->parent?->location_type);
            } elseif ($assignLocation?->parent?->parent?->type == 'city') {
                $userLocation['city_corp'] = new LocationResource($assignLocation?->parent);
                $userLocation['location_type'] = $this->getLocationType($assignLocation?->parent?->parent?->location_type);
            }
            // 3rd parent
            $userLocation['district'] = new LocationResource($assignLocation?->parent?->parent);
            // 4th parent
            $userLocation['division'] = new LocationResource($assignLocation?->parent?->parent?->parent);
        } elseif ($assignLocation?->type == 'union' || $assignLocation?->type == 'pouro') {
            if ($assignLocation?->type == 'union')
                $userLocation['union'] = new LocationResource($assignLocation);
            elseif ($assignLocation?->type == 'pouro')
                $userLocation['pourashava'] = new LocationResource($assignLocation);
            $userLocation['sub_location_type'] = $assignLocation?->type;

            // parents
            $userLocation['location_type'] = $this->getLocationType($assignLocation?->parent?->location_type);
            $userLocation['upazila'] = new LocationResource($assignLocation?->parent);
            $userLocation['district'] = new LocationResource($assignLocation?->parent?->parent);
            $userLocation['division'] = new LocationResource($assignLocation?->parent?->parent?->parent);
        } elseif ($assignLocation?->type == 'thana') {
            $userLocation['location_type'] = $this->getLocationType($assignLocation?->location_type);
            if ($assignLocation?->location_type == 2) {
                $userLocation['upazila'] = new LocationResource($assignLocation);
                // parents
                $userLocation['district'] = new LocationResource($assignLocation?->parent);
                $userLocation['division'] = new LocationResource($assignLocation?->parent?->parent);
            } elseif ($assignLocation?->location_type == 3) {
                $userLocation['thana'] = new LocationResource($assignLocation);
                // parents
                $userLocation['city_corp'] = new LocationResource($assignLocation?->parent);
                $userLocation['district'] = new LocationResource($assignLocation?->parent?->parent);
                $userLocation['division'] = new LocationResource($assignLocation?->parent?->parent?->parent);
            }

        } elseif ($assignLocation?->type == 'city') {
            if ($assignLocation?->location_type == 1)
                $userLocation['district_pourashava'] = new LocationResource($assignLocation);
            elseif ($assignLocation?->location_type == 3)
                $userLocation['city_corp'] = new LocationResource($assignLocation);
            $userLocation['location_type'] = $this->getLocationType($assignLocation?->location_type);
            // parents
            $userLocation['district'] = new LocationResource($assignLocation?->parent);
            $userLocation['division'] = new LocationResource($assignLocation?->parent?->parent);
        } elseif ($assignLocation?->type == 'district') {
            $userLocation['district'] = new LocationResource($assignLocation);
            $userLocation['division'] = new LocationResource($assignLocation?->parent);
        } elseif ($assignLocation?->type == 'division')
            $userLocation['division'] = new LocationResource($assignLocation);
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
                }else {
                    $query = $query->where('id', -1); // wrong location type
                }
            } elseif ($locationType == 'city') {
                if ($subLocationType == 1) {
                    $district_pourashava_id = $assignedLocationId;
                    $division_id = $district_id = $city_corp_id = $upazila_id = $thana_id = -1;
                } elseif ($subLocationType == 3) {
                    $city_corp_id = $assignedLocationId;
                    $division_id = $district_id = $district_pourashava_id = $upazila_id = $thana_id = -1;
                }else {
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
            $query = $query->where('permanent_division_id', $division_id);
        if ($district_id && $district_id > 0)
            $query = $query->where('permanent_district_id', $district_id);
        if ($city_corp_id && $city_corp_id > 0)
            $query = $query->where('permanent_city_corp_id', $city_corp_id);
        if ($district_pourashava_id && $district_pourashava_id > 0)
            $query = $query->where('permanent_district_pourashava_id', $district_pourashava_id);
        if ($upazila_id && $upazila_id > 0)
            $query = $query->where('permanent_upazila_id', $upazila_id);
        if ($pourashava_id && $pourashava_id > 0)
            $query = $query->where('permanent_pourashava_id', $pourashava_id);
        if ($thana_id && $thana_id > 0)
            $query = $query->where('permanent_thana_id', $thana_id);
        if ($union_id && $union_id > 0)
            $query = $query->where('permanent_union_id', $union_id);
        if ($ward_id && $ward_id > 0)
            $query = $query->where('permanent_ward_id', $ward_id);

        return $query;
    }

    /**
     * @param $query
     * @param $request
     * @return mixed
     */
    private function applyLocationFilter2($query, $request): mixed
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
                }else {
                    $query = $query->where('id', -1); // wrong location type
                }
            } elseif ($locationType == 'city') {
                if ($subLocationType == 1) {
                    $district_pourashava_id = $assignedLocationId;
                    $division_id = $district_id = $city_corp_id = $upazila_id = $thana_id = -1;
                } elseif ($subLocationType == 3) {
                    $city_corp_id = $assignedLocationId;
                    $division_id = $district_id = $district_pourashava_id = $upazila_id = $thana_id = -1;
                }else {
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
            $query = $query->where('beneficiaries.permanent_division_id', $division_id);
        if ($district_id && $district_id > 0)
            $query = $query->where('beneficiaries.permanent_district_id', $district_id);
        if ($city_corp_id && $city_corp_id > 0)
            $query = $query->where('beneficiaries.permanent_city_corp_id', $city_corp_id);
        if ($district_pourashava_id && $district_pourashava_id > 0)
            $query = $query->where('beneficiaries.permanent_district_pourashava_id', $district_pourashava_id);
        if ($upazila_id && $upazila_id > 0)
            $query = $query->where('beneficiaries.permanent_upazila_id', $upazila_id);
        if ($pourashava_id && $pourashava_id > 0)
            $query = $query->where('beneficiaries.permanent_pourashava_id', $pourashava_id);
        if ($thana_id && $thana_id > 0)
            $query = $query->where('beneficiaries.permanent_thana_id', $thana_id);
        if ($union_id && $union_id > 0)
            $query = $query->where('beneficiaries.permanent_union_id', $union_id);
        if ($ward_id && $ward_id > 0)
            $query = $query->where('beneficiaries.permanent_ward_id', $ward_id);
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

    public function replaceList(Request $request, $forPdf = false)
    {
        $program_id = $request->query('program_id');

        $perPage = $request->query('perPage', 10);
        $sortByColumn = $request->query('sortBy', 'beneficiary_replaces.created_at');
        $orderByDirection = $request->query('orderBy', 'asc');

        $query = DB::table('beneficiary_replaces')
            ->join('beneficiaries', 'beneficiaries.id', '=', 'beneficiary_replaces.beneficiary_id')
            ->join('allowance_programs', 'allowance_programs.id', '=', 'beneficiaries.program_id')
            ->join('locations AS division', 'division.id', '=', 'beneficiaries.permanent_division_id', 'left')
            ->join('locations AS district', 'district.id', '=', 'beneficiaries.permanent_district_id', 'left')
            ->join('locations AS city_corporation', 'city_corporation.id', '=', 'beneficiaries.permanent_city_corp_id', 'left')
            ->join('locations AS district_pourashava', 'district_pourashava.id', '=', 'beneficiaries.permanent_district_pourashava_id', 'left')
            ->join('locations AS upazila', 'upazila.id', '=', 'beneficiaries.permanent_upazila_id', 'left')
            ->join('beneficiaries AS replace_with_beneficiaries', 'replace_with_beneficiaries.id', '=', 'beneficiary_replaces.replace_with_ben_id')
            ->join('locations AS replace_with_division', 'replace_with_division.id', '=', 'replace_with_beneficiaries.permanent_division_id', 'left')
            ->join('locations AS replace_with_district', 'replace_with_district.id', '=', 'replace_with_beneficiaries.permanent_district_id', 'left')
            ->join('locations AS replace_with_city_corporation', 'replace_with_city_corporation.id', '=', 'replace_with_beneficiaries.permanent_city_corp_id', 'left')
            ->join('locations AS replace_with_district_pourashava', 'replace_with_district_pourashava.id', '=', 'replace_with_beneficiaries.permanent_district_pourashava_id', 'left')
            ->join('locations AS replace_with_upazila', 'replace_with_upazila.id', '=', 'replace_with_beneficiaries.permanent_upazila_id', 'left')
            ->join('lookups AS replace_cause', 'replace_cause.id', '=', 'beneficiary_replaces.cause_id', 'left');
        if ($program_id)
            $query = $query->where('beneficiaries.program_id', $program_id);

        $query = $this->applyLocationFilter2($query, $request);

        if ($forPdf)
            return $query->select('beneficiary_replaces.id',
                'replace_cause.value_en as replace_cause_en',
                'replace_cause.value_bn as replace_cause_bn',
                'beneficiary_replaces.cause_detail',
                'beneficiary_replaces.cause_date',
                'beneficiaries.application_id',
                'beneficiaries.name_en',
                'beneficiaries.name_bn',
                'beneficiaries.father_name_en',
                'beneficiaries.father_name_bn',
                'beneficiaries.mother_name_en',
                'beneficiaries.mother_name_bn',
                'allowance_programs.name_en as program_name_en',
                'allowance_programs.name_bn as program_name_bn',
                'division.name_en as division_name_en',
                'division.name_bn as division_name_bn',
                'district.name_en as district_name_en',
                'district.name_bn as district_name_bn',
                'city_corporation.name_en as city_corporation_name_en',
                'city_corporation.name_bn as city_corporation_name_bn',
                'district_pourashava.name_en as district_pourashava_name_en',
                'district_pourashava.name_bn as district_pourashava_name_bn',
                'upazila.name_en as upazila_name_en',
                'upazila.name_bn as upazila_name_bn',
                'replace_with_beneficiaries.application_id AS replace_with_application_id',
                'replace_with_beneficiaries.name_en AS replace_with_name_en',
                'replace_with_beneficiaries.name_bn AS replace_with_name_bn',
                'replace_with_beneficiaries.father_name_en AS replace_with_father_name_en',
                'replace_with_beneficiaries.father_name_bn AS replace_with_father_name_bn',
                'replace_with_beneficiaries.mother_name_en AS replace_with_mother_name_en',
                'replace_with_beneficiaries.mother_name_bn AS replace_with_mother_name_bn',
                'replace_with_division.name_en as replace_with_division_name_en',
                'replace_with_division.name_bn as replace_with_division_name_bn',
                'replace_with_district.name_en as replace_with_district_name_en',
                'replace_with_district.name_bn as replace_with_district_name_bn',
                'replace_with_city_corporation.name_en as replace_with_city_corporation_name_en',
                'replace_with_city_corporation.name_bn as replace_with_city_corporation_name_bn',
                'replace_with_district_pourashava.name_en as replace_with_district_pourashava_name_en',
                'replace_with_district_pourashava.name_bn as replace_with_district_pourashava_name_bn',
                'replace_with_upazila.name_en as replace_with_upazila_name_en',
                'replace_with_upazila.name_bn as replace_with_upazila_name_bn')->orderBy("$sortByColumn", "$orderByDirection")->get();
        else
            return $query->select('beneficiary_replaces.id',
                'replace_cause.value_en as replace_cause_en',
                'replace_cause.value_bn as replace_cause_bn',
                'beneficiary_replaces.cause_detail',
                'beneficiary_replaces.cause_date',
                'beneficiaries.application_id',
                'beneficiaries.name_en',
                'beneficiaries.name_bn',
                'beneficiaries.father_name_en',
                'beneficiaries.father_name_bn',
                'beneficiaries.mother_name_en',
                'beneficiaries.mother_name_bn',
                'allowance_programs.name_en as program_name_en',
                'allowance_programs.name_bn as program_name_bn',
                'division.name_en as division_name_en',
                'division.name_bn as division_name_bn',
                'district.name_en as district_name_en',
                'district.name_bn as district_name_bn',
                'city_corporation.name_en as city_corporation_name_en',
                'city_corporation.name_bn as city_corporation_name_bn',
                'district_pourashava.name_en as district_pourashava_name_en',
                'district_pourashava.name_bn as district_pourashava_name_bn',
                'upazila.name_en as upazila_name_en',
                'upazila.name_bn as upazila_name_bn',
                'replace_with_beneficiaries.application_id AS replace_with_application_id',
                'replace_with_beneficiaries.name_en AS replace_with_name_en',
                'replace_with_beneficiaries.name_bn AS replace_with_name_bn',
                'replace_with_beneficiaries.father_name_en AS replace_with_father_name_en',
                'replace_with_beneficiaries.father_name_bn AS replace_with_father_name_bn',
                'replace_with_beneficiaries.mother_name_en AS replace_with_mother_name_en',
                'replace_with_beneficiaries.mother_name_bn AS replace_with_mother_name_bn',
                'replace_with_division.name_en as replace_with_division_name_en',
                'replace_with_division.name_bn as replace_with_division_name_bn',
                'replace_with_district.name_en as replace_with_district_name_en',
                'replace_with_district.name_bn as replace_with_district_name_bn',
                'replace_with_city_corporation.name_en as replace_with_city_corporation_name_en',
                'replace_with_city_corporation.name_bn as replace_with_city_corporation_name_bn',
                'replace_with_district_pourashava.name_en as replace_with_district_pourashava_name_en',
                'replace_with_district_pourashava.name_bn as replace_with_district_pourashava_name_bn',
                'replace_with_upazila.name_en as replace_with_upazila_name_en',
                'replace_with_upazila.name_bn as replace_with_upazila_name_bn')->orderBy("$sortByColumn", "$orderByDirection")->paginate($perPage);
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
     * @param $forPdf
     * @return mixed
     */
    public function exitList(Request $request, $forPdf = false)
    {
        $program_id = $request->query('program_id');

        $perPage = $request->query('perPage', 10);
        $sortByColumn = $request->query('sortBy', 'beneficiary_exits.created_at');
        $orderByDirection = $request->query('orderBy', 'asc');

        $query = DB::table('beneficiary_exits')
            ->join('beneficiaries', 'beneficiaries.id', '=', 'beneficiary_exits.beneficiary_id')
            ->join('allowance_programs', 'allowance_programs.id', '=', 'beneficiaries.program_id')
            ->join('locations AS division', 'division.id', '=', 'beneficiaries.permanent_division_id', 'left')
            ->join('locations AS district', 'district.id', '=', 'beneficiaries.permanent_district_id', 'left')
            ->join('locations AS city_corporation', 'city_corporation.id', '=', 'beneficiaries.permanent_city_corp_id', 'left')
            ->join('locations AS district_pourashava', 'district_pourashava.id', '=', 'beneficiaries.permanent_district_pourashava_id', 'left')
            ->join('locations AS upazila', 'upazila.id', '=', 'beneficiaries.permanent_upazila_id', 'left')
            ->join('locations AS pourashava', 'pourashava.id', '=', 'beneficiaries.permanent_pourashava_id', 'left')
            ->join('locations AS thana', 'thana.id', '=', 'beneficiaries.permanent_thana_id', 'left')
            ->join('locations AS union', 'union.id', '=', 'beneficiaries.permanent_union_id', 'left')
            ->join('locations AS ward', 'ward.id', '=', 'beneficiaries.permanent_ward_id', 'left')
            ->join('lookups AS exit_reason', 'exit_reason.id', '=', 'beneficiary_exits.exit_reason_id', 'left');
        if ($program_id)
            $query = $query->where('beneficiaries.program_id', $program_id);

        $query = $this->applyLocationFilter2($query, $request);

        if ($forPdf)
            return $query->select('beneficiary_exits.id',
                'exit_reason.value_en as exit_reason_en',
                'exit_reason.value_bn as exit_reason_bn',
                'beneficiary_exits.exit_reason_detail',
                'beneficiary_exits.exit_date',
                'beneficiaries.application_id',
                'beneficiaries.name_en',
                'beneficiaries.name_bn',
                'beneficiaries.father_name_en',
                'beneficiaries.father_name_bn',
                'beneficiaries.mother_name_en',
                'beneficiaries.mother_name_bn',
                'allowance_programs.name_en as program_name_en',
                'allowance_programs.name_bn as program_name_bn',
                'division.name_en as division_name_en',
                'division.name_bn as division_name_bn',
                'district.name_en as district_name_en',
                'district.name_bn as district_name_bn',
                'city_corporation.name_en as city_corporation_name_en',
                'city_corporation.name_bn as city_corporation_name_bn',
                'district_pourashava.name_en as district_pourashava_name_en',
                'district_pourashava.name_bn as district_pourashava_name_bn',
                'upazila.name_en as upazila_name_en',
                'upazila.name_bn as upazila_name_bn',
                'pourashava.name_en as pourashava_name_en',
                'pourashava.name_bn as pourashava_name_bn',
                'thana.name_en as thana_en',
                'thana.name_bn as thana_bn',
                'union.name_en as union_en',
                'union.name_bn as union_bn',
                'ward.name_en as ward_en',
                'ward.name_bn as ward_bn')->orderBy("$sortByColumn", "$orderByDirection")->get();
        else
            return $query->select('beneficiary_exits.id',
                'exit_reason.value_en as exit_reason_en',
                'exit_reason.value_bn as exit_reason_bn',
                'beneficiary_exits.exit_reason_detail',
                'beneficiary_exits.exit_date',
                'beneficiaries.application_id',
                'beneficiaries.name_en',
                'beneficiaries.name_bn',
                'beneficiaries.father_name_en',
                'beneficiaries.father_name_bn',
                'beneficiaries.mother_name_en',
                'beneficiaries.mother_name_bn',
                'allowance_programs.name_en as program_name_en',
                'allowance_programs.name_bn as program_name_bn',
                'division.name_en as division_name_en',
                'division.name_bn as division_name_bn',
                'district.name_en as district_name_en',
                'district.name_bn as district_name_bn',
                'city_corporation.name_en as city_corporation_name_en',
                'city_corporation.name_bn as city_corporation_name_bn',
                'district_pourashava.name_en as district_pourashava_name_en',
                'district_pourashava.name_bn as district_pourashava_name_bn',
                'upazila.name_en as upazila_name_en',
                'upazila.name_bn as upazila_name_bn',
                'pourashava.name_en as pourashava_name_en',
                'pourashava.name_bn as pourashava_name_bn',
                'thana.name_en as thana_en',
                'thana.name_bn as thana_bn',
                'union.name_en as union_en',
                'union.name_bn as union_bn',
                'ward.name_en as ward_en',
                'ward.name_bn as ward_bn')->orderBy("$sortByColumn", "$orderByDirection")->paginate($perPage);
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

    public function shiftingList(Request $request, $forPdf = false)
    {
        $from_program_id = $request->query('from_program_id');
        $to_program_id = $request->query('to_program_id');

        $perPage = $request->query('perPage', 10);
        $sortByColumn = $request->query('sortBy', 'beneficiary_shiftings.created_at');
        $orderByDirection = $request->query('orderBy', 'asc');

        $query = DB::table('beneficiary_shiftings')
            ->join('beneficiaries', 'beneficiaries.id', '=', 'beneficiary_shiftings.beneficiary_id')
            ->join('allowance_programs AS from_program', 'from_program.id', '=', 'beneficiary_shiftings.from_program_id')
            ->join('allowance_programs AS to_program', 'to_program.id', '=', 'beneficiary_shiftings.to_program_id')
            ->join('locations AS division', 'division.id', '=', 'beneficiaries.permanent_division_id', 'left')
            ->join('locations AS district', 'district.id', '=', 'beneficiaries.permanent_district_id', 'left')
            ->join('locations AS city_corporation', 'city_corporation.id', '=', 'beneficiaries.permanent_city_corp_id', 'left')
            ->join('locations AS district_pourashava', 'district_pourashava.id', '=', 'beneficiaries.permanent_district_pourashava_id', 'left')
            ->join('locations AS upazila', 'upazila.id', '=', 'beneficiaries.permanent_upazila_id', 'left')
            ->join('locations AS pourashava', 'pourashava.id', '=', 'beneficiaries.permanent_pourashava_id', 'left')
            ->join('locations AS thana', 'thana.id', '=', 'beneficiaries.permanent_thana_id', 'left')
            ->join('locations AS union', 'union.id', '=', 'beneficiaries.permanent_union_id', 'left')
            ->join('locations AS ward', 'ward.id', '=', 'beneficiaries.permanent_ward_id', 'left');
        if ($from_program_id)
            $query = $query->where('beneficiary_shiftings.from_program_id', $from_program_id);
        if ($to_program_id)
            $query = $query->where('beneficiary_shiftings.to_program_id', $to_program_id);

        $query = $this->applyLocationFilter2($query, $request);

        if ($forPdf)
            return $query->select('beneficiary_shiftings.id',
                'beneficiary_shiftings.shifting_cause',
                'beneficiary_shiftings.activation_date',
                'beneficiaries.application_id',
                'beneficiaries.name_en',
                'beneficiaries.name_bn',
                'beneficiaries.father_name_en',
                'beneficiaries.father_name_bn',
                'beneficiaries.mother_name_en',
                'beneficiaries.mother_name_bn',
                'from_program.name_en as from_program_name_en',
                'from_program.name_bn as from_program_name_bn',
                'to_program.name_en as to_program_name_en',
                'to_program.name_bn as to_program_name_bn',
                'division.name_en as division_name_en',
                'division.name_bn as division_name_bn',
                'district.name_en as district_name_en',
                'district.name_bn as district_name_bn',
                'city_corporation.name_en as city_corporation_name_en',
                'city_corporation.name_bn as city_corporation_name_bn',
                'district_pourashava.name_en as district_pourashava_name_en',
                'district_pourashava.name_bn as district_pourashava_name_bn',
                'upazila.name_en as upazila_name_en',
                'upazila.name_bn as upazila_name_bn',
                'pourashava.name_en as pourashava_name_en',
                'pourashava.name_bn as pourashava_name_bn',
                'thana.name_en as thana_en',
                'thana.name_bn as thana_bn',
                'union.name_en as union_en',
                'union.name_bn as union_bn',
                'ward.name_en as ward_en',
                'ward.name_bn as ward_bn')->orderBy("$sortByColumn", "$orderByDirection")->get();
        else
            return $query->select('beneficiary_shiftings.id',
                'beneficiary_shiftings.shifting_cause',
                'beneficiary_shiftings.activation_date',
                'beneficiaries.application_id',
                'beneficiaries.name_en',
                'beneficiaries.name_bn',
                'beneficiaries.father_name_en',
                'beneficiaries.father_name_bn',
                'beneficiaries.mother_name_en',
                'beneficiaries.mother_name_bn',
                'from_program.name_en as from_program_name_en',
                'from_program.name_bn as from_program_name_bn',
                'to_program.name_en as to_program_name_en',
                'to_program.name_bn as to_program_name_bn',
                'division.name_en as division_name_en',
                'division.name_bn as division_name_bn',
                'district.name_en as district_name_en',
                'district.name_bn as district_name_bn',
                'city_corporation.name_en as city_corporation_name_en',
                'city_corporation.name_bn as city_corporation_name_bn',
                'district_pourashava.name_en as district_pourashava_name_en',
                'district_pourashava.name_bn as district_pourashava_name_bn',
                'upazila.name_en as upazila_name_en',
                'upazila.name_bn as upazila_name_bn',
                'pourashava.name_en as pourashava_name_en',
                'pourashava.name_bn as pourashava_name_bn',
                'thana.name_en as thana_en',
                'thana.name_bn as thana_bn',
                'union.name_en as union_en',
                'union.name_bn as union_bn',
                'ward.name_en as ward_en',
                'ward.name_bn as ward_bn')->orderBy("$sortByColumn", "$orderByDirection")->paginate($perPage);
    }
}
