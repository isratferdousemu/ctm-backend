<?php

namespace App\Http\Controllers\Api\V1\Admin\Emergency;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Emergency\EmergencyBeneficiaryRequest;
use App\Http\Resources\Admin\Emergency\EmergencyBeneficiaryResource;
use App\Http\Services\Admin\Emergency\EmergencyBeneficiaryService;
use App\Http\Traits\MessageTrait;

use App\Models\EmergencyBeneficiary;
use Illuminate\Http\Request;

class EmergencyBeneficiaryController extends Controller
{
    use MessageTrait;
    private EmergencyBeneficiaryService $emergencyBeneficiaryService;

    public function __construct(EmergencyBeneficiaryService $emergencyBeneficiaryService)
    {
        $this->emergencyBeneficiaryService = $emergencyBeneficiaryService;
    }

    /**
     * @throws \Throwable
     */
    public function store(Request $request): EmergencyBeneficiaryResource
    {
        $beneficiary = $this->emergencyBeneficiaryService->store($request);

        Helper::activityLogInsert($beneficiary, '', 'Emergency Beneficiary', 'Emergency Beneficiary Created !');

        return EmergencyBeneficiaryResource::make($beneficiary)->additional([
            'success' => true,
            'message' => $this->insertSuccessMessage,
        ]);
    }
    public function edit($id)
    {
        try {
            $beneficiary = $this->emergencyBeneficiaryService->edit($id);
            return EmergencyBeneficiaryResource::make($beneficiary)->additional([
                'success' => true,
                'message' => $this->fetchDataSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function list(Request $request): \Illuminate\Http\JsonResponse
    {
        $beneficiaryInfo = $this->emergencyBeneficiaryService->list($request);
        return handleResponse($beneficiaryInfo, null);
    }   public function getExistingBeneficiariesInfo(Request $request): \Illuminate\Http\JsonResponse
{
        $beneficiaryInfo = $this->emergencyBeneficiaryService->getExistingBeneficaries($request);
        return handleResponse($beneficiaryInfo, null);
    }

    public function update(EmergencyBeneficiaryRequest $request, $id)
    {
//        return $request->hasFile('nominee_image');
        try {
            $beforeUpdate = EmergencyBeneficiary::find($id);
            $beneficiary = $this->emergencyBeneficiaryService->update($request, $id);
            Helper::activityLogUpdate($beneficiary, $beforeUpdate, 'Emergency Beneficiary', 'Emergency Beneficiary Updated !');

            return EmergencyBeneficiaryResource::make($beneficiary)->additional([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroy($id)
    {
        $data = $this->emergencyBeneficiaryService->destroy($id);
        Helper::activityLogDelete($data, '', 'Emergency Beneficiary', 'Emergency Beneficiary Deleted !');
        return handleResponse($data, $this->deleteSuccessMessage);
    }
}
