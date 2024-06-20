<?php

namespace App\Http\Controllers\Api\V1\Admin\Emergency;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Emergency\EmergencyAllotmentRequest;
use App\Http\Resources\Admin\Emergency\EmergencyAllotmentResource;
use App\Http\Services\Admin\Emergency\EmergencyAllotmentService;
use App\Http\Traits\MessageTrait;
use App\Models\EmergencyAllotment;
use Illuminate\Http\Request;

class EmergencyAllotmentController extends Controller
{
    use MessageTrait;

    protected $emergencyAllotmentService;

    public function __construct(EmergencyAllotmentService $emergencyAllotmentService)
    {
        $this->emergencyAllotmentService = $emergencyAllotmentService;
    }

    public function getEmergencyAllotments(Request $request)
    {
        $allotments = $this->emergencyAllotmentService->getEmergencyAllotments($request);

        return EmergencyAllotmentResource::collection($allotments)->additional([
            'success' => true,
            'message' => $this->fetchDataSuccessMessage,
        ]);
    }

    public function getAllotmentWiseProgram(Request $request)
    {
        $data= array();
        $processedProgramIds = [];
        $emergencyAllotments = EmergencyAllotment::with('program')->get();
        foreach ($emergencyAllotments as $emergencyAllotment) {
            $program = $emergencyAllotment->program;
            if ($program && !in_array($program->id, $processedProgramIds)) {
                $data[] = [
                    'id' => $program->id,
                    'name_en' => $program->name_en,
                    'name_bn' => $program->name_bn,
                ];
                $processedProgramIds[] = $program->id;
            }
        }
       return $data;
    }


    public function store(EmergencyAllotmentRequest $request)
    {

        $allotment = $this->emergencyAllotmentService->storeAllotment($request);

        Helper::activityLogInsert($allotment, '', 'Emergency Allotment', 'Emergency Allotment Created !');

        return EmergencyAllotmentResource::make($allotment)->additional([
            'success' => true,
            'message' => "Emergency Allotment Created Successfully",
        ]);
    }

    public function edit($id)
    {

        try {
            $allotment = $this->emergencyAllotmentService->edit($id);
            return EmergencyAllotmentResource::make($allotment)->additional([
                'success' => true,
                'message' => $this->fetchDataSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(EmergencyAllotmentRequest $request, $id)
    {
        try {
            $beforeUpdate = EmergencyAllotment::find($id);
            $allotment = $this->emergencyAllotmentService->update($request, $id);
            Helper::activityLogUpdate($allotment, $beforeUpdate, 'Emergency Allotment', 'Emergency Allotment Updated !');

            return EmergencyAllotmentResource::make($allotment)->additional([
                'success' => true,
                'message' => "Emergency Allotment Updated Successfully",
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroy($id)
    {
        $data = $this->emergencyAllotmentService->destroy($id);
        Helper::activityLogDelete($data, '', 'Emergency Allotment', 'Emergency Allotment Deleted !');
        return handleResponse($data, "Emergency Allotment Deleted Successfully");
    }
}
