<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{

    public function division(){

        return $this->belongsTo(Location::class,'division_id');

    }
    public function district(){

        return $this->belongsTo(Location::class,'district_id');

    }
    public function program(){

        return $this->belongsTo(AllowanceProgram::class,'program_id');

    }
    public function office(){

        return $this->belongsTo(Office::class,'office_id');

    }
    public function members(){
        return $this->hasMany(CommitteeMember::class);

       

    }
}
