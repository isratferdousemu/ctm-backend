<?php

namespace App\Http\Controllers\Api\V1\Admin\Training;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Training\TimeSlotRequest;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
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
    public function store(TimeSlotRequest $request)
    {
        $timeSlot = TimeSlot::create($request->validated());

        Helper::activityLogInsert($timeSlot, '','Time Slot','Time Slot Created !');

        return $this->sendResponse($timeSlot, 'Time Slot created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(TimeSlot $timeSlot)
    {
        return $this->sendResponse($timeSlot);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TimeSlotRequest $request, TimeSlot $timeSlot)
    {
        $beforeUpdate = $timeSlot->replicate();

        $timeSlot->update($request->validated());

        Helper::activityLogUpdate($timeSlot, $beforeUpdate,'Time Slot','Time Slot Updated !');

        return $this->sendResponse($timeSlot, 'Time Slot updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeSlot $timeSlot)
    {
        $timeSlot->delete();

        Helper::activityLogDelete($timeSlot, '','Time Slot','Time Slot Deleted !');

        return $this->sendResponse($timeSlot, 'Time Slot deleted successfully');

    }
}
