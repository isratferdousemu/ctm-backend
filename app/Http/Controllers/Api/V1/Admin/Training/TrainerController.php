<?php

namespace App\Http\Controllers\Api\V1\Admin\Training;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Training\TrainerRequest;
use App\Models\Trainer;
use App\Models\TrainingProgramTrainer;
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

        $query->with('designation');

        return $this->sendResponse($query->paginate(
            request('perPage')
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TrainerRequest $request)
    {
        $trainer = $this->saveTrainer($request, new Trainer());

        Helper::activityLogInsert($trainer, '','Trainer','Trainer Created !');

        return $this->sendResponse($trainer, 'Trainer created successfully');
    }


    /**
     * @param $request
     * @param $trainer
     * @return mixed
     */
    public function saveTrainer($request, $trainer)
    {
        $trainer->name = $request->name;
        $trainer->designation_id = $request->designation_id;
        $trainer->mobile_no = $request->mobile_no;
        $trainer->email = $request->email;
        $trainer->address = $request->address;
        if ($request->image) {
            $trainer->image = $request->file('image')->store('public');
        }
        $trainer->description = $request->description;
        $trainer->save();

        return $trainer;
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
        $trainer->load('designation');
        return $this->sendResponse($trainer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TrainerRequest $request, Trainer $trainer)
    {
        $beforeUpdate = $trainer->replicate();

        $trainer = $this->saveTrainer($request, $trainer);

        Helper::activityLogUpdate($trainer, $beforeUpdate,'Trainer','Trainer Updated !');

        return $this->sendResponse($trainer, 'Trainer updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trainer $trainer)
    {
        $trainer->delete();

        TrainingProgramTrainer::where('trainer_id', $trainer->id)->delete();

        Helper::activityLogDelete($trainer, '','Trainer','Trainer Deleted !');

        return $this->sendResponse($trainer, 'Trainer deleted successfully');

    }
}
