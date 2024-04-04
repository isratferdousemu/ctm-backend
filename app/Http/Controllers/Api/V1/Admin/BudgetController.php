<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Budget\StoreBudgetRequest;
use App\Http\Requests\Admin\Budget\UpdateBudgetRequest;
use App\Http\Resources\Admin\Budget\BudgetResouce;
use App\Http\Services\Admin\BudgetAllotment\BudgetService;
use App\Http\Traits\MessageTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 *
 */
class BudgetController extends Controller
{
    use MessageTrait;

    /**
     * @var BudgetService
     */
    private BudgetService $budgetService;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $budgetList = $this->budgetService->list($request);
//            return response()->json($beneficiaryList);
            return BudgetResouce::collection($budgetList)->additional([
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
    public function add(StoreBudgetRequest $request): \Illuminate\Http\JsonResponse|BudgetResouce
    {
        try {
            $data = $this->budgetService->save($request);
            activity("Budget")
                ->causedBy(auth()->user())
                ->performedOn($data)
                ->log('Budget Created!');
            return BudgetResouce::make($data)->additional([
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
    public function show($id): \Illuminate\Http\JsonResponse|BudgetResouce
    {
        try {
            $budget = $this->budgetService->get($id);
            if ($budget) {
                return BudgetResouce::make($budget)->additional([
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
    public function update(UpdateBudgetRequest $request, $id): \Illuminate\Http\JsonResponse|BudgetResouce
    {
        try {
            $data = $this->budgetService->update($request);
            activity("Budget")
                ->causedBy(auth()->user())
                ->performedOn($data)
                ->log('Budget Updated!');
            return BudgetResouce::make($data)->additional([
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
            $this->budgetService->delete($id);
            activity("Budget")
                ->causedBy(auth()->user())
                ->log('Budget Deleted!!');
            return response()->json([
                'success' => true,
                'message' => $this->deleteSuccessMessage,
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function getProjection(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->budgetService->getProjection($request);
            return response()->json([
                'data' => $data,
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }
}