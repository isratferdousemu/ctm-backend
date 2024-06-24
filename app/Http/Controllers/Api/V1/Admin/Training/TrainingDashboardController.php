<?php

namespace App\Http\Controllers\Api\V1\Admin\Training;

use App\Http\Controllers\Controller;
use App\Models\Lookup;
use App\Models\Trainer;
use App\Models\TrainingProgram;
use App\Models\TrainingProgramParticipant;
use App\Models\TrainingRating;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TrainingDashboardController extends Controller
{
    public function cardCalculation()
    {
        // Total number of courses
        $totalCourse = Lookup::where('type', 29)->count() ?? 0;

        // Total number of participants with status 1
        $totalParticipants = TrainingProgramParticipant::where('status', 1)->count() ?? 0;

        // Training completion percentage
        $trainingCompletion = TrainingProgramParticipant::where('status', 1)
            ->where('invitation_status', 1)
            ->count() ?? 0;

        $totalInvitations = TrainingProgramParticipant::where('invitation_status', 1)->count() ?? 0;

        $completionPercentage = $totalInvitations > 0
            ? ($trainingCompletion / $totalInvitations) * 100
            : 0;

        // Total number of active batches
        $activeBatches = TrainingProgram::where('status', 82)->count() ?? 0;

        // Total number of trainers and active trainers
        $totalNumberofTrainers = Trainer::count() ?? 0;
        $totalNumberofActiveTrainers = Trainer::where('status', 1)->count() ?? 0;

        // Average enrollment per training program
        $averageEnrollment = TrainingProgram::withCount('participants')
            ->get()
            ->avg('participants_count') ?? 0;

        // Prepare data for response
        $data = [
            [
                'id' => 1,
                'name_en' => 'Total Course',
                'name_bn' => 'মোট কোর্স',
                'value' => $totalCourse,
                'icon' => 'mdi mdi-card-multiple',
            ],
            [
                'id' => 2,
                'name_en' => 'Total Participants',
                'name_bn' => 'মোট অংশগ্রহণকারী',
                'value' => $totalParticipants,
                'icon' => 'mdi mdi-card-multiple',
            ],
            [
                'id' => 3,
                'name_en' => 'Training Completion',
                'name_bn' => 'প্রশিক্ষণ সম্পন্নতা',
                'value' => $completionPercentage,
                'icon' => 'mdi mdi-card-multiple',
            ],
            [
                'id' => 4,
                'name_en' => 'Active Batches',
                'name_bn' => 'সক্রিয় ব্যাচ',
                'value' => $activeBatches,
                'icon' => 'mdi mdi-card-multiple',
            ],
            [
                'id' => 5,
                'name_en' => 'Total No of Trainers',
                'name_bn' => 'মোট প্রশিক্ষকের সংখ্যা',
                'value' => $totalNumberofTrainers,
                'icon' => 'mdi mdi-card-multiple',
            ],
            [
                'id' => 6,
                'name_en' => 'Active Trainers',
                'name_bn' => 'সক্রিয় প্রশিক্ষক',
                'value' => $totalNumberofActiveTrainers,
                'icon' => 'mdi mdi-card-multiple',
            ],
            [
                'id' => 7,
                'name_en' => 'Enrolment Per Training (Avg.)',
                'name_bn' => 'প্রতি প্রশিক্ষণে নিবন্ধন (গড়)',
                'value' => round($averageEnrollment, 2),
                'icon' => 'mdi mdi-card-multiple',
            ],
        ];

        // Return JSON response
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => "Data successfully fetched",
        ], ResponseAlias::HTTP_OK);
    }

}
