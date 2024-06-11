<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollPaymentCycleDetail extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payrollPaymentCycle()
    {
        return $this->belongsTo(PayrollPaymentCycle::class, 'payroll_payment_cycle_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }
    public function beneficiary(){
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }
   
}