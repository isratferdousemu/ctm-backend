<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Exceptions\AuthBasicErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Application\ApplicationRequest;
use App\Http\Requests\Admin\Application\ApplicationVerifyRequest;
use App\Http\Services\Admin\Application\ApplicationService;
use App\Http\Traits\BeneficiaryTrait;
use App\Http\Traits\MessageTrait;
use App\Models\AllowanceProgram;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    public function onlineApplicationRegistration(ApplicationRequest $request){

        // check allowance validation
        $allowance = AllowanceProgram::find($request->program_id);

        // check is marital
        if($allowance){
            if($allowance->is_age_limit == 1){
                // error code => applicant_marital_status
                if(!in_array($request->gender_id, $allowance->ages->pluck('gender_id')->toArray())){
                    throw new AuthBasicErrorException(
                        Response::HTTP_UNPROCESSABLE_ENTITY,
                        $this->applicantGenderTypeTextErrorCode,
                        $this->applicationGenderTypeMessage
                    );
                }else{
                    $genderAge = $allowance->ages->where('gender_id',$request->gender_id)->first();
                    $minAge = $genderAge->min_age;
                    $maxAge = $genderAge->max_age;
                    // get current age form date_of_birth field
                    $birthDate = $request->date_of_birth;
                    $birthDate = explode("-", $birthDate);
                    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
                        ? ((date("Y") - $birthDate[0]) - 1)
                        : (date("Y") - $birthDate[0]));
                    // return $genderAge;
                    // 60 -90 => age is 73
                // age range is minAge to maxAge
                    if($age<$minAge || $age>$maxAge){
                        throw new AuthBasicErrorException(
                            Response::HTTP_UNPROCESSABLE_ENTITY,
                            $this->applicantAgeLimitTextErrorCode,
                            $this->applicantAgeLimitMessage
                        );
                    }

                }

            }
            if($allowance->is_marital == 1){
                // error code =>
                if($allowance->marital_status!=$request->marital_status){
                    throw new AuthBasicErrorException(
                        Response::HTTP_UNPROCESSABLE_ENTITY,
                        $this->applicantMaritalStatusTextErrorCode,
                        $this->applicantMaritalStatusMessage
                    );
                }
            }

        }

        // return gettype(json_decode($request->application_allowance_values)[19]->value);
        $data = $this->applicationService->onlineApplicationRegistration($request);

        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => $this->insertSuccessMessage,
        ], 200);

    }
}
