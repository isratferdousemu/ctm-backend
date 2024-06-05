<?php

namespace App\Http\Controllers\Api\V1\Admin\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Beneficiary\BeneficiaryResource;
use App\Http\Resources\Admin\Payroll\ActiveBeneficiaryResource;
use App\Http\Resources\Admin\Payroll\AllotmentResource;
use App\Http\Resources\Admin\Payroll\PayrollInstallmentScheduleResource;
use App\Http\Services\Admin\Beneficiary\BeneficiaryService;
use App\Http\Services\Admin\Payroll\PayrollService;
use App\Http\Traits\MessageTrait;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    use MessageTrait;

    /**
     * @var PayrollService
     */
    private PayrollService $payrollService;

    /**
     * @param PayrollService $payrollService
     */
    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    /**
     * @param int $program_id
     * @param int $financial_year_id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getActiveInstallments(int $program_id, int $financial_year_id): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $activeInstallmentList = $this->payrollService->getActiveInstallments($program_id, $financial_year_id);
//            return response()->json($beneficiaryList);
            return PayrollInstallmentScheduleResource::collection($activeInstallmentList)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function getAllotmentAreaList(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $allotmentAreaList = $this->payrollService->getAllotmentAreaList($request);
//            return response()->json($beneficiaryList);
            return AllotmentResource::collection($allotmentAreaList)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function getActiveBeneficiaries($allotment_id): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $beneficiaryList = $this->payrollService->getActiveBeneficiaries($allotment_id);
//            return response()->json($beneficiaryList);
            return ActiveBeneficiaryResource::collection($beneficiaryList)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

}
