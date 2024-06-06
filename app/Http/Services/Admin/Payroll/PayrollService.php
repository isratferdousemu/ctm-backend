<?php

namespace App\Http\Services\Admin\Payroll;

use App\Http\Requests\Admin\Payroll\SavePayrollRequest;
use App\Models\Allotment;
use App\Models\Beneficiary;
use App\Models\Payroll;
use App\Models\PayrollInstallmentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    /**
     * @param $program_id
     * @param $financial_year_id
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getActiveInstallments($program_id, $financial_year_id): \Illuminate\Database\Eloquent\Collection|array
    {
        return PayrollInstallmentSetting::query()
            ->join('payroll_installment_schedules', 'payroll_installment_schedules.id', '=', 'payroll_installment_settings.installment_schedule_id')
            ->select('payroll_installment_schedules.*')
            ->where('payroll_installment_settings.program_id', $program_id)
            ->where('payroll_installment_settings.financial_year_id', $financial_year_id)
            ->orderBy('payroll_installment_schedules.installment_name')
            ->get();
    }

    /**
     * @param Request $request
     * @param bool $getAllRecords
     * @return mixed
     */
    public function getAllotmentAreaList(Request $request): mixed
    {
        $program_id = $request->query('program_id');
        $financial_year_id = $request->query('financial_year_id');
        $perPage = $request->query('perPage', 100);

        $query = Allotment::query()
            ->leftJoin('payrolls', 'allotments.id', '=', 'payrolls.allotment_id');
        $query = $query->where(function ($query) {
            return $query->where('payrolls.is_submitted', 0)
                ->orWhere('payrolls.is_submitted', null);
        });
        if ($program_id)
            $query = $query->where('allotments.program_id', $program_id);

        if ($financial_year_id)
            $query = $query->where('allotments.financial_year_id', $financial_year_id);

        $query = $this->applyLocationFilter($query, $request);

        $query = $query
            ->selectRaw('allotments.*, payrolls.allotment_id')
            ->with('upazila', 'cityCorporation', 'districtPourosova', 'location');
        return $query->orderBy('location_id')->paginate($perPage)->through(function ($allotmentArea) {
            $allotmentArea->active_beneficiaries = $this->countActiveBeneficiaries($allotmentArea);
            return $allotmentArea;
        });
    }

    /**
     * @param Allotment $allotmentArea
     * @return int
     */
    private function countActiveBeneficiaries(Allotment $allotmentArea): int
    {
        $query = Beneficiary::query();
        $query = $query->where('program_id', $allotmentArea->program_id)
            ->where('financial_year_id', $allotmentArea->financial_year_id);
//            ->where('status', 1);
        if ($allotmentArea->city_corp_id)
            $query = $query->where('permanent_city_corp_id', $allotmentArea->city_corp_id);
        if ($allotmentArea->district_pourashava_id)
            $query = $query->where('permanent_district_pourashava_id', $allotmentArea->district_pourashava_id);
        if ($allotmentArea->upazila_id)
            $query = $query->where('permanent_upazila_id', $allotmentArea->upazila_id);
        if ($allotmentArea->pourashava_id)
            $query = $query->where('permanent_pourashava_id', $allotmentArea->pourashava_id);
        if ($allotmentArea->thana_id)
            $query = $query->where('permanent_thana_id', $allotmentArea->thana_id);
        if ($allotmentArea->union_id)
            $query = $query->where('permanent_union_id', $allotmentArea->union_id);
        if ($allotmentArea->ward_id)
            $query = $query->where('permanent_ward_id', $allotmentArea->ward_id);

        return $query->count();
    }

    /**
     * @param Request $request
     * @param int $allotment_id
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getActiveBeneficiaries(Request $request, int $allotment_id): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $allotmentArea = Allotment::findOrfail($allotment_id);
        $perPage = $request->query('perPage', 100);
        $query = Beneficiary::query();
        $query = $query->where('program_id', $allotmentArea->program_id)
            ->where('financial_year_id', $allotmentArea->financial_year_id)
            ->where('status', 1);
        if ($allotmentArea->city_corp_id)
            $query = $query->where('permanent_city_corp_id', $allotmentArea->city_corp_id);
        if ($allotmentArea->district_pourashava_id)
            $query = $query->where('permanent_district_pourashava_id', $allotmentArea->district_pourashava_id);
        if ($allotmentArea->upazila_id)
            $query = $query->where('permanent_upazila_id', $allotmentArea->upazila_id);
        if ($allotmentArea->pourashava_id)
            $query = $query->where('permanent_pourashava_id', $allotmentArea->pourashava_id);
        if ($allotmentArea->thana_id)
            $query = $query->where('permanent_thana_id', $allotmentArea->thana_id);
        if ($allotmentArea->union_id)
            $query = $query->where('permanent_union_id', $allotmentArea->union_id);
        if ($allotmentArea->ward_id)
            $query = $query->where('permanent_ward_id', $allotmentArea->ward_id);

        return $query->with('permanentUpazila', 'permanentCityCorporation', 'permanentDistrictPourashava', 'permanentUnion', 'permanentPourashava', 'permanentWard')->paginate($perPage);
    }

    /**
     * @param SavePayrollRequest $request
     * @return mixed
     * @throws \Throwable
     */
    public function setBeneficiaries(SavePayrollRequest $request): mixed
    {
        DB::beginTransaction();
        try {
            $allotment = Allotment::findOrFail($request->post('allotment_id'));
            $validatedPayrollData = $request->safe()->merge([
                'total_beneficiaries' => $allotment->total_beneficiaries,
                'sub_total_amount' => 1,
                'total_charge' => 1,
                'total_amount' => 1])
                ->only([
                    'program_id',
                    'financial_year_id',
                    'office_id',
                    'allotment_id',
                    'installment_schedule_id']);
            $payroll = Payroll::create($validatedPayrollData);

            $validatedPayrollDetailsData = $request->validated('payroll_details');
            if ($validatedPayrollDetailsData) {
                collect($validatedPayrollDetailsData)->map(function ($data) {
//                    $data['charge'=>1,'total_amount'=>$data['amount']];
//                    $data->total_amount = $data->charge + $data->amount;
                    return $data;
                })->toArray();
                $payroll->payrollDeails()->createMany($validatedPayrollDetailsData);
            }
            DB::commit();
            return $payroll;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
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
            $query = $query->where('allotments.division_id', $division_id);
        if ($district_id && $district_id > 0)
            $query = $query->where('allotments.district_id', $district_id);
        if ($city_corp_id && $city_corp_id > 0)
            $query = $query->where('allotments.city_corp_id', $city_corp_id);
        if ($district_pourashava_id && $district_pourashava_id > 0)
            $query = $query->where('allotments.district_pourashava_id', $district_pourashava_id);
        if ($upazila_id && $upazila_id > 0)
            $query = $query->where('allotments.upazila_id', $upazila_id);
        if ($pourashava_id && $pourashava_id > 0)
            $query = $query->where('allotments.pourashava_id', $pourashava_id);
        if ($thana_id && $thana_id > 0)
            $query = $query->where('allotments.thana_id', $thana_id);
        if ($union_id && $union_id > 0)
            $query = $query->where('allotments.union_id', $union_id);
        if ($ward_id && $ward_id > 0)
            $query = $query->where('allotments.ward_id', $ward_id);

        return $query;
    }

}
