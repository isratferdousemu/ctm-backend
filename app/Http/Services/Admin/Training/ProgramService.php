<?php

namespace App\Http\Services\Admin\Training;

use App\Helpers\Helper;
use App\Models\TrainingParticipant;
use App\Models\TrainingProgram;
use App\Models\User;

class ProgramService
{

    public function storeProgram($request)
    {
        $program = new TrainingProgram();
        $program->program_name = $request->program_name;
        $program->training_circular_id = $request->training_circular_id;
        $program->start_date = $request->start_date;
        $program->end_date = $request->end_date;
        $program->description = $request->description;
        $program->on_days = $request->on_days;

        $program->save();

        $program->modules()->sync($request->circular_modules);
        $program->trainers()->sync($request->trainers);
        $program->users()->syncWithPivotValues($request->users, ['training_circular_id' => $program->training_circular_id]);

        $program->users->map(function ($user) {
            return $user->assignRole('participant');
        });

        return $program;
    }




    public function updateProgram($request, $program)
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
        $program->users()->syncWithPivotValues($request->users, ['training_circular_id' => $program->training_circular_id]);

        $program->users->map(function ($user) {
            return $user->assignRole('participant');
        });

        return $program;
    }

}
