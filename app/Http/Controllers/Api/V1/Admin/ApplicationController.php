<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Constants\ApplicationStatus;
use App\Http\Services\Admin\Application\CommitteeApplicationService;
use App\Http\Services\Admin\Application\CommitteeListService;
use App\Http\Services\Admin\Application\OfficeApplicationService;
use App\Http\Traits\RoleTrait;
use App\Models\Beneficiary;
use App\Models\PMTScore;
use App\Http\Requests\Admin\Application\UpdateStatusRequest;
use App\Models\Application;
use App\Models\Committee;
use App\Models\CommitteePermission;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\MobileOperator;
use App\Models\AllowanceProgram;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\LocationTrait;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Http\Traits\BeneficiaryTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\AuthBasicErrorException;
use App\Http\Requests\Admin\Application\ApplicationRequest;
use App\Http\Services\Admin\Application\ApplicationService;
use App\Http\Requests\Admin\Application\MobileOperatorRequest;
use App\Http\Services\Admin\Application\MobileOperatorService;
use App\Http\Resources\Admin\Application\MobileOperatorResource;
use App\Http\Requests\Admin\Application\ApplicationVerifyRequest;
use App\Http\Requests\Admin\Application\MobileOperatorUpdateRequest;
use Illuminate\Validation\ValidationException;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;

class ApplicationController extends Controller
{
    use MessageTrait, BeneficiaryTrait,LocationTrait, LocationTrait, RoleTrait;
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


    public function getWardIdOld()
    {
        $parentsIdOfWards = [];

        $user = auth()->user();
        $user->load('assign_location.parent.parent.parent.parent');


        $query = Location::query();


        //search by assign location id
        $query->when($user->assign_location_id, function ($q, $v) {
            $q->where('parent_id', $v);
        });

        if ($user->office_type) {

            //Upazila type
            if (in_array($user->office_type, [8, 10, 11])) {
                $query->when(request('sub_location_type'), function ($q, $v) {
                    $q->where('type', $v == 1 ? $this->pouro : $this->union);
                });

                //load all wards under by pourosova
                $query->when(request('pouro_id'), function ($q, $v) {
                    $q->where('parent_id', $v);
                });

                //load all wards under by union
                $query->when(request('union_id'), function ($q, $v) {
                    $q->where('parent_id', $v);
                });

                $parentsIdOfWards = $query->pluck('id');
            }

            //city corporation
            if ($user->office_type == 9) {
                $query->when(request('city_thana_id'), function ($q, $v) {
                    $q->where('parent_id', $v)
                        ->where('location_type', 3);
                });

                $parentsIdOfWards = $query->pluck('id');
            }



            //District pouroshova
            if ($user->office_type == 35) {
                $query->when(request('district_pouro_id'), function ($q, $v) {
                    $q->where('parent_id', $v);
                });

                $parentsIdOfWards = $query->pluck('id');
            }


        }


        return Location::whereType($this->ward)
            ->when(request('ward_id'), function ($q, $v) {
                $q->whereId($v);
            }, function ($q) use ($parentsIdOfWards) {
                $q->whereIn('parent_id', $parentsIdOfWards);
            })
            ->pluck('id');


        return $query->get()->groupBy('type');


//        return Location::where('parent_id', $user->assign_location_id)->pluck('id');


        return $user;
//        if ()

    }


