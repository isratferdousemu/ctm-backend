<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiDataReceive extends Model
{
    use HasFactory;


    public function apiList()
    {
        return $this->belongsToMany(ApiList::class, 'api_selects', 'api_data_receive_id', 'api_list_id')
            ->orderByDesc('created_at');
    }

}
