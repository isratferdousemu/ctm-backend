<?php

namespace App\Http\Controllers\Api\V1\Admin\Emergency;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Emergency\EmergencyBeneficiaryResource;
use App\Http\Services\Admin\Emergency\EmergencyBeneficairyService;
use App\Http\Traits\MessageTrait;
use Illuminate\Http\Request;

class EmergencyBeneficiaryController extends Controller
{
    use MessageTrait;
    private $emergencyBeneficairyService;
    public function __construct(EmergencyBeneficairyService $emergencyBeneficairyService)
    {
        $this->emergencyBeneficairyService = $emergencyBeneficairyService;
    }
    public function store(Request $request)
    {
        $beneficiary = $this->emergencyBeneficairyService->store($request);

        Helper::activityLogInsert($beneficiary, '', 'Emergency Beneficiary', 'Emergency Beneficiary Created !');

        return EmergencyBeneficiaryResource::make($beneficiary)->additional([
            'success' => true,
            'message' => $this->insertSuccessMessage,
        ]);
    }
}
