<?php

namespace App\Http\Controllers\Api\V1\Admin\Training;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Training\ParticipantRequest;
use App\Http\Services\Admin\Training\ParticipantService;
use App\Models\TrainingParticipant;
use Illuminate\Http\Request;

class TrainingParticipantController extends Controller
{
    public function __construct(public ParticipantService $participantService)
    {
    }

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
    public function store(ParticipantRequest $request)
    {
        $participant = TrainingParticipant::create($request->validated());

        Helper::activityLogInsert($participant, '','Training Participant','Training Participant Created !');

        return $this->sendResponse($participant, 'Training Participant created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(TimeSlot $participant)
    {
        return $this->sendResponse($participant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TimeSlotRequest $request, TimeSlot $participant)
    {
        $beforeUpdate = $participant->replicate();

        $participant->update($request->validated());

        Helper::activityLogUpdate($participant, $beforeUpdate,'Training Participant','Training Participant Updated !');

        return $this->sendResponse($participant, 'Training Participant updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeSlot $participant)
    {
        $participant->delete();

        Helper::activityLogDelete($participant, '','Training Participant','Training Participant Deleted !');

        return $this->sendResponse($participant, 'Training Participant deleted successfully');

    }
}
