<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowanceProgramAdditionalField extends Model
{
    use HasFactory;

    protected $table = "additional_fields_allowance_program";

    public function allowanceprogram()
    {
        return $this->hasMany(AllowanceProgram::class);
    }

    public function additionalfield()
    {
        return $this->hasMany(AdditionalFields::class);
    }
}
