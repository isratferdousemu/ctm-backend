<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission;

class Menu extends Model
{
    use HasFactory,SoftDeletes;
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }


    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function pageLink()
    {
        return $this->belongsTo(Permission::class, 'page_link_id');
    }
}
