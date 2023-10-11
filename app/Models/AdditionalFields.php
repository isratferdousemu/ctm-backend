<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalFields extends Model
{
    use HasFactory;

    public function allowanceprogram()
    {
        return $this->belongsToMany(AllowanceProgram::class);
    }
}
