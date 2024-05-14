<?php

namespace App\Http\Controllers\Api\V1\Admin\Training;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Training\TrainingProgramRequest;
use App\Models\TimeSlot;
use App\Models\Trainer;
use App\Models\TrainingCircular;
use App\Models\TrainingProgram;

class TrainingProgramController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = TimeSlot::query();

        $query->when(request('search'), function ($q, $v) {
            $q->where('time', 'like', "%$v%")
            ;
        });

        return $this->sendResponse($query
            ->paginate(request('perPage'))
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TrainingProgramRequest $request)
    {
        $trainingProgram = $this->saveTrainingProgram($request, new TrainingProgram());

        Helper::activityLogInsert($trainingProgram, '','Training Program','Training Program Created !');

        return $this->sendResponse($trainingProgram, 'Training Program created successfully');
    }


    /**
     * @param $request
     * @param TrainingProgram $trainingProgram
     * @return TrainingProgram
     */
    public function saveTrainingProgram($request, $trainingProgram)
    {
        $trainingProgram->program_name = $request->program_name;
        $trainingProgram->training_circular_id = $request->training_circular_id;
        $trainingProgram->start_date = $request->start_date;
        $trainingProgram->end_date = $request->end_date;
        $trainingProgram->description = $request->description;
        $trainingProgram->on_days = $request->on_days;
        $trainingProgram->save();

        $trainingProgram->modules()->sync($request->circular_modules);
        $trainingProgram->modules()->sync($request->trainers);

        return $trainingProgram;
    }


    public function circulars()
    {
        $circulars = TrainingCircular::with('modules:id,value_en,value_bn')
            ->get(['id', 'circular_name']);

        return $this->sendResponse($circulars);
    }


    public function trainers()
    {
        return $this->sendResponse(Trainer::whereStatus(1)->get());
    }


    public function timeSlots()
    {
        return $this->sendResponse(TimeSlot::get(['id', 'time']));
    }

    /**
     * Display the specified resource.
     */
    public function show(TimeSlot $trainingProgram)
    {
        return $this->sendResponse($trainingProgram);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TimeSlotRequest $request, TimeSlot $trainingProgram)
    {
        $beforeUpdate = $trainingProgram->replicate();

        $trainingProgram->update($request->validated());

        Helper::activityLogUpdate($trainingProgram, $beforeUpdate,'Training Program','Training Program Updated !');

        return $this->sendResponse($trainingProgram, 'Training Program updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeSlot $trainingProgram)
    {
        $trainingProgram->delete();

        Helper::activityLogDelete($trainingProgram, '','Training Program','Training Program Deleted !');

        return $this->sendResponse($trainingProgram, 'Training Program deleted successfully');

    }
}
