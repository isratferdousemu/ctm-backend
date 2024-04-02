<?php

namespace App\Http\Services\Admin\BudgetAllotment;


use App\Http\Requests\Admin\Budget\StoreBudgetRequest;
use App\Http\Requests\Admin\Budget\UpdateBudgetRequest;
use App\Models\Budget;
use Illuminate\Http\Request;


class BudgetService
{
    public function save(StoreBudgetRequest $request): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|bool|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $validated = $request->validated();
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
        return $budget->save();
    }

    public function delete($id)
    {
        $budget = Budget::findOrFail($id);
        return $budget->delete();
    }

    public function getProjection(Request $request)
    {
        return null;
    }

}
