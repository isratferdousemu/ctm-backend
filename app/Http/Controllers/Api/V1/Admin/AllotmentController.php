<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Allotment\StoreAllotmentRequest;
use App\Http\Requests\Admin\Allotment\UpdateAllotmentRequest;
use App\Http\Requests\Admin\Budget\StoreBudgetRequest;
use App\Http\Requests\Admin\Budget\UpdateBudgetRequest;
use App\Http\Resources\Admin\Allotment\AllotmentResouce;
use App\Http\Resources\Admin\Budget\BudgetResouce;
use App\Http\Services\Admin\BudgetAllotment\AllotmentService;
use App\Http\Traits\MessageTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AllotmentController extends Controller
{
    use MessageTrait;

    /**
     * @var AllotmentService
     */
    private AllotmentService $allotmentService;

    public function __construct(AllotmentService $allotmentService)
    {
        $this->allotmentService = $allotmentService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $allotmentList = $this->allotmentService->list($request);
//            return response()->json($beneficiaryList);
            return AllotmentResouce::collection($allotmentList)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param StoreBudgetRequest $request
     * @return BudgetResouce|\Illuminate\Http\JsonResponse
     */
    public function add(StoreAllotmentRequest $request): \Illuminate\Http\JsonResponse|AllotmentResouce
    {
        try {
            $data = $this->allotmentService->save($request);
            activity("Allotment")
                ->causedBy(auth()->user())
                ->performedOn($data)
                ->log('Allotment Created!');
            return AllotmentResouce::make($data)->additional([
                'success' => true,
                'message' => $this->insertSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param $id
     * @return BudgetResouce|\Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse|AllotmentResouce
    {
        try {
            $budget = $this->allotmentService->get($id);
            if ($budget) {
                return AllotmentResouce::make($budget)->additional([
                    'success' => true,
                    'message' => $this->fetchSuccessMessage,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $this->notFoundMessage,
                ], ResponseAlias::HTTP_OK);
            }
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param UpdateBudgetRequest $request
     * @param $id
     * @return BudgetResouce|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateAllotmentRequest $request, $id): \Illuminate\Http\JsonResponse|AllotmentResouce
    {
        try {
            $data = $this->allotmentService->update($request);
            activity("Budget")
                ->causedBy(auth()->user())
                ->performedOn($data)
                ->log('Budget Updated!');
            return AllotmentResouce::make($data)->additional([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->allotmentService->delete($id);
            activity("Allotment")
                ->causedBy(auth()->user())
                ->log('Allotment Deleted!!');
            return response()->json([
                'success' => true,
                'message' => $this->deleteSuccessMessage,
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

}
