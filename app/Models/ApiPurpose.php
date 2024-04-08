<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ApiPurpose extends Model
{
    use HasFactory;


    protected $appends = ['columns'];


    public function columns(): Attribute
    {
        return  new Attribute(
            get: fn() => Schema::getColumnListing($this->table_name),
        );
    }
}
