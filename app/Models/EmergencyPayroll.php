<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyPayroll extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Get the FinancialYear that owns the EmergencyPayrollPaymentCycle
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function FinancialYear(): BelongsTo
    {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id', 'id');
    }

    /**
     * Get the installment that owns the EmergencyPayrollPaymentCycle
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function installment(): BelongsTo
    {
        return $this->belongsTo(PayrollInstallmentSchedule::class, 'installment_schedule_id', 'id');
    }
}
