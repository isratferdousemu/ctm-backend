<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProgramParticipant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    protected $casts = [
        'exam_response' => 'array',
        'trainer_rating_response' => 'array',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function trainingCircular()
    {
        return $this->belongsTo(TrainingCircular::class);
    }



    public function trainingProgram()
    {
        return $this->belongsTo(TrainingProgram::class);
    }


    public function ratings()
    {
        return $this->hasMany(TrainingRating::class, 'user_id', 'user_id')
            ->where('training_program_id', $this->training_program_id);
    }


}
