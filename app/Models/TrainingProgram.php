<?php

namespace App\Models;

use App\Constants\TrainingLookUp;
use App\Http\Traits\RoleTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class TrainingProgram extends Model
{
    use HasFactory, SoftDeletes, RoleTrait;


    protected $guarded = ['id'];


    protected $casts = [
        'on_days' => 'array',
        'question_paper' => 'array',
        'trainer_ratings_paper' => 'array',
    ];


    protected $appends = ['is_participant', 'certificate'];



    protected function isParticipant(): Attribute
    {
        return new Attribute(
            get: fn() => auth()->user()->hasRole($this->participant)
        );
    }


    protected function certificate(): Attribute
    {
        return new Attribute(
            function () {
                return TrainingProgramParticipant::where('training_program_id', $this->id)
                    ->where('user_id', auth()->id())
                    ->value('status');
            }
        );
    }



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


    public function participants()
    {
        return $this->hasMany(TrainingProgramParticipant::class);
    }


    public function statusName()
    {
        return $this->belongsTo(Lookup::class, 'status', 'id');
    }




}
