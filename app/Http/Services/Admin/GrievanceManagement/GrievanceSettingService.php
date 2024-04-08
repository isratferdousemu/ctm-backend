<?php

namespace App\Http\Services\Admin\GrievanceManagement;
use App\Http\Traits\RoleTrait;
use App\Models\GrievanceSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;



Class GrievanceSettingService{
    use RoleTrait;

    public function store($request){

       DB::beginTransaction();
       try {
       $grievanceSettings = new GrievanceSetting();
       $grievanceSettings->grievance_type_id = $request->grievance_type_id;
       $grievanceSettings->grievance_subject_id = $request->grievance_subject_id;
       $grievanceSettings->first_tire_officer =$request->first_tire_officer;
       $grievanceSettings->first_tire_solution_time =$request->first_tire_solution_time;
       $grievanceSettings->secound_tire_officer =$request->secound_tire_officer;
       $grievanceSettings->secound_tire_solution_time =$request->secound_tire_solution_time;
       $grievanceSettings->third_tire_officer =$request->third_tire_officer;
       $grievanceSettings->third_tire_solution_time =$request->third_tire_solution_time;
       $grievanceSettings->save();
       DB::commit();
       return $grievanceSettings;
       } catch (\Throwable $th) {
           DB::rollBack();
           throw $th;
       }
      
    }

    public function edit($id)
    {
       $grievanceSubject= GrievanceSetting::where('id',$id)->first();
       return $grievanceSubject;
    }

     public function update(Request $request)
    {
     DB::beginTransaction();
       try {
       $grievanceSubject = GrievanceSetting::where('id',$request->id)->first();
       $grievanceSubject->title_en = $request->title_en;
       $grievanceSubject->title_bn = $request->title_bn;
       $grievanceSubject->grievance_type_id = $request->grievance_type_id;
       if ($request->status == null) {
         $grievanceSubject->status = '0';
      } else {
       $grievanceSubject->status = $request->status;
       }

       $grievanceSubject->update();
       DB::commit();
       return $grievanceSubject;
       } catch (\Throwable $th) {
           DB::rollBack();
           throw $th;
       }
    }

    public function destroy($id)
    {
    DB::beginTransaction();
       try {
       $grievanceSubject = GrievanceSetting::where('id',$id)->first();
       $grievanceSubject->delete();
       DB::commit();
       return $grievanceSubject;
       } catch (\Throwable $th) {
           DB::rollBack();
           throw $th;
       }
    }


}