<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficeHasWard extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'office_has_wards';

    public function parent()
    {
        return $this->belongsTo(Location::class, 'ward_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }
}
