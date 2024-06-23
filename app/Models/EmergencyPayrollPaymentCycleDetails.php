<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyPayrollPaymentCycleDetails extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Get the EmergencyBeneficiary that owns the EmergencyPayrollPaymentCycleDetails
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function EmergencyBeneficiary(): BelongsTo
    {
        return $this->belongsTo(EmergencyBeneficiary::class, 'emergency_beneficiary_id', 'id');
    }

    public function EmergencyPayroll(): BelongsTo
    {
        return $this->belongsTo(EmergencyPayroll::class, 'emergency_payroll_id', 'id');
    }
}
