<?php

namespace App\Http\Controllers\Api\V1\Admin\Training;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use App\Models\TrainingProgramModule;
use App\Models\TrainingProgramTrainer;
use App\Models\TrainingRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingDashboardController extends Controller
{
    public function cardCalculation(){
        return 123;
    }


    public function topTrainers(Request $request)
    {
        return Trainer::query()
            ->select('trainers.id', 'trainers.name',
                DB::raw("COUNT(t2.module_id) as total")
            )
            ->rightJoin('training_program_trainers as t1', 'trainers.id', '=', 't1.trainer_id')
            ->rightJoin('training_program_modules as t2', 't1.training_program_id', '=', 't2.training_program_id')
            ->rightJoin('training_programs as t3', 't2.training_program_id', '=', 't3.id')
            ->when($request->program_id, function ($q, $v) {
                $q->where('t3.id', $v);
            })
            ->groupBy('trainers.id', 'trainers.name')
            ->orderByDesc('total')
            ->get();

    }
}
