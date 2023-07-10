<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name_en','name_bn','code','parent_id'];

    public static $divisionInsertRules = [

        'name_en'                              => 'required|string|max:50|unique:locations,name_en',
        'name_bn'                              => 'required|string|max:50|unique:locations,name_bn',
        'code'                               => 'required|string|max:6|unique:locations,code',
    ];
    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }
}
