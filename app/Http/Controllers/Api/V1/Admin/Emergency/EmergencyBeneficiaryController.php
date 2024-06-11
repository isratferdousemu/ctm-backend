<?php

namespace App\Http\Controllers\Api\V1\Admin\Emergency;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Emergency\EmergencyBeneficiaryResource;
use App\Http\Services\Admin\Emergency\EmergencyBeneficiaryService;
use App\Http\Traits\MessageTrait;
use Illuminate\Http\Request;

class EmergencyBeneficiaryController extends Controller
{
    use MessageTrait;
    private $emergencyBeneficiaryService;
    
    public function __construct(EmergencyBeneficiaryService $emergencyBeneficiaryService)
    {
        $this->emergencyBeneficiaryService = $emergencyBeneficiaryService;
    }
    public function store(Request $request)
    {
        $beneficiary = $this->emergencyBeneficiaryService->store($request);

        Helper::activityLogInsert($beneficiary, '', 'Emergency Beneficiary', 'Emergency Beneficiary Created !');

        return EmergencyBeneficiaryResource::make($beneficiary)->additional([
            'success' => true,
            'message' => $this->insertSuccessMessage,
        ]);
    }
    public function getExistingBeneficariesInfo(Request $request)
    {
        $beneficiaryInfo = $this->emergencyBeneficiaryService->getExistingBeneficaries($request);
        return handleResponse($beneficiaryInfo, null);
    }
}
