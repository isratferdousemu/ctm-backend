<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'score',
    ];

    public function parent()
    {
        return $this->belongsTo(Variable::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Variable::class, 'parent_id');
    }
}
