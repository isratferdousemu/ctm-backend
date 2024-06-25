<?php

namespace App\Http\Services\Admin\BudgetAllotment;

use App\Models\Allotment;
use App\Models\BudgetDetail;
use App\Models\FinancialYear;
use Illuminate\Http\Request;


class DashboardService
{
    /**
     * @return FinancialYear
     */
    public function startingFinancialYear()
    {
        return FinancialYear::query()
            ->join('budgets', 'budgets.financial_year_id', '=', 'financial_years.id')
            ->select('financial_years.*')
            ->orderBy('financial_years.start_date')
            ->first();
    }

    /**
     * @return FinancialYear|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function currentFinancialYear()
    {
        return FinancialYear::query()->where('status', 1)->first();
    }

    /**
     * @return int|mixed
     */
    public function totalBudgetAmount()
    {
        return BudgetDetail::query()->sum('total_amount');
    }

    /**
     * @param $current_financial_year_id
     * @return int|mixed
     */
    public function currentBudgetAmount($current_financial_year_id)
    {
        return BudgetDetail::query()
            ->join('budgets', 'budget_details.budget_id', '=', 'budgets.id')
            ->where('budgets.financial_year_id', $current_financial_year_id)
            ->sum('budget_details.total_amount');
    }

    /**
     * @param $current_financial_year_id
     * @return int|mixed
     */
    public function currentTotalBeneficiaries($current_financial_year_id)
    {
        return BudgetDetail::query()
            ->join('budgets', 'budget_details.budget_id', '=', 'budgets.id')
            ->where('budgets.financial_year_id', $current_financial_year_id)
            ->sum('budget_details.total_beneficiaries');
    }

    /**
     * @return int|mixed
     */
    public function totalAllotmentAmount()
    {
        return Allotment::query()->sum('total_amount');
    }

    /**
     * @param $current_financial_year_id
     * @return int|mixed
     */
    public function currentAllotmentAmount($current_financial_year_id)
    {
        return Allotment::query()
            ->where('financial_year_id', $current_financial_year_id)
            ->sum('total_amount');
    }

    public function totalBudget(Request $request)
    {
        $financial_year_ids = $request->has('financial_year_ids') ? explode(',', $request->query('financial_year_ids')) : [];
        $program_ids = $request->has('program_ids') ? explode(',', $request->query('program_ids')) : [];
        $location_ids = $request->has('location_ids') ? explode(',', $request->query('location_ids')) : [];
        $query = BudgetDetail::query()
            ->join('budgets', 'budget_details.budget_id', '=', 'budgets.id')
            ->join('financial_years', 'financial_years.id', '=', 'budgets.financial_year_id');
        if (count($financial_year_ids) > 0) {
            $query->whereIn('budgets.financial_year_id', $financial_year_ids);
        }
        if (count($program_ids) > 0) {
            $query->whereIn('budgets.program_id', $program_ids);
        }
        if (count($location_ids) > 0) {
            $query->whereIn('budget_details.division_id', $location_ids);
        }
        return $query->selectRaw('financial_years.financial_year, sum(budget_details.total_amount) as total_amount')
            ->groupBy('financial_years.financial_year')
            ->orderBy('financial_years.financial_year', 'desc')
            ->limit(10)
            ->get();
    }

    public function totalAllotment(Request $request)
    {
        $financial_year_ids = $request->has('financial_year_ids') ? explode(',', $request->query('financial_year_ids')) : [];
        $program_ids = $request->has('program_ids') ? explode(',', $request->query('program_ids')) : [];
        $location_ids = $request->has('location_ids') ? explode(',', $request->query('location_ids')) : [];
        $query = Allotment::query()
            ->join('financial_years', 'financial_years.id', '=', 'allotments.financial_year_id');
        if (count($financial_year_ids) > 0) {
            $query->whereIn('allotments.financial_year_id', $financial_year_ids);
        }
        if (count($program_ids) > 0) {
            $query->whereIn('allotments.program_id', $program_ids);
        }
        if (count($location_ids) > 0) {
            $query->whereIn('allotments.division_id', $location_ids);
        }
        return $query->selectRaw('financial_years.financial_year, sum(allotments.total_amount) as total_amount')
            ->groupBy('financial_years.financial_year')
            ->orderBy('financial_years.financial_year', 'desc')
            ->limit(10)
            ->get();
    }
}
