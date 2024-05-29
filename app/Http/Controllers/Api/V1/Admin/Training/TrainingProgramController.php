<?php

namespace App\Http\Controllers\Api\V1\Admin\Training;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Training\TrainingProgramRequest;
use App\Http\Services\Admin\Training\ProgramService;
use App\Models\TimeSlot;
use App\Models\TrainingProgram;
use App\Models\Trainer;
use App\Models\TrainingCircular;
use App\Models\TrainingProgramParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingProgramController extends Controller

{

    public function __construct(public ProgramService $programService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TrainingProgram::query();

        $query->when(request('search'), function ($q, $v) {
            $q->where('program_name', 'like', "%$v%")
            ;
        });

        $query->when(request('training_type_id'), function ($q, $v) {
            $q->whereHas('trainingCircular', function ($q) use ($v) {
                $q->where('training_type_id', $v);
            });
        });

        $query->when(request('training_circular_id'), function ($q, $v) {
            $q->where('training_circular_id', $v);
        });

        $query->when(request('module_id'), function ($q, $v) {
            $q->whereHas('modules', function ($q) use ($v) {
                $q->whereId($v);
            });
        });

        $query->when(request()->has('status'), function ($q, $v) {
            $q->where('status', request('status'));
        });

        $query->when(request('trainer_id'), function ($q, $v) {
            $q->whereHas('trainers', function ($q) use ($v) {
                $q->whereId($v);
            });
        });


        $query->when(request('start_date'), function ($q, $v) {
            $q->whereDate('start_date', '>=', $v);
        });


        $query->when(request('end_date'), function ($q, $v) {
            $q->whereDate('end_date', '<=', $v);
        });


        $query->with('modules', 'trainingCircular.trainingType', 'trainers', 'statusName');

        $query->latest();

        return $this->sendResponse($query
            ->paginate(request('perPage'))
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TrainingProgramRequest $request)
    {
        DB::beginTransaction();
        try {
            $program = $this->programService->storeProgram($request);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        Helper::activityLogInsert($program, '','Training Program','Training Program Created !');
        return $this->sendResponse($program, 'Training Program created successfully');
    }


    /**
     * @param $request
     * @param TrainingProgram $program
     * @return TrainingProgram
     */
    public function saveTrainingProgram($request, $program)
    {
        $program->program_name = $request->program_name;
        $program->training_circular_id = $request->training_circular_id;
        $program->start_date = $request->start_date;
        $program->end_date = $request->end_date;
        $program->description = $request->description;
        $program->on_days = $request->on_days;
        $program->save();

        $program->modules()->sync($request->circular_modules);
        $program->trainers()->sync($request->trainers);

        return $program;
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
    public function show(TrainingProgram $program)
    {
        $program->load('trainingCircular.trainingType', 'modules', 'trainers', 'statusName');

        return $this->sendResponse($program);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TrainingProgramRequest $request, TrainingProgram $program)
    {
        $beforeUpdate = $program->replicate();

        DB::beginTransaction();
        try {
            $program = $this->programService->updateProgram($request, $program);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        Helper::activityLogUpdate($program, $beforeUpdate,'Training Program','Training Program Updated !');
        return $this->sendResponse($program, 'Training Program updated successfully');
    }


    public function updateStatus(TrainingProgram $program)
    {
        $beforeUpdate = $program->replicate();
        $program->update(['status' => !$program->status]);
        Helper::activityLogUpdate($program, $beforeUpdate,'Training Program','Training Program Status Updated !');

        return $this->sendResponse($program, 'Training Program status updated successfully');

    }


    public function updateExamStatus(TrainingProgram $program)
    {
        $beforeUpdate = $program->replicate();
        $program->update(['exam_status' => !$program->exam_status]);
        Helper::activityLogUpdate($program, $beforeUpdate,'Training Program','Training Program Exam Status Updated !');

        return $this->sendResponse($program, 'Exam status updated successfully');

    }

    public function updateRatingStatus(TrainingProgram $program)
    {
        $beforeUpdate = $program->replicate();
        $program->update(['rating_status' => !$program->rating_status]);
        Helper::activityLogUpdate($program, $beforeUpdate,'Training Program','Training Program Rating Status Updated !');

        return $this->sendResponse($program, 'Rating status updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingProgram $program)
    {
        $program->delete();

        TrainingProgramParticipant::where('training_program_id', $program->id)->delete();

        Helper::activityLogDelete($program, '','Training Program','Training Program Deleted !');

        return $this->sendResponse($program, 'Training Program deleted successfully');

    }
}