    public function getWardId()
    {
        $parentsIdOfWards = [];

        $user = auth()->user();

        $locationType = request('location_type_id');
        $query = Location::query();

        //search by assign location id
        $query->when($user->assign_location_id, function ($q, $v) {
            $q->where('parent_id', $v);
        });

        $query->when(request('division_id'), function ($q, $v) {
            $q->where('parent_id', $v);
        });

        $query->when(request('district_id'), function ($q, $v) {
            $q->where('parent_id', $v);
        });


        //Upazila type
        if ($locationType == 2) {
            $query->when(request('sub_location_type'), function ($q, $v) {
                $q->where('type', $v == 1 ? $this->pouro : $this->union);
            });

            //load all wards under by pourosova
            $query->when(request('pouro_id'), function ($q, $v) {
                $q->where('parent_id', $v);
            });

            //load all wards under by union
            $query->when(request('union_id'), function ($q, $v) {
                $q->where('parent_id', $v);
            });
        }



        //City corporation
        if ($locationType == 3) {
            $query->when(request('city_thana_id'), function ($q, $v) {
                $q->where('parent_id', $v)
                    ->where('location_type', 3);
            });
        }



        //District pouroshova
        if ($locationType == 1) {
            $query->when(request('district_pouro_id'), function ($q, $v) {
                $q->where('parent_id', $v);
            });
        }

        $parentsIdOfWards = $query->pluck('id');

        return Location::whereType($this->ward)
            ->when(request('ward_id'), function ($q, $v) {
                $q->whereId($v);
            }, function ($q) use ($parentsIdOfWards) {
                $q->whereIn('parent_id', $parentsIdOfWards);
            })
            ->pluck('id');

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
        $location_type_id = $request->query('location_type_id');
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




        $query = Application::query();

        $this->applyUserWiseFiltering($query);

            $query->where(function ($query) use ($filterArrayNameEn, $filterArrayNameBn, $filterArrayFatherNameEn, $filterArrayFatherNameBn, $filterArrayMotherNameEn, $filterArrayMotherNameBn, $filterArrayApplicationId, $filterArrayNomineeNameEn, $filterArrayNomineeNameBn, $filterArrayAccountNo, $filterArrayNidNo, $filterArrayListTypeId, $filterArrayProgramId) {
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
                    ->orWhere($filterArrayProgramId)
                ;
            });

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }


        $query->with('current_location', 'permanent_location.parent.parent.parent.parent', 'program',
            'gender', 'pmtScore'
        )
         ->orderBy('score')
        ;


