<?php

namespace App\Models;

use App\Models\AdditionalFieldValues;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\AdditionalFields
 *
 * @property int $id
 * @property string $nameEn
 * @property string $nameBn
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields whereNameBn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdditionalFieldValues> $additionalFieldValue
 * @property-read int|null $additionalFieldValueCount
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AllowanceProgram> $allowanceprogram
 * @property-read int|null $allowanceprogramCount
 * @mixin \Eloquent
 */
class AdditionalFields extends Model
{
    use HasFactory; 

    public function newQuery($excludeDeleted = true)
    {
        return parent::newQuery($excludeDeleted)
            ->orderBy('name_en', 'asc');
    }

    public function allowanceprogram()
    {
        return $this->belongsToMany(AllowanceProgram::class);
    }

    public function additional_field_value()
    {
        return $this->hasMany(AdditionalFieldValues::class,'additional_field_id');
    }
      public function allowAddiFieldValues()
    {
        return $this->belongsToMany(AdditionalFieldValues::class, 'application_allowance_values', 'allow_addi_fields_id', 'allow_addi_field_values_id')
            ->withPivot('value');
    }
   
}
