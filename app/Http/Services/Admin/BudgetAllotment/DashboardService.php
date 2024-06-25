<?php

namespace App\Http\Services\Admin\BudgetAllotment;

use App\Models\Allotment;
use App\Models\BudgetDetail;
use App\Models\FinancialYear;

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
}
