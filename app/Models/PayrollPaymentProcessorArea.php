<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollPaymentProcessorArea extends Model
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
     * Get the division that owns the PayrollPaymentProcessorArea
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'division_id', 'id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'district_id', 'id');
    }

    public function upazila(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'upazila_id', 'id');
    }

    public function union(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'union_id', 'id');
    }

    public function thana(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'thana_id', 'id');
    }
    public function CityCorporation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'city_corp_id', 'id');
    }

    public function DistrictPourashava(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'district_pourashava_id', 'id');
    }

    public function LocationType(): BelongsTo
    {
        return $this->belongsTo(Lookup::class, 'location_type', 'id');
    }
}
