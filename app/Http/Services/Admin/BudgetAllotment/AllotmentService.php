<?php

namespace App\Http\Services\Admin\BudgetAllotment;


use App\Http\Requests\Admin\Allotment\StoreAllotmentRequest;
use App\Http\Requests\Admin\Allotment\UpdateAllotmentRequest;
use App\Models\Allotment;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Allotment Service
 */
class AllotmentService
{
    public function officeList()
    {
        $location_id = \request()->query('locatioln_id');

    }
    public function getLocationChainById($location_id)
    {
        $location_ids = [];

    }
    public function save(StoreAllotmentRequest $request): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|bool|\Illuminate\Database\Eloquent\Builder|array|null
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated(['program_id', 'division_id', 'district_id', 'financial_year_id']);
            $allotment = Allotment::create($validated);
            $validatedDetail = $request->validated('allotmentDetails');
            if ($validatedDetail)
                $allotment->allotmentDetails()->createMany($validatedDetail);
            DB::commit();
            return $allotment;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function list(Request $request, $getAllRecords = false)
    {
        $program_id = $request->query('program_id');
        $financial_year_id = $request->query('financial_year_id');
        $division_id = $request->query('division_id');
        $district_id = $request->query('district_id');
        $perPage = $request->query('perPage', 10);
        $sortByColumn = $request->query('sortBy', 'created_at');
        $orderByDirection = $request->query('orderBy', 'asc');

        $query = Allotment::query();
        if ($program_id)
            $query = $query->where('program_id', $program_id);

        if ($financial_year_id)
            $query = $query->where('financial_year_id', $financial_year_id);

        if ($division_id)
            $query = $query->where('division_id', $division_id);

        if ($district_id)
            $query = $query->where('district_id', $district_id);

        if ($getAllRecords)
            return $query->with('program',
                'location',
                'financialYear')
                ->orderBy("$sortByColumn", "$orderByDirection")
                ->get();
        else
            return $query->with('program',
                'location',
                'financialYear',
                'allotmentDetails')
                ->orderBy("$sortByColumn", "$orderByDirection")
                ->paginate($perPage);

    }

    public function get($id)
    {
        return Allotment::with('program', 'location', 'financialYear', 'allotmentDetails')->find($id);
    }

    public function update(UpdateAllotmentRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $allotment = Allotment::findOrFail($id);
            $validated = $request->validated(['program_id', 'division_id', 'district_id', 'financial_year_id']);
            $allotment->fill($validated);
            $allotment->save();
            $allotment->allotmentDetails()->delete();
            $validatedDetail = $request->validated('allotmentDetails');
            if ($validatedDetail)
                $allotment->allotmentDetails()->createMany($validatedDetail);
            DB::commit();
            return $allotment;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $allotment = Allotment::findOrFail($id);
            $allotment->allotmentDetails()->delete();
            $resp = $allotment->delete();
            DB::commit();
            return $resp;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

}
