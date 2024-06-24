<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyBeneficiary extends Model
{
    use HasFactory;
    protected $guarded=['id'];
    protected $table='emergency_beneficiaries';

    public function emergencyAllotment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(EmergencyAllotment::class, 'allotment_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gender()
    {
        return $this->belongsTo(Lookup::class, 'gender_id');
    }

    /**
     * Get the program that owns the EmergencyBeneficiary
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(AllowanceProgram::class, 'program_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentDivision()
    {
        return $this->belongsTo(Location::class, 'current_division_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentDistrict()
    {
        return $this->belongsTo(Location::class, 'current_district_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentCityCorporation()
    {
        return $this->belongsTo(Location::class, 'current_city_corp_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentDistrictPourashava()
    {
        return $this->belongsTo(Location::class, 'current_district_pourashava_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentUpazila()
    {
        return $this->belongsTo(Location::class, 'current_upazila_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentPourashava()
    {
        return $this->belongsTo(Location::class, 'current_pourashava_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentThana()
    {
        return $this->belongsTo(Location::class, 'current_thana_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentUnion()
    {
        return $this->belongsTo(Location::class, 'current_union_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentWard()
    {
        return $this->belongsTo(Location::class, 'current_ward_id', 'id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permanentDivision()
    {
        return $this->belongsTo(Location::class, 'permanent_division_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permanentDistrict()
    {
        return $this->belongsTo(Location::class, 'permanent_district_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permanentCityCorporation()
    {
        return $this->belongsTo(Location::class, 'permanent_city_corp_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permanentDistrictPourashava()
    {
        return $this->belongsTo(Location::class, 'permanent_district_pourashava_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permanentUpazila()
    {
        return $this->belongsTo(Location::class, 'permanent_upazila_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permanentPourashava()
    {
        return $this->belongsTo(Location::class, 'permanent_pourashava_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permanentThana()
    {
        return $this->belongsTo(Location::class, 'permanent_thana_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permanentUnion()
    {
        return $this->belongsTo(Location::class, 'permanent_union_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permanentWard()
    {
        return $this->belongsTo(Location::class, 'permanent_ward_id', 'id');
    }
}
