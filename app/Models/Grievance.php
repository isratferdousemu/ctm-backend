<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grievance extends Model
{
    use HasFactory;

 public function grievanceType()
    {
        return $this->belongsTo(GrievanceType::class,'grievance_type_id','id');
    }
 public function grievanceSubject()
    {
        return $this->belongsTo(GrievanceSubject::class,'grievance_subject_id','id');
    } 
 public function program()
    {
        return $this->belongsTo(AllowanceProgram::class,'program_id','id');
    }  
    
    public function district()
    {
        return $this->belongsTo(Location::class, 'district_id', 'id');
    }


    public function districtPouroshova()
    {
        return $this->belongsTo(Location::class, 'district_pouro_id', 'id');
    }


    public function cityCorporation()
    {
        return $this->belongsTo(Location::class, 'city_id', 'id');
    }

    public function upazila()
    {
        return $this->belongsTo(Location::class, 'pouro_id', 'id');
    }


    public function thana()
    {
        return $this->belongsTo(Location::class, 'thana_id', 'id');
    }


    public function union()
    {
        return $this->belongsTo(Location::class, 'union_id', 'id');
    }


    public function pourashava()
    {
        return $this->belongsTo(Location::class, 'pouro_id', 'id');
    }


    public function ward()
    {
        return $this->belongsTo(Location::class, 'ward_id', 'id');
    }
        public function gender(){
        return $this->belongsTo(Lookup::class,'gender_id','id');
    }
}
