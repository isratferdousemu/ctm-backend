<?php

namespace App\Http\Services\Admin\Employee;

use App\Models\WorkingShift;
use App\Models\WorkingShiftDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkingShiftService
{
    public function createEmpShift(Request $request){
        DB::beginTransaction();
        try {

            $workingShift                       = new WorkingShift;
            $workingShift->name                = $request->name;
            $workingShift->type            = $request->type;
            $workingShift->description            = $request->description;
            $workingShift->save();

            if ($request->has('weekdays')) {

    foreach ($request->weekdays as $value) {
    $workingShiftDetails                       = new WorkingShiftDetails;
    $workingShiftDetails->working_shift_id                = $workingShift->id;
    $workingShiftDetails->weekday                = $value['weekday'];
    $workingShiftDetails->is_weekend                = $value['is_weekend'];
    $workingShiftDetails->start_at  = $request->type =='regular'? $request->start_at : $value['start_at'];
    $workingShiftDetails->end_at  = $request->type =='regular'? $request->end_at : $value['end_at'];
    $workingShiftDetails->save();

    }

            }


            DB::commit();
            return $workingShift;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function clearOldShiftDetails(WorkingShift $shift)
    {
        WorkingShiftDetails::whereWorkingShiftId($shift->id)->forceDelete();
    }

    public function deleteEmpShift(WorkingShift $workingShift){
        DB::beginTransaction();
        try {
            $this->clearOldShiftDetails($workingShift);
            $workingShift->forceDelete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }
    public function updateEmpShift(Request $request, WorkingShift $workingShift){
        DB::beginTransaction();
        try {
            $workingShift->name                = $request->name;
            $workingShift->type            = $request->type;
            $workingShift->description            = $request->description;
            $workingShift->save();

            if ($request->has('weekdays')) {
                $this->clearOldShiftDetails($workingShift);

                foreach ($request->weekdays as $value) {
                $workingShiftDetails                       = new WorkingShiftDetails;
                $workingShiftDetails->working_shift_id                = $workingShift->id;
                $workingShiftDetails->weekday                = $value['weekday'];
                $workingShiftDetails->is_weekend                = $value['is_weekend'];
                $workingShiftDetails->start_at  = $request->type =='regular'? $request->start_at : $value['start_at'];
                $workingShiftDetails->end_at  = $request->type =='regular'? $request->end_at : $value['end_at'];
                $workingShiftDetails->save();

                }

                        }
            DB::commit();
            return $workingShift;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


}
