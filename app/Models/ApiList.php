<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiList extends Model
{
    use HasFactory;


    protected $casts = [
        'selected_columns' => 'array'
    ];


    public function purpose()
    {
        return $this->belongsTo(ApiPurpose::class, 'api_purpose_id', 'id');
    }

}
