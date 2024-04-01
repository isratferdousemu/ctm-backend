<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class APIUrl extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "api_urls";


}
