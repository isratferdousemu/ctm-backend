<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variable extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name_en',
        'score',
    ];

    // public function newQuery($excludeDeleted = true)
    // {
    //     return parent::newQuery($excludeDeleted)
    //         ->orderBy('name_en', 'asc');
    // }

    public function parent()
    {
        return $this->belongsTo(Variable::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Variable::class, 'parent_id');
    }
}
