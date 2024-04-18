<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;


class GrievanceSetting extends Model
{
    use HasFactory;

      public function grievanceType()
    {
        return $this->belongsTo(GrievanceType::class,'grievance_type_id');
    }
      public function grievanceSubject()
    {
        return $this->belongsTo(GrievanceSubject::class,'grievance_subject_id','id');
    }  
    public function firstOfficer()
    {
        return $this->belongsTo(Role::class,'first_tire_officer','id');
    } 
    public function secoundOfficer()
    {
        return $this->belongsTo(Role::class,'secound_tire_officer','id');
    }
     public function thirdOfficer()
    {
        return $this->belongsTo(Role::class,'third_tire_officer','id');
    }
}