<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\MobileOperator;
use App\Models\AllowanceProgram;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\LocationTrait;
use App\Http\Controllers\Controller;
use App\Http\Traits\BeneficiaryTrait;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\AuthBasicErrorException;
use App\Http\Requests\Admin\Application\ApplicationRequest;
use App\Http\Services\Admin\Application\ApplicationService;
use App\Http\Requests\Admin\Application\MobileOperatorRequest;
use App\Http\Services\Admin\Application\MobileOperatorService;
use App\Http\Resources\Admin\Application\MobileOperatorResource;
use App\Http\Requests\Admin\Application\ApplicationVerifyRequest;
use App\Http\Requests\Admin\Application\MobileOperatorUpdateRequest;

class ApplicationController extends Controller
{
    use MessageTrait, BeneficiaryTrait,LocationTrait;
    private $applicationService;

    public function __construct(ApplicationService $applicationService , MobileOperatorService $mobileoperatorService) {
        $this->applicationService= $applicationService;
        $this->mobileoperatorService= $mobileoperatorService;
    }

    public function getBeneficiaryByLocation(){
        $beneficiaries = $this->getBeneficiary();
        $applications = $this->applications();
    }

    /* -------------------------------------------------------------------------- */
    /*                         online application Methods                         */
    /* -------------------------------------------------------------------------- */

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
             'id' => $data->id,
            'message' => $this->insertSuccessMessage,
        ], 200);

    }


    /* -------------------------------------------------------------------------- */
    /*                        Application Selection Methods                       */
    /* -------------------------------------------------------------------------- */

    /**
    * @OA\Get(
    *     path="/admin/application/get",
    *      operationId="getAllApplicationPaginated",
    *       tags={"APPLICATION-SELECTION"},
    *      summary="get paginated Applications with advance search",
    *      description="get paginated applications with advance search",
    *      security={{"bearer_token":{}}},
    *     @OA\Parameter(
    *         name="searchText",
    *         in="query",
    *         description="search by name",
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="application_id",
    *         in="query",
    *         description="search by application id",
    *         @OA\Schema(type="text")
    *     ),
    *     @OA\Parameter(
    *         name="nominee_name",
    *         in="query",
    *         description="search by nominee name",
    *         @OA\Schema(type="text")
    *     ),
    *     @OA\Parameter(
    *         name="account_no",
    *         in="query",
    *         description="search by account number",
    *         @OA\Schema(type="number")
    *     ),
    *     @OA\Parameter(
    *         name="nid_no",
    *         in="query",
    *         description="search by nid number",
    *         @OA\Schema(type="number")
    *     ),
    *     @OA\Parameter(
    *         name="list_type_id",
    *         in="query",
    *         description="search by list type name",
    *         @OA\Schema(type="number")
    *     ),
    *     @OA\Parameter(
    *         name="program_id",
    *         in="query",
    *         description="search by program name",
    *         @OA\Schema(type="number")
    *     ),
    *     @OA\Parameter(
    *         name="division_id",
    *         in="query",
    *         description="search by division name",
    *         @OA\Schema(type="number")
    *     ),
    *     @OA\Parameter(
    *         name="district_id",
    *         in="query",
    *         description="search by district name",
    *         @OA\Schema(type="number")
    *     ),
    *     @OA\Parameter(
    *         name="location_type_id",
    *         in="query",
    *         description="search by location type name",
    *         @OA\Schema(type="number")
    *     ),
    *     @OA\Parameter(
    *         name="thana_id",
    *         in="query",
    *         description="search by thana name",
    *         @OA\Schema(type="number")
    *     ),
    *     @OA\Parameter(
    *         name="union_id",
    *         in="query",
    *         description="search by union name",
    *         @OA\Schema(type="number")
    *     ),
    *     @OA\Parameter(
    *         name="city_id",
    *         in="query",
    *         description="search by city name",
    *         @OA\Schema(type="number")
    *     ),
    *     @OA\Parameter(
    *         name="city_thana_id",
    *         in="query",
    *         description="search by city thana name",
    *         @OA\Schema(type="number")
    *     ),
    *     @OA\Parameter(
    *         name="district_pouro_id",
    *         in="query",
    *         description="search by district pouro name",
    *         @OA\Schema(type="number")
    *     ),
    *     @OA\Parameter(
    *         name="perPage",
    *         in="query",
    *         description="number of committee per page",
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
    public function getAllApplicationPaginated(Request $request){

        $searchText = $request->query('searchText');
        $application_id = $request->query('application_id');
        $nominee_name = $request->query('nominee_name');
        $account_no = $request->query('account_no');
        $nid_no = $request->query('nid_no');
        $list_type_id = $request->query('list_type_id');
        $program_id = $request->query('program_id');
        $division_id = $request->query('division_id');
        $district_id = $request->query('district_id');
        $location_type_id = $request->query('location_type_id');
        $thana_id = $request->query('thana_id');
        $union_id = $request->query('union_id');
        $city_id = $request->query('city_id');
        $city_thana_id = $request->query('city_thana_id');
        $district_pouro_id = $request->query('district_pouro_id');
        $perPage = $request->query('perPage');
        $page = $request->query('page');

        $filterArrayNameEn = [];
        $filterArrayNameBn = [];
        $filterArrayFatherNameEn = [];
        $filterArrayFatherNameBn = [];
        $filterArrayMotherNameEn = [];
        $filterArrayMotherNameBn = [];
        $filterArrayApplicationId = [];
        $filterArrayNomineeNameEn = [];
        $filterArrayNomineeNameBn = [];
        $filterArrayAccountNo = [];
        $filterArrayNidNo = [];
        $filterArrayListTypeId = [];
        $filterArrayProgramId = [];

        if($searchText){
            $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayMotherNameEn[] = ['mother_name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayMotherNameBn[] = ['mother_name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayFatherNameEn[] = ['father_name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayFatherNameBn[] = ['father_name_bn', 'LIKE', '%' . $searchText . '%'];
            $page = 1;

        }

        if($application_id){
            $filterArrayApplicationId[] = ['application_id', 'LIKE', '%' . $application_id . '%'];
            $page = 1;

        }

        if($nominee_name){
            $filterArrayNomineeNameEn[] = ['nominee_en', 'LIKE', '%' . $nominee_name . '%'];
            $filterArrayNomineeNameBn[] = ['nominee_bn', 'LIKE', '%' . $nominee_name . '%'];
            $page = 1;

        }

        if($account_no){
            $filterArrayAccountNo[] = ['account_number', 'LIKE', '%' . $account_no . '%'];
            $page = 1;

        }

        if($nid_no){
            $filterArrayNidNo[] = ['verification_number', 'LIKE', '%' . $nid_no . '%'];
            $page = 1;

        }

        if($list_type_id){
            $filterArrayListTypeId[] = ['forward_committee_id', '=', $list_type_id];
            $page = 1;

        }

        if($program_id){
            $filterArrayProgramId[] = ['program_id', '=', $program_id];
            $page = 1;

        }

        if($location_type_id){
            $filterArrayLocationTypeId[] = ['location_type_id', '=', $location_type_id];
        }


        $applications = Application::query()->where(function ($query) use ($filterArrayNameEn, $filterArrayNameBn, $filterArrayFatherNameEn, $filterArrayFatherNameBn, $filterArrayMotherNameEn, $filterArrayMotherNameBn, $filterArrayApplicationId, $filterArrayNomineeNameEn, $filterArrayNomineeNameBn, $filterArrayAccountNo, $filterArrayNidNo, $filterArrayListTypeId, $filterArrayProgramId, $district_id, $division_id, $thana_id, $union_id, $city_id, $city_thana_id, $district_pouro_id) {
            $query->where($filterArrayNameEn)
                ->orWhere($filterArrayNameBn)
                ->orWhere($filterArrayFatherNameEn)
                ->orWhere($filterArrayFatherNameBn)
                ->orWhere($filterArrayMotherNameEn)
                ->orWhere($filterArrayMotherNameBn)
                ->orWhere($filterArrayApplicationId)
                ->orWhere($filterArrayNomineeNameEn)
                ->orWhere($filterArrayNomineeNameBn)
                ->orWhere($filterArrayAccountNo)
                ->orWhere($filterArrayNidNo)
                ->orWhere($filterArrayListTypeId)
                ->orWhere($filterArrayProgramId);
                if($division_id){
                $page = 1;

                    $query->whereHas('permanent_location', function ($query) use ($division_id) {
                        $query->where('id', $division_id)
                        ->orWhereHas('parent', function ($query) use ($division_id) {
                            $query->where('id', $division_id)
                                ->orWhereHas('parent', function ($query) use ($division_id) {
                                    $query->where('id', $division_id)
                                        ->orWhereHas('parent', function ($query) use ($division_id) {
                                            $query->where('id', $division_id)
                                                ->where('type', $this->division);
                                        });
                                });
                        });
                    });
                }
                if ($district_id) {
                $page = 1;

                    $query->whereHas('permanent_location', function ($query) use ($district_id) {
                        $query->where('id', $district_id)
                        ->orWhereHas('parent', function ($query) use ($district_id) {
                            $query->where('id', $district_id)
                                ->orWhereHas('parent', function ($query) use ($district_id) {
                                    $query->where('id', $district_id)
                                        ->orWhereHas('parent', function ($query) use ($district_id) {
                                            $query->where('id', $district_id)
                                                ->where('type', $this->district);
                                        });
                                });
                        });
                    });
                }

                if($thana_id){
                $page = 1;

                    $query->whereHas('permanent_location', function ($query) use ($thana_id) {
                        $query->where('id', $thana_id)
                        ->orWhereHas('parent', function ($query) use ($thana_id) {
                            $query->where('id', $thana_id)
                                ->orWhereHas('parent', function ($query) use ($thana_id) {
                                    $query->where('id', $thana_id)
                                        ->orWhereHas('parent', function ($query) use ($thana_id) {
                                            $query->where('id', $thana_id)
                                                ->where('type', $this->thana);
                                        });
                                });
                        });
                    });
                }

                if($union_id){
                $page = 1;

                    $query->whereHas('permanent_location', function ($query) use ($union_id) {
                        $query->where('id', $union_id)
                        ->orWhereHas('parent', function ($query) use ($union_id) {
                            $query->where('id', $union_id)
                                ->orWhereHas('parent', function ($query) use ($union_id) {
                                    $query->where('id', $union_id)
                                        ->orWhereHas('parent', function ($query) use ($union_id) {
                                            $query->where('id', $union_id)
                                                ->where('type', $this->union);
                                        });
                                });
                        });
                    });
                }

                if($city_id){
                $page = 1;

                    $query->whereHas('permanent_location', function ($query) use ($city_id) {
                        $query->where('id', $city_id)
                        ->orWhereHas('parent', function ($query) use ($city_id) {
                            $query->where('id', $city_id)
                                ->orWhereHas('parent', function ($query) use ($city_id) {
                                    $query->where('id', $city_id)
                                        ->orWhereHas('parent', function ($query) use ($city_id) {
                                            $query->where('id', $city_id)
                                                ->where('type', $this->city);
                                        });
                                });
                        });
                    });
                }

                if($city_thana_id){
                $page = 1;

                    $query->whereHas('permanent_location', function ($query) use ($city_thana_id) {
                        $query->where('id', $city_thana_id)
                        ->orWhereHas('parent', function ($query) use ($city_thana_id) {
                            $query->where('id', $city_thana_id)
                                ->orWhereHas('parent', function ($query) use ($city_thana_id) {
                                    $query->where('id', $city_thana_id)
                                        ->orWhereHas('parent', function ($query) use ($city_thana_id) {
                                            $query->where('id', $city_thana_id)
                                                ->where('type', $this->thana);
                                        });
                                });
                        });
                    });
                }

                if($district_pouro_id){
                $page = 1;

                    $query->whereHas('permanent_location', function ($query) use ($district_pouro_id) {
                        $query->where('id', $district_pouro_id)
                        ->orWhereHas('parent', function ($query) use ($district_pouro_id) {
                            $query->where('id', $district_pouro_id)
                                ->orWhereHas('parent', function ($query) use ($district_pouro_id) {
                                    $query->where('id', $district_pouro_id)
                                        ->orWhereHas('parent', function ($query) use ($district_pouro_id) {
                                            $query->where('id', $district_pouro_id)
                                                ->where('type', $this->districtPouroshava);
                                        });
                                });
                        });
                    });
                }

        })
        ->with('current_location','permanent_location.parent.parent.parent','program','gender')
        ->latest()
        // ->orderBy('score', 'asc')
        ->paginate($perPage, ['*'], 'page',$page);
        return $applications;


    }
    /**
     * @OA\Get(
     *      path="/admin/application/get/{id}",
     *      operationId="getApplicationById",
     *      tags={"APPLICATION-SELECTION"},
     *      summary=" get a single application",
     *      description="Returns application  by id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of application to return",
     *         in="path",
     *         name="id",
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
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
     *          response=404,
     *          description="Not Found!"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *     )
     */
   public function getApplicationById($id){
  
    $application = Application::where('id','=',$id)
    ->with('current_location',
            'permanent_location.parent.parent.parent',
         
            // 'povertyValues.variable.subVariables',
            // 'application',
            // 'variable',
           
            'poverty_score.children',
            'poverty_score_value'
            )->first();
            $image=Application::where('id','=',$id)
            ->pluck('image');
          
        
        return \response()->json([
            'application' => $application,
            'image'=>$image,
            'id'=>$id
         


            ],Response::HTTP_OK);

    }
    /**
     * @OA\Get(
     *      path="/global/applicants copy/{id}",
     *      operationId="getApplicationCopyById",
     *      tags={"GLOBal"},
     *      summary=" get a applicant's copy",
     *      description="Returns application  by id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of application to return",
     *         in="path",
     *         name="id",
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
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
     *          response=404,
     *          description="Not Found!"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *     )
     */
   public function getApplicationCopyById($id){
  
    $application = Application::where('id','=',$id)
    ->with('current_location.parent.parent.parent',
            'permanent_location.parent.parent.parent'
         
            // 'povertyValues.variable.subVariables',
            // 'application',
            // 'variable',
           
           
            )->first();
            $image=Application::where('id','=',$id)
            ->pluck('image');
          
        
        return \response()->json([
            'application' => $application,
           


            ],Response::HTTP_OK);

    }
 
  /**
    * @OA\Get(
    *     path="/admin/mobile-operator/get",
    *      operationId="getAllMobileOperatorPaginated",
    *      tags={"APPLICATION-SELECTION"},
    *      summary="get paginated mobileoperator",
    *      description="get paginated mobileoperator",
    *      security={{"bearer_token":{}}},
    *     @OA\Parameter(
    *         name="searchText",
    *         in="query",
    *         description="search by mobileoperator",
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="perPage",
    *         in="query",
    *         description="number of mobileoperator per page",
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

 public function getAllMobileOperatorPaginated(Request $request){
        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage');
        $page = $request->query('page');

        $filterArrayValue=[];
       

        if ($searchText) {
            $filterArrayValue[] = ['operator', 'LIKE', '%' . $searchText . '%'];
          
        }
        $globalsetting = MobileOperator::query()
        ->where(function ($query) use ($filterArrayValue) {
            $query->where($filterArrayValue);
                  
        })
       
        ->latest()
        ->paginate($perPage, ['*'], 'page');

        return MobileOperatorResource::collection($globalsetting)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
 }
 /**
     *
     * @OA\Post(
     *      path="/admin/mobile-operator/insert",
     *      operationId="insertMobileOperator",
     *      tags={"APPLICATION-SELECTION"},
     *      summary="insert a mobile-operator",
     *      description="insert a mobile-operator",
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
     *                  
     *                   @OA\Property(
     *                      property="operator",
     *                      description="Value of operator",
     *                      type="text",
     *                   ),
     *                 
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
  
        public function insertMobileOperator(MobileOperatorRequest $request){

        try {
            $mobile = $this->mobileoperatorService->createMobileOperator($request);
        
            return MobileOperatorResource::make($mobile)->additional([
                'success' => true,
                'message' => $this->insertSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

         /**
     * @OA\Get(
     *      path="/admin/mobile-operator/destroy/{id}",
     *      operationId="destroyMobileOperator",
     *      tags={"APPLICATION-SELECTION"},
     *      summary=" destroy global setting",
     *      description="Returns mobile-operator destroy by id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of mobile-operator to return",
     *         in="path",
     *         name="id",
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
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
     *          response=404,
     *          description="Not Found!"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *     )
     */

        public function destroyMobileOperator($id)
    {

        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:mobile_operators,id',
        ]);

        $validator->validated();

        $mobile = MobileOperator::whereId($id)->first();

    
        if($mobile){
            $mobile->delete();
        }
     
         return $this->sendResponse($mobile, $this->deleteSuccessMessage, Response::HTTP_OK);

    }
     /**
     *
     * @OA\Post(
     *      path="/admin/mobile-operator/update",
     *      operationId="updateMobileOperator",
     *      tags={"APPLICATION-SELECTION"},
     *      summary="update a Mobile Operator",
     *      description="update a Mobile Operator",
     *      security={{"bearer_token":{}}},
     *
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="enter inputs",
     *
     *            @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(
     *                   @OA\Property(
     *                      property="id",
     *                      description="id of the Global Setting",
     *                      type="integer",
     *                   ),
     *                   @OA\Property(
     *                      property="operator",
     *                      description="operator",
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
    public function updateMobileOperator(MobileOperatorUpdateRequest $request){

        try {
            $mobile = $this->mobileoperatorService->updateMobileOperator($request);
          
            return MobileOperatorResource::make($mobile)->additional([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

}
