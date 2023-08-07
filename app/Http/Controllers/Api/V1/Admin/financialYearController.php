<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Systemconfig\FinanacialYear\FinancialRequest;
use App\Http\Resources\Admin\systemconfig\Finanacial\FinancialResource;
use App\Http\Services\Admin\Systemconfig\SystemconfigService;
use App\Http\Traits\MessageTrait;
use Illuminate\Http\Request;

class financialYearController extends Controller
{
    use MessageTrait;
    private $systemconfigService;

    public function __construct(SystemconfigService $systemconfigService) {
        $this->systemconfigService= $systemconfigService;
    }

    /**
     *
     * @OA\Post(
     *      path="/admin/financial-year/insert",
     *      operationId="insertFinancialYear",
     *      tags={"ADMIN-FINANCIAL-YEAR"},
     *      summary="insert a financial-year",
     *      description="insert a financial-year",
     *      security={{"bearer_token":{}}},
     *
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="enter inputs",
     *
     *
     *            @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(
     *                   @OA\Property(
     *                      property="financial_year",
     *                      description="financial year. ex: 2023-2024",
     *                      type="text",
     *
     *                   ),
     *
     *                 ),
     *             ),
     *
     *         ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful Insert operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *
     *          )
     *        )
     *     )
     *
     */
    public function insertFinancialYear(FinancialRequest $request){

        try {
            $financial = $this->systemconfigService->createFinancialYear($request);
            activity("Financial-Year")
            ->causedBy(auth()->user())
            ->performedOn($financial)
            ->log('Financial Year Created !');
            return FinancialResource::make($financial)->additional([
                'success' => true,
                'message' => $this->insertSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }
}
