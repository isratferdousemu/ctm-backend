<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AllowanceProgram
 *
 * @property int $id
 * @property string $code
 * @property string $name_en
 * @property string $name_bn
 * @property string|null $guideline
 * @property int $service_type
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram query()
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereGuideline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereNameBn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereServiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereUpdatedAt($value)
 * @property string $nameEn
 * @property string $nameBn
 * @property int $serviceType
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property int $version
 * @property-read \App\Models\Lookup|null $lookup
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereVersion($value)
 * @property string $paymentCycle
 * @property int|null $isMarital
 * @property string|null $maritalStatus
 * @property int $isActive
 * @property int|null $isAgeLimit
 * @property int $isDisableClass
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdditionalFields> $addtionalfield
 * @property-read int|null $addtionalfieldCount
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereIsAgeLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereIsDisableClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereIsMarital($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram wherePaymentCycle($value)
 * @mixin \Eloquent
 */
class AllowanceProgram extends Model
{
    public function newQuery($excludeDeleted = true)
    {
        return parent::newQuery($excludeDeleted)
            ->orderBy('name_en', 'asc');
    }
    public function lookup()
    {
        return $this->belongsTo(Lookup::class,'service_type');
    }

    public function addtionalfield()
    {
        return $this->belongsToMany(AdditionalFields::class, 'additional_fields_allowance_program', 'allowance_program_id','field_id');
    }

    public function ages(){
        return $this->hasMany(AllowanceProgramAge::class,'allowance_program_id');
    }


    public function classAmounts()
    {
        return $this->hasMany(AllowanceProgramAmount::class, 'allowance_program_id', 'id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'program_id');
    }  
     public function grievances()
    {
        return $this->hasMany(Grievance::class, 'program_id');
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class, 'program_id');
    }


}