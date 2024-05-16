<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Budget\ApproveBudgetRequest;
use App\Http\Requests\Admin\Budget\StoreBudgetRequest;
use App\Http\Requests\Admin\Budget\UpdateBudgetRequest;
use App\Http\Resources\Admin\Budget\BudgetDetailResource;
use App\Http\Resources\Admin\Budget\BudgetResource;
use App\Http\Services\Admin\BudgetAllotment\BudgetService;
use App\Http\Traits\MessageTrait;
use App\Models\Budget;
use Illuminate\Http\Request;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;
use Mockery\Exception;
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
     * @param BudgetService $budgetService
     */
    public function __construct(BudgetService $budgetService)
    {
        $this->budgetService = $budgetService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserLocation(): \Illuminate\Http\JsonResponse
    {
        $uerLocation = $this->budgetService->getUserLocation();
        return response()->json([
            'data' => $uerLocation,
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $budgetList = $this->budgetService->list($request);
//            return response()->json($beneficiaryList);
            return BudgetResource::collection($budgetList)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param StoreBudgetRequest $request
     * @return BudgetResource|\Illuminate\Http\JsonResponse
     */
    public function add(StoreBudgetRequest $request): \Illuminate\Http\JsonResponse|BudgetResource
    {
        try {
            $data = $this->budgetService->save($request);
            return BudgetResource::make($data)->additional([
                'success' => true,
                'message' => $this->insertSuccessMessage,
            ]);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), [], 500);
        }
    }

    /**
     * @param $id
     * @return BudgetResource|\Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse|BudgetResource
    {
        try {
            $budget = $this->budgetService->get($id);
            if ($budget) {
                return BudgetResource::make($budget)->additional([
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
     * @return BudgetResource|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateBudgetRequest $request, $id): \Illuminate\Http\JsonResponse|BudgetResource
    {
        try {
            $budget = Budget::findOrFail($id);
            if (!$budget->process_flag) {
                throw new Exception('Budget not yet processed', ResponseAlias::HTTP_BAD_REQUEST);
            } elseif ($budget->is_approved) {
                throw new Exception('Budget Already Approved', ResponseAlias::HTTP_BAD_REQUEST);
            }
            $data = $this->budgetService->update($request, $id);
            return BudgetResource::make($data)->additional([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function approve(ApproveBudgetRequest $request, $id): \Illuminate\Http\JsonResponse|BudgetResource
    {
        try {
            $beforeUpdate = Budget::findOrFail($id);
            if (!$beforeUpdate->process_flag) {
                throw new Exception('Budget not yet processed', ResponseAlias::HTTP_BAD_REQUEST);
            } elseif ($beforeUpdate->is_approved) {
                throw new Exception('Budget Already Approved', ResponseAlias::HTTP_BAD_REQUEST);
            }
            $data = $this->budgetService->approve($request, $id);
//            $afterUpdate = Budget::findOrFail($id);
            Helper::activityLogUpdate($data, $beforeUpdate, "Budget", "Budget Approved!");
            return BudgetResource::make($data)->additional([
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
            $budget = Budget::findOrFail($id);
            if ($budget->is_approved == 1) {
                throw new Exception('Budget Already Approved', ResponseAlias::HTTP_BAD_REQUEST);
            }
            $this->budgetService->delete($id);
            Helper::activityLogDelete($budget, '', 'Budget', 'Budget Deleted!!');
            return response()->json([
                'success' => true,
                'message' => $this->deleteSuccessMessage,
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function getProjection(Request $request, $program_id, $financial_year_id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->budgetService->getProjection($request, $program_id, $financial_year_id);
            return response()->json([
                'data' => $data,
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function detailList($budget_id, Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $budget = Budget::find($budget_id);
            if (!$budget) {
                return response()->json([
                    'success' => false,
                    'message' => $this->notFoundMessage,
                ], ResponseAlias::HTTP_OK);
            }
            $budgetDetailList = $this->budgetService->detailList($budget_id, $request);
//            return response()->json($budgetList);
            return BudgetDetailResource::collection($budgetDetailList)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function detailUpdate($budget_id, Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $budget = Budget::find($budget_id);
            if (!$budget) {
                return response()->json([
                    'success' => false,
                    'message' => $this->notFoundMessage,
                ], ResponseAlias::HTTP_OK);
            }
            $this->budgetService->detailUpdate($budget_id, $request);
            return response()->json([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function getBudgetDetailListPdf($budget_id, Request $request): ResponseAlias
    {
        $budgetDetailList = $this->budgetService->detailList($budget_id, $request, true);
        $user = auth()->user()->load('assign_location.parent.parent.parent.parent');
        $generated_by = $user->full_name;
        $assign_location = '';
        if ($user->assign_location) {
            $assign_location .= ', ' . (app()->isLocale('bn') ? $user->assign_location?->name_bn : $user->assign_location?->name_en);
            if ($user->assign_location?->parent) {
                $assign_location .= ', ' . (app()->isLocale('bn') ? $user->assign_location?->parent?->name_bn : $user->assign_location?->parent?->name_en);
                if ($user->assign_location?->parent?->parent) {
                    $assign_location .= ', ' . (app()->isLocale('bn') ? $user->assign_location?->parent?->parent?->name_bn : $user->assign_location?->parent?->parent?->name_en);
//                    if ($user->assign_location?->parent?->parent?->parent) {
//                        $assign_location .= ', ' . $user->assign_location?->parent?->parent?->parent?->name_bn;
//                    }
                }
            }
        }
        $data = ['budgetDetailList' => $budgetDetailList, 'generated_by' => $generated_by, 'assign_location' => $assign_location];
        $pdf = LaravelMpdf::loadView('reports.budget.budget_detail_list', $data, [],
            [
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'title' => __("budget.page_title"),
                'orientation' => 'L',
                'default_font_size' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_header' => 10,
                'margin_footer' => 5,
            ]);

        return \Illuminate\Support\Facades\Response::stream(
            function () use ($pdf) {
                echo $pdf->output();
            },
            200,
            [
                'Content-Type' => 'application/pdf;charset=utf-8',
                'Content-Disposition' => 'inline; filename="preview.pdf"',
            ]);

//        $fileName = 'উপকারভোগী_পরিবর্তন_তালিকা_' . now()->timestamp . '_' . auth()->id() . '.pdf';
//        $pdfPath = public_path("/pdf/$fileName");
//        $pdf->save($pdfPath);
//        return $this->sendResponse(['url' => asset("/pdf/$fileName")]);
    }

}
