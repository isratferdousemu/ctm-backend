<?php

namespace App\Http\Controllers\Api\V1\Admin\Training;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Training\TrainerRequest;
use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Trainer::query();

        $query->when(request('search'), function ($q, $v) {
            $q->where('name', 'like', "%$v%")
                ->orWhere('mobile_no', 'like', "%$v%")
                ->orWhere('email', 'like', "%$v%")
            ;
        });

        return $this->sendResponse($query->paginate(
            request('perPage')
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TrainerRequest $request)
    {
        $trainer = Trainer::create($request->validated());

        Helper::activityLogInsert($trainer, '','Trainer','Trainer Created !');

        return $this->sendResponse($trainer, 'Trainer created successfully');
    }


    public function updateStatus(Trainer $trainer)
    {
        $beforeUpdate = $trainer->replicate();

        $trainer->update(['status' => !$trainer->status]);

        Helper::activityLogUpdate($trainer, $beforeUpdate,'Trainer','Trainer Status Updated !');

        return $this->sendResponse($trainer, 'Trainer status updated successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(Trainer $trainer)
    {
        return $this->sendResponse($trainer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TrainerRequest $request, Trainer $trainer)
    {
        $beforeUpdate = $trainer->replicate();

        $trainer->update($request->validated());

        Helper::activityLogUpdate($trainer, $beforeUpdate,'Trainer','Trainer Updated !');

        return $this->sendResponse($trainer, 'Trainer updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trainer $trainer)
    {
        $trainer->delete();

        Helper::activityLogDelete($trainer, '','Trainer','Trainer Deleted !');

        return $this->sendResponse($trainer, 'Trainer deleted successfully');

    }
}
