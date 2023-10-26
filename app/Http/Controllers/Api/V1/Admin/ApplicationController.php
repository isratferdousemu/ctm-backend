<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Application\ApplicationVerifyRequest;
use App\Http\Services\Admin\Application\ApplicationService;
use App\Http\Traits\BeneficiaryTrait;
use App\Http\Traits\MessageTrait;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    use MessageTrait, BeneficiaryTrait;
    private $applicationService;

    public function __construct(ApplicationService $applicationService) {
        $this->applicationService= $applicationService;
    }

    public function getBeneficiaryByLocation(){
        $beneficiaries = $this->getBeneficiary();
        $applications = $this->applications();
    }

    /**
     *
     * @OA\Post(
     *      path="/global/online-application/card-verification",
     *      operationId="onlineApplicationVerifyCard",
     *      tags={"GLOBAL"},
     *      summary="Check Application Card",
     *      description="Check Application Card",
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="enter inputs",
     *
     *            @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(
     *                   @OA\Property(
     *                      property="verification_type",
     *                      description="verification type",
     *                      type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="verification_number",
     *                      description="verification card number",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="date_of_birth",
     *                      description="birth date",
     *                      type="text",
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
    public function onlineApplicationVerifyCard(ApplicationVerifyRequest $request){
        $data = $this->applicationService->onlineApplicationVerifyCard($request);

        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => $this->fetchSuccessMessage,
        ], 200);
    }
    /**
     *
     * @OA\Post(
     *      path="/global/online-application/dis-card-verification",
     *      operationId="onlineApplicationVerifyDISCard",
     *      tags={"GLOBAL"},
     *      summary="Check Application Card",
     *      description="Check Application Card",
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="enter inputs",
     *
     *            @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(
     *                   @OA\Property(
     *                      property="dis_no",
     *                      description="DIS number",
     *                      type="text",
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
    public function onlineApplicationVerifyDISCard(Request $request){
        $data = $this->applicationService->onlineApplicationVerifyCardDIS($request);

        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => $this->fetchSuccessMessage,
        ], 200);
    }
}
