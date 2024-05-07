<?php

namespace App\Http\Services\Admin\BudgetAllotment;


use App\Http\Requests\Admin\Budget\StoreBudgetRequest;
use App\Http\Requests\Admin\Budget\UpdateBudgetRequest;
use App\Models\AllowanceProgram;
use App\Models\Beneficiary;
use App\Models\Budget;
use App\Models\FinancialYear;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BudgetService
{
    public function save(StoreBudgetRequest $request): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|bool|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $budget_id = mt_rand(100000, 999999);
        $validated = $request->safe()->merge(['budget_id' => $budget_id])->only(['budget_id', 'program_id', 'financial_year_id', 'calculation_type', 'previous_year_value', 'calculation_value', 'remarks']);
        return Budget::create($validated);
    }

    public function list(Request $request, $getAllRecords = false)
    {
        $program_id = $request->query('program_id');
        $financial_year_id = $request->query('financial_year_id');
        $perPage = $request->query('perPage', 10);
        $sortByColumn = $request->query('sortBy', 'created_at');
        $orderByDirection = $request->query('orderBy', 'asc');

        $query = Budget::query();
        if ($program_id)
            $query = $query->where('program_id', $program_id);

        if ($financial_year_id)
            $query = $query->where('financial_year_id', $financial_year_id);

        if ($getAllRecords)
            return $query->with('program',
                'calculationType',
                'financialYear')
                ->orderBy("$sortByColumn", "$orderByDirection")
                ->get();
        else
            return $query->with('program',
                'calculationType',
                'financialYear')
                ->orderBy("$sortByColumn", "$orderByDirection")
                ->paginate($perPage);

    }

    public function get($id)
    {
        return Budget::with('program', 'calculationType', 'financialYear')->find($id);
    }

    public function update(UpdateBudgetRequest $request, $id)
    {
        $budget = Budget::findOrFail($id);
        $validated = $request->validated();
        $budget->fill($validated);
        $budget->save();
        return $budget;
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $budget = Budget::findOrFail($id);
            $budget->budgetDetail()->delete();
            $budget->delete();
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function getProjection(Request $request, $program_id, $financial_year_id): array
    {
        $location_id = $request->query('location_id');
        $location = $location_id ? Location::findOrFail($location_id) : null;
        $program = AllowanceProgram::findOrFail($program_id);
        $query = Beneficiary::query()->where('program_id', $program_id)->where('financial_year_id', $financial_year_id);
//        if ($location_id)
//            $query = $query->where('program_id', $program_id);
        $coming_year_total_ben = $query->count();
        $coming_year_total_amount = 100;

        $previous_financial_year = FinancialYear::whereRaw(
            "start_date < (
                SELECT
                    fy2.start_date
                from
                    financial_years fy2
                WHERE
                    fy2.id = $financial_year_id)")
            ->orderBy('start_date', 'desc')
            ->first();
        $query2 = Beneficiary::query()->where('program_id', $program_id)->where('financial_year_id', $previous_financial_year?->id);
//        if ($location_id)
//            $query = $query->where('program_id', $program_id);
        $previous_year_total_ben = $query2->count();
        $previous_year_total_amount = 90;
        return [
            'location' => $location,
            'program' => $program,
            'previous_year_total_ben' => $previous_year_total_ben,
            'previous_year_total_amount' => $previous_year_total_amount,
            'coming_year_total_ben' => $coming_year_total_ben,
            'coming_year_total_amount' => $coming_year_total_amount
        ];
    }

    public function getProjection2(Request $request, $program_id, $financial_year_id): array
    {
        return [
            'location' => $financial_year_id,
            'program' => $program_id,
            'previous_year_total_ben' => 10,
            'previous_year_total_amount' => 20,
            'coming_year_total_ben' => 30,
            'coming_year_total_amount' => 60
        ];
    }
}
