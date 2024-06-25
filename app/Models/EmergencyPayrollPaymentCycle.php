<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EmergencyPayrollPaymentCycle extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Get all of the CycleDetails for the EmergencyPayrollPaymentCycle
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function CycleDetails(): HasMany
    {
        return $this->hasMany(EmergencyPayrollPaymentCycleDetails::class, 'emergency_cycle_id', 'id');
    }




}
