<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Systemconfig\FinanacialYear\FinancialRequest;
use App\Http\Resources\Admin\systemconfig\Finanacial\FinancialResource;
use App\Http\Services\Admin\Systemconfig\SystemconfigService;
use App\Http\Traits\MessageTrait;
use App\Models\FinancialYear;
use Illuminate\Http\Request;

class financialYearController extends Controller
{
    use MessageTrait;
    private $systemconfigService;

    public function __construct(SystemconfigService $systemconfigService) {
        $this->systemconfigService= $systemconfigService;
    }

    /**
    * @OA\Get(
    *     path="/admin/financial-year/get",
    *      operationId="getFinancialPaginated",
    *      tags={"ADMIN-FINANCIAL-YEAR"},
    *      summary="get paginated financial-year",
    *      description="get paginated financial-year",
    *      security={{"bearer_token":{}}},
    *     @OA\Parameter(
    *         name="perPage",
    *         in="query",
    *         description="number of financial-year per page",
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Parameter(
    *         name="page",
    *         in="query",
    *         description="page number",
    *         @OA\Schema(type="integer")
    *     ),
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
    * )
    */

 public function getFinancialPaginated(Request $request){
    // Retrieve the query parameters
    $perPage = $request->query('perPage');
    $page = $request->query('page');


    $financial = FinancialYear::query()
    ->latest()
    ->paginate($perPage, ['*'], 'page');

    return FinancialResource::collection($financial)->additional([
        'success' => true,
        'message' => $this->fetchSuccessMessage,
    ]);
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
