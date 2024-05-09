<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\Admin\Training\TrainingCircularRequest;
use App\Models\TrainingCircular;
use Illuminate\Http\Request;

class TrainingCircularController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return $this->sendResponse(
            TrainingCircular::paginate(request('perPage'))
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TrainingCircularRequest $request)
    {
        $trainingCircular = TrainingCircular::create($request->except('module_id'));

        $trainingCircular->modules()->attach($request->module_id);

        Helper::activityLogInsert($trainingCircular, '','Training Circular','Training Circular Created !');

        return $this->sendResponse($trainingCircular, 'Training Circular Circular created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingCircular $circular)
    {
        $circular->load('modules', 'circularType', 'trainingType', 'status');

        return $this->sendResponse($circular);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TrainingCircularRequest $request, TrainingCircular $circular)
    {
        $beforeUpdate = $circular->replicate();

        $circular->update($request->except('module_id'));

        $circular->modules()->sync($request->module_id);

        Helper::activityLogInsert($circular, $beforeUpdate,'Training Circular','Training Circular Created !');

        return $this->sendResponse($circular, 'Training Circular Circular updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingCircular $circular)
    {
        $circular->delete();

        Helper::activityLogDelete($circular, '','Training Circular','Training Circular Deleted !');

        return $this->sendResponse($circular, 'Training Circular deleted successfully');

    }
}
