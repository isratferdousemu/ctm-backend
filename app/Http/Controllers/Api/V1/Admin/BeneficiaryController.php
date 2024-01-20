<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Beneficiary\SearchBeneficiaryRequest;
use App\Http\Resources\Admin\Beneficiary\BeneficiaryResource;
use App\Http\Services\Admin\Beneficiary\BeneficiaryService;
use App\Http\Traits\MessageTrait;

/**
 *
 */
class BeneficiaryController extends Controller
{
    use MessageTrait;

    /**
     * @var BeneficiaryService
     */
    private BeneficiaryService $beneficiaryService;

    /**
     * @param BeneficiaryService $beneficiaryService
     */
    public function __construct(BeneficiaryService $beneficiaryService)
    {
        $this->beneficiaryService = $beneficiaryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function list(SearchBeneficiaryRequest $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $beneficiaryList = $this->beneficiaryService->list($request);
//            return response()->json($beneficiaryList);
            return BeneficiaryResource::collection($beneficiaryList)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|BeneficiaryResource
     */
    public function show($id): \Illuminate\Http\JsonResponse|BeneficiaryResource
    {
        try {
            $beneficiary = $this->beneficiaryService->detail($id);
            return BeneficiaryResource::make($beneficiary)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }
}
