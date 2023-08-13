<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    
    public function committee(){

        return $this->belongsTo(Committee::class,'committee_id');

    }
}