        return $query->paginate($perPage, ['*'], 'page',$page);
    }


    public function getColumnValue($column, $application)
    {
        return match ($column) {
            'name_en' =>  $application->name_en,
            'program.name_en' => $application->program?->name_en,
            'application_id' => $application->application_id,
            'status' => $application->getStatus(),
            'score' => $application->score,
            'account_number' => $application->account_number,
            'verification_number' => $application->verification_number,
            'division' => $application->division?->name_en,
            'district' => $application->district?->name_en,
            'location' => $application->cityCorporation?->name_en ?: ($application->districtPouroshova?->name_en ?: $application->upazila?->name_en),
            'union_pouro_city' => $application->thana?->name_en ?: ($application->union?->name_en ?: $application->pourashava?->name_en),
            'ward' => $application->ward?->name_en,
            'father_name_en' => $application->father_name_en,
            'mother_name_en' => $application->mother_name_en,
            'marital_status' => $application->marital_status,
            'spouse_name_en' => $application->spouse_name_en,
            'nominee_en' => $application->nominee_en,
            'nominee_relation_with_beneficiary' => $application->nominee_relation_with_beneficiary,
            'mobile' => $application->mobile,
        };
    }


    public function getTableHeaders()
    {
        return [
            'name_en' =>  'নাম',
            'program.name_en' => 'প্রোগ্রাম নাম',
            'application_id' => 'আইডি',
            'status' => 'স্ট্যাটাস',
            'score' => 'প্রোভার্টি স্কোর',
            'account_number' => 'একাউন্ট নং',
            'verification_number' => 'ভেরিফিকেশন নম্বর',
            'division' => 'বিভাগ',
            'district' => 'জেলা',
            'location' => 'সিটি / জেলা পৌর / উপজেলা',
            'union_pouro_city' => 'থানা /ইউনিয়ন /পৌর',
            'ward' => 'ওয়ার্ড',
            'father_name_en' => 'পিতার নাম',
            'mother_name_en' => 'মাতার নাম',
            'marital_status' => 'বৈবাহিক অবস্থা',
            'spouse_name_en' => 'স্বামী বা স্ত্রী নাম',
            'nominee_en' => 'নমিনি',
            'nominee_relation_with_beneficiary' => 'নমিনির সাথে সম্পর্ক',
            'mobile' => 'মোবাইল',
        ];
    }


    public function formatApplicationData($applications, $columns)
    {
        $data = [];

        foreach ($applications as $key => $application) {
            foreach ($columns as $column) {
                $data[$key][$column] = $this->getColumnValue($column, $application);
            }
        }

        return $data;
    }


    public function getPdf(Request $request)
    {
        $applications = $this->getApplicationsForPdf($request);
        $applications = $this->formatApplicationData($applications, $request->selectedColumns);
        $headers = $this->getTableHeaders();

        $data = ['applications' => $applications, 'headers' => $headers, 'columns' => $request->selectedColumns];


        $pdf = LaravelMpdf::loadView('reports.application', $data, [],
            [
                'mode' => 'utf-8',
                'format' => 'A4-P',
                'title' => 'আবেদনের তালিকা',
                'orientation' => 'L',
                'default_font_size' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_header' => 10,
                'margin_footer' => 10,
            ]);


        $fileName = 'আবেদনের_তালিকা_' . now()->timestamp . '_'. auth()->id() . '.pdf';

        $pdfPath = public_path("/pdf/$fileName");

        $pdf->save($pdfPath);

        return $this->sendResponse(['url' => asset("/pdf/$fileName")]);


    }


    public function getApplicationsForPdf($request)
    {
        $searchText = $request->query('searchText');
        $application_id = $request->query('application_id');
        $nominee_name = $request->query('nominee_name');
        $account_no = $request->query('account_no');
        $nid_no = $request->query('nid_no');
        $list_type_id = $request->query('list_type_id');
        $program_id = $request->query('program_id');

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
        }

        if($application_id){
            $filterArrayApplicationId[] = ['application_id', 'LIKE', '%' . $application_id . '%'];
        }

        if($nominee_name){
            $filterArrayNomineeNameEn[] = ['nominee_en', 'LIKE', '%' . $nominee_name . '%'];
            $filterArrayNomineeNameBn[] = ['nominee_bn', 'LIKE', '%' . $nominee_name . '%'];
        }

        if($account_no){
            $filterArrayAccountNo[] = ['account_number', 'LIKE', '%' . $account_no . '%'];
        }

        if($nid_no){
            $filterArrayNidNo[] = ['verification_number', 'LIKE', '%' . $nid_no . '%'];
        }

        if($list_type_id){
            $filterArrayListTypeId[] = ['forward_committee_id', '=', $list_type_id];
        }

        if($program_id){
            $filterArrayProgramId[] = ['program_id', '=', $program_id];
        }


        $query = Application::query();

        $this->applyUserWiseFiltering($query);

        $query->where(function ($query) use ($filterArrayNameEn, $filterArrayNameBn, $filterArrayFatherNameEn, $filterArrayFatherNameBn, $filterArrayMotherNameEn, $filterArrayMotherNameBn, $filterArrayApplicationId, $filterArrayNomineeNameEn, $filterArrayNomineeNameBn, $filterArrayAccountNo, $filterArrayNidNo, $filterArrayListTypeId, $filterArrayProgramId) {
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
                ->orWhere($filterArrayProgramId)
            ;
        })
            ->when($request->has('status'), function ($q, $v) {
                $q->where('status', request('status'));
            })

            ->with('program', 'district', 'districtPouroshova', 'cityCorporation',
                'upazila', 'thana', 'union', 'pourashava', 'ward'
            )

            ->orderBy('score')
        ;

        return $query->get();
    }





    public function applyUserWiseFiltering($query)
    {
        $user = auth()->user()->load('assign_location.parent.parent.parent.parent');

        if ($user->hasRole($this->officeHead) && $user->office_type) {
            return (new OfficeApplicationService())->getApplications($query, $user);
        }


        if ($user->hasRole($this->committee) && $user->committee_type_id) {
            return (new CommitteeApplicationService())->getApplications($query, $user);
        }

        if ($user->hasRole($this->superAdmin)) {
            return (new OfficeApplicationService())->applyLocationTypeFilter(
                query: $query,
                divisionId: request('division_id'),
                districtId: request('district_id')
            );
        }


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
        ->with('current_location.parent.parent.parent.parent',
                'permanent_location.parent.parent.parent.parent',
                'program',  // Assuming you have defined this relationship in your Application model
            'allowAddiFields.allowAddiFieldValues', // Assuming you have defined these relationships in your models
                )->first();
                $image=Application::where('id','=',$id)
                ->value('image');
                $image= asset('uploads/application/' . $application->nominee_image);

                // Grouping additional fields by ID
        $groupedAdditionalFields = $application->allowAddiFields->groupBy('id');

        // Mapping to get only one instance of each additional field with its values
        $uniqueAdditionalFields = $groupedAdditionalFields->map(function ($fields) {
        $additionalField = $fields->first();
        $additionalField->allowAddiFieldValues = $fields->first()->allowAddiFieldValues;
        return $additionalField;

    });

        return \response()->json([
            'application' => $application,
            'image'=>$image,
            // 'id'=>$id

            'unique_additional_fields' => $uniqueAdditionalFields->values(), // Convert to values to remove keys

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
            'permanent_location.parent.parent.parent',


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
     *      path="/admin/",
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



    /**
     * @OA\Get(
     *      path="/admin/application/committee-list",
     *      operationId="getCommitteeList",
     *      tags={"APPLICATION-SELECTION"},
     *      summary="get committee list",
     *      description="Returns committee list",
     *      security={{"bearer_token":{}}},
     *
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
    public function getCommitteeList()
    {
        $user = auth()->user()->load('assign_location.parent.parent.parent.parent');

        $query = Committee::query();
        $query->select('committees.*');
        $query->leftJoin('locations', 'committees.location_id', '=', 'locations.id');

        (new CommitteeListService())->applyCommitteeListFilter($query, $user);

        return $query->get();
    }



    public function checkPermission($request, $user)
    {
        $permission = $user->committeePermission;

        if ($request->status == ApplicationStatus::APPROVE) {
            if (!$permission?->approve) {
                throw ValidationException::withMessages(['Unauthorized action']);
            }
        }

        if ($request->status == ApplicationStatus::FORWARD) {
            if (!$permission?->forward) {
                throw ValidationException::withMessages(['Unauthorized action']);
            }
        }

        if ($request->status == ApplicationStatus::REJECTED) {
            if (!$permission?->reject) {
                throw ValidationException::withMessages(['Unauthorized action']);
            }
        }

        if ($request->status == ApplicationStatus::WAITING) {
            if (!$permission?->waiting) {
                throw ValidationException::withMessages(['Unauthorized action']);
            }
        }

    }




    /**
     *
     * @OA\Post(
     *      path="/admin/application/update-status",
     *      operationId="updateApplicationStatus",
     *      tags={"APPLICATION-SELECTION"},
     *      summary="update application status",
     *      description="update status",
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
     *                      property="applications_id",
     *                      description="id of applications",
     *                      type="array",
     *                      @OA\Items(type="string")
     *                   ),
     *                   @OA\Property(
     *                      property="committee_id",
     *                      description="id of committee",
     *                      type="integer",
     *                   ),
     *                  @OA\Property(
     *                      property="status",
     *                      description="application status",
     *                      type="integer",
     *                   ),
     *                  @OA\Property(
     *                      property="remark",
     *                      description="remark",
     *                      type="string",
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
    public function updateApplications(UpdateStatusRequest $request)
    {
        $user = auth()->user();

        if ($user->committee_type_id) {
            $this->checkPermission($request, $user);
        }

        $query = Application::query();

        $this->applyUserWiseFiltering($query);
        $query->with(['committeeApplication']);
        $query->whereIn('id', $request->applications_id);

        $query->whereNot('status', ApplicationStatus::REJECTED)
            ->whereNot('status', ApplicationStatus::APPROVE);

        $applications = $query->get();

        $data['status'] = $request->status;
        $data['remark'] = $request->remark;

        //Upazila committee & office user
        if ($request->status == ApplicationStatus::FORWARD) {
            $data['forward_committee_id'] = $request->committee_id;
            $this->forwardApplication($request, $applications);

        } else {
            //committee user only
            if ($user->committee_id) {
                $this->changeCommitteeApplicationsStatus($request, $applications, $user->committee_id);
            }
        }

        $total = Application::whereIn('id', $applications->pluck('id'))->update($data);

        return $this->sendResponse($applications, 'Total updated ' . $total);
    }


    public function changeCommitteeApplicationsStatus($request, $applications, $committeeId)
    {
        foreach ($applications as $application) {
            $committeeApplication = $application->committeeApplication()->firstOrNew([
                    'committee_id' => $committeeId
                ]
            );

            $committeeApplication->status = $request->status;
            $committeeApplication->remark = $request->remark;
            $committeeApplication->save();

            if ($request->status == ApplicationStatus::APPROVE) {
                $this->createBeneficiary($application);
            }
        }
    }


    /**
     * @param Application $application
     * @return mixed
     */
    public function createBeneficiary($application)
    {
        if ($application->beneficiary()->doesntExist()) {
            $beneficiary = new Beneficiary(
                [
                    "application_table_id" => $application->id,
                    "program_id" => $application->program_id,
                    "application_id" => $application->application_id,
                    "name_en" => $application->name_en,
                    "name_bn" => $application->name_bn,
                    "mother_name_en" => $application->mother_name_en,
                    "mother_name_bn" => $application->mother_name_bn,
                    "father_name_en" => $application->father_name_en,
                    "father_name_bn" => $application->father_name_bn,
                    "spouse_name_en" => $application->spouse_name_en,
                    "spouse_name_bn" => $application->spouse_name_bn,
                    "identification_mark" => $application->identification_mark,
                    "age" => $application->age,
                    "date_of_birth" => $application->date_of_birth,
//                "nationality" => $application->nationality,
                    "gender_id" => $application->gender_id,
                    "education_status" => $application->education_status,
                    "profession" => $application->profession,
                    "religion" => $application->religion,
                    "marital_status" => $application->marital_status,
                    "email" => $application->email,
                    "verification_type" => $application->verification_type,
                    "verification_number" => $application->verification_number,
                    "image" => $application->image,
                    "signature" => $application->signature,
                    "current_division_id" => $application->current_division_id,
                    "current_district_id" => $application->current_district_id,
                    "current_city_corp_id" => $application->current_city_corp_id,
                    "current_district_pourashava_id" => $application->current_district_pourashava_id,
                    "current_upazila_id" => $application->current_upazila_id,
                    "current_pourashava_id" => $application->current_pourashava_id,
                    "current_thana_id" => $application->current_thana_id,
                    "current_union_id" => $application->current_union_id,
                    "current_ward_id" => $application->current_ward_id,
                    "current_post_code" => $application->current_post_code,
                    "current_address" => $application->current_address,
                    "mobile" => $application->mobile,
                    "permanent_division_id" => $application->permanent_division_id,
                    "permanent_district_id" => $application->permanent_district_id,
                    "permanent_city_corp_id" => $application->permanent_city_corp_id,
                    "permanent_district_pourashava_id" => $application->permanent_district_pourashava_id,
                    "permanent_upazila_id" => $application->permanent_upazila_id,
                    "permanent_pourashava_id" => $application->permanent_pourashava_id,
                    "permanent_thana_id" => $application->permanent_thana_id,
                    "permanent_union_id" => $application->permanent_union_id,
                    "permanent_ward_id" => $application->permanent_ward_id,
                    "permanent_post_code" => $application->permanent_post_code,
                    "permanent_address" => $application->permanent_address,
                    "permanent_mobile" => $application->permanent_mobile,
                    "nominee_en" => $application->nominee_en,
                    "nominee_bn" => $application->nominee_bn,
                    "nominee_verification_number" => $application->nominee_verification_number,
                    "nominee_address" => $application->nominee_address,
                    "nominee_image" => $application->nominee_image,
                    "nominee_signature" => $application->nominee_signature,
                    "nominee_relation_with_beneficiary" => $application->nominee_relation_with_beneficiary,
                    "nominee_nationality" => $application->nominee_nationality,
                    "account_name" => $application->account_name,
                    "account_number" => $application->account_number,
                    "account_owner" => $application->account_owner,
                    "status" => $application->status,
                    "score" => $application->score,
                    "forward_committee_id" => $application->forward_committee_id,
                    "remarks" => $application->remark,
                ]
            );

            return $beneficiary->save();
        }

    }



    public function forwardApplication($request, $applications)
    {
        foreach ($applications as $application) {
            $committeeApplication = $application->committeeApplication()->firstOrNew([
                    'committee_id' => $request->committee_id
                ]
            );

            $committeeApplication->status = $request->status;
            $committeeApplication->remark = $request->remark;
            $committeeApplication->save();
        }
    }


    /**
     * @param $request
     * @param Application[] $applications
     * @return mixed
     */
    public function insertCommitteeApplications($request, $applications, $committeeId)
    {
        foreach ($applications as $application) {
            $committeeApplication = $application->committeeApplication()->firstOrNew([
                'committee_id' => $committeeId
                ]
            );

            $committeeApplication->status = $request->status;
            $committeeApplication->remark = $request->remark;
            $committeeApplication->save();

            $application->forward_committee_id = $committeeId;
            $application->status = $request->status;
            $application->remark = $request->remark;
            $application->save();


            return $committeeApplication;

            $committeeApplication->save();
        }
    }






    /**
     * @OA\Get(
     *      path="/admin/application/permissions",
     *      operationId="getApplicationPermissions",
     *      tags={"APPLICATION-SELECTION"},
     *      summary=" get permission of user",
     *      description="Returns application  permission",
     *      security={{"bearer_token":{}}},
     *
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
    public function getApplicationPermission()
    {

        $user = auth()->user();

        $user->load('assign_location.parent.parent.parent.parent', 'committeePermission');

        return $this->sendResponse(
            [
                'user' => $user,
                'permission' => $this->getPermission($user)
            ]
        );

    }


    public function getPermission($user)
    {
        //superadmin
        if ($user->user_type == 1) {
            return [
                'approve' => false,
                'forward' => false,
                'reject' => false,
                'waiting' => false,
            ];
        }



        //if office user
        if ($user->office_type) {
            $canForward = in_array($user->office_type, [8, 9, 10, 11, 35]);

            return [
                'approve' => false,
                'forward' => $canForward,
                'reject' => false,
                'waiting' => false,
            ];
        }

        //committee user

        return [
            'approve' => (bool) $user->committeePermission?->approve,
            'forward' => (bool) $user->committeePermission?->forward,
            'reject' => (bool) $user->committeePermission?->reject,
            'waiting' => (bool) $user->committeePermission?->waiting,
        ];
    }
    public function onlineApplicationCheck(){
        $division=55;
        $division_cut_off = DB::select("
        SELECT poverty_score_cut_offs.*, financial_years.financial_year AS financial_year, financial_years.end_date
        FROM poverty_score_cut_offs
        JOIN financial_years ON financial_years.id = poverty_score_cut_offs.financial_year_id
        WHERE poverty_score_cut_offs.location_id = ? AND poverty_score_cut_offs.default = 1
        ORDER BY financial_years.end_date DESC LIMIT 1", [$division]);
        $division_cut_off=$division_cut_off[0]->id;
        return $division_cut_off;

    }

}
