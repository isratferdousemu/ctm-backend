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




}
