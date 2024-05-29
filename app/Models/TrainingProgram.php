<?php

namespace App\Models;

use App\Constants\TrainingLookUp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingProgram extends Model
{
    use HasFactory, SoftDeletes;


    protected $guarded = ['id'];


    protected $casts = [
        'on_days' => 'array'
    ];


    public function trainingCircular()
    {
        return $this->belongsTo(TrainingCircular::class);
    }


    public function modules()
    {
        return $this->belongsToMany(Lookup::class, TrainingProgramModule::class, 'training_program_id', 'module_id')
            ->where('type', TrainingLookUp::TRAINING_MODULE);
    }



    public function trainers()
    {
        return $this->belongsToMany(Trainer::class, TrainingProgramTrainer::class, 'training_program_id', 'trainer_id');
    }


    public function users()
    {
        return $this->belongsToMany(User::class, 'training_program_participants', 'training_program_id', 'user_id');
    }


    public function statusName()
    {
        return $this->belongsTo(Lookup::class, 'status', 'id');
    }




}
