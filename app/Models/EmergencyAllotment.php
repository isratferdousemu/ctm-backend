<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmergencyAllotment extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'emergency_allotments';

    public $primaryKey = 'id';

    public $timestamps = true;

    protected $guarded = ['id'];

    public function program()
    {
        return $this->belongsTo(AllowanceProgram::class, 'program_id');
    }


    public function division()
    {
        return $this->belongsTo(Location::class, 'division_id', 'id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(Location::class, 'district_id', 'id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function upazila()
    {
        return $this->belongsTo(Location::class, 'upazila_id', 'id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cityCorporation()
    {
        return $this->belongsTo(Location::class, 'city_corp_id', 'id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function districtPourosova()
    {
        return $this->belongsTo(Location::class, 'district_pourashava_id', 'id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
}
