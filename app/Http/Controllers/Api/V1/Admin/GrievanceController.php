<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Constants\ApplicationStatus;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Application\MobileOperatorRequest;
use App\Http\Requests\Admin\Application\MobileOperatorUpdateRequest;
use App\Http\Requests\Admin\Application\UpdateStatusRequest;
use App\Http\Resources\Admin\Application\MobileOperatorResource;
use App\Http\Resources\Admin\GrievanceManagement\GrievanceResource;
use App\Http\Services\Admin\Application\CommitteeApplicationService;
use App\Http\Services\Admin\Application\CommitteeListService;
use App\Http\Services\Admin\Application\MobileOperatorService;
use App\Http\Services\Admin\Application\OfficeApplicationService;
use App\Http\Services\Admin\Application\VerificationService;
use App\Http\Services\Admin\GrievanceManagement\GrievanceComitteeService;
use App\Http\Services\Admin\GrievanceManagement\GrievanceService;
use App\Http\Services\Admin\GrievanceManagement\GrievanceListService;
use App\Http\Services\Admin\GrievanceManagement\OfficeGrievanceService;
use App\Http\Services\Notification\SMSservice;
use App\Http\Traits\BeneficiaryTrait;
use App\Http\Traits\LocationTrait;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\RoleTrait;
use App\Jobs\SendEmail;
use App\Models\AllowanceProgram;
use App\Models\Beneficiary;
use App\Models\Committee;
use App\Models\CommitteePermission;
use App\Models\Grievance;
use App\Models\MobileOperator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;

class GrievanceController extends Controller
{
    use MessageTrait, BeneficiaryTrait, LocationTrait, LocationTrait, RoleTrait;
    private $grievanceService;

    public function __construct(GrievanceService $grievanceService)
    {
        $this->grievanceService = $grievanceService;

    }

    public function getBeneficiaryByLocation()
    {
        $beneficiaries = $this->getBeneficiary();
        $applications = $this->applications();
    }

    // public function onlineGrievanceVerifyCard(GrievanceVerifyRequest $request)
    public function onlineGrievanceVerifyCard(Request $request)
    {
        // return $request->all();
        if ($request->is_existing_beneficiary == 1) {
            $data = Beneficiary::where('beneficiary_id', $request->verification_number)
                ->where('date_of_birth', $request->date_of_birth)
                ->where('status', '=', 1)
                ->first();
            //    return  $data;
            if ($data != null) {
                return response()->json([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Beneficiary ID Verify Successfully',
                ], 201);

            } else {
                return response()->json([
                    'status' => false,
                    'data' => $data,
                    'message' => "Beneficiary ID Doesn't Match !!",
                ], 300);

            }

        } else {
            $data = [
                'nid' => $request->verification_number,
                'dob' => $request->date_of_birth,
            ];

            $data = (new VerificationService)->callVerificationApi($data);
            return response()->json([
                'status' => true,
                'data' => $data,
                'message' => $this->fetchSuccessMessage,
            ], 200);

        }

    }

    // application tracking function
    public function applicationTracking(Request $request)
    {
        $application = Application::with('program', 'committeeApplication')
            ->where('application_id', '=', $request->tracking_no)
            ->orWhere('verification_number', '=', $request->nid)
            ->Where('date_of_birth', '=', $request->date_of_birth)
            ->first();

        return response()->json([
            'status' => true,
            'data' => $application,
            'message' => $this->fetchSuccessMessage,
        ], 200);

    }

    public function grievanceEntry(Request $request)
    {

        //  return $request->all();
        $data = $this->grievanceService->onlineGrievanceEntry($request);
        Helper::activityLogInsert($data, '', 'Grievance entry', 'Grievance Created !');
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => $this->insertSuccessMessage,
        ], 200);

    }

    public function getStatusyId(Request $request)
    {
        $id = $request->id;
        $nid = $request->nid;

        $application = Application::where('id', '=', $id)
            ->update(['status' => 0]);
        $delete = Application::where('verification_number', '=', $nid)
            ->where('status', '=', 9)
            ->delete();
        $application = Application::find($id);

        if (!$application) {

            return response()->json(['error' => 'Application not found'], Response::HTTP_NOT_FOUND);
        }
        $programName = AllowanceProgram::where('id', $application->program_id)->first('name_en');
        $programName = $programName->name_en;

        $message = " Dear $application->name_en. " . "\n Congratulations! Your application has been submitted for the $programName successfully." . "\n Your tracking ID is " . $application->application_id . "\n Save tracking ID for further tracking." . "\n Sincerely," . "\nDepartment of Social Services";

        $this->SMSservice->sendSms($application->mobile, $message);
        return response()->json([

            'application' => $application,
            'id' => $application->application_id,

        ], Response::HTTP_OK);

    }

    public function getClassWiseAmount($allowance, $request)
    {
        $addFields = json_decode($request->application_allowance_values, true) ?: [];

        $class = $allowance->addtionalfield()->where('name_en', 'class')->first();

        if ($class) {
            foreach ($addFields as $addField) {
                if ($addField['allowance_program_additional_fields_id'] == $class->id) {
                    return $allowance->classAmounts()
                        ->where('type_id', $addField['allowance_program_additional_field_values_id'])
                        ->value('amount')
                    ;
                }
            }
        }

    }

    /* -------------------------------------------------------------------------- */
    /*                        Grievance list Methods                       */
    /* -------------------------------------------------------------------------- */

    public function getAllGrievancePaginated(Request $request)
    {

        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage');
        $page = $request->query('page');
        $status = $request->query('status');

        $grievance = Grievance::query();
        $this->applyUserWiseGrievacne($grievance);
        // dd($data->get());

        //  $grievance->with(['grievanceType', 'grievanceSubject', 'program', 'gender', 'district', 'districtPouroshova', 'cityCorporation',
        //     'upazila', 'thana', 'union', 'pourashava', 'ward'])
        //     ->orWhereHas('grievanceType', function ($query) use ($searchText) {
        //         $query->where('title_en', 'LIKE', '%' . $searchText . '%');
        //     })
        //     ->orWhereHas('grievanceSubject', function ($query) use ($searchText) {
        //         $query->where('title_en', 'LIKE', '%' . $searchText . '%');
        //     })
        //     ->orderBy('id', 'asc');
         $grievance->with('grievanceType', 'grievanceSubject', 'program', 'gender', 'district', 'districtPouroshova', 'cityCorporation')
          ->orderBy('id')
;


            return $grievance->paginate($perPage, ['*'], 'page', $page);

            // ->orderBy('id', 'asc')
            // ->latest()
            // ->paginate($perPage, ['*'], 'page');
            // dd($grievance);
            // return $grievance;
            // dd($grievance);

        // return GrievanceResource::collection($grievance)->additional([
        //     'success' => true,
        //     'message' => $this->fetchDataSuccessMessage,
        // ]);

    }

    public function applyUserWiseGrievacne($query)
    {

        // dd($query->get());
        $user = auth()->user()->load('assign_location.parent.parent.parent.parent');
        // dd($user);

        if ($user->hasRole($this->officeHead) && $user->office_type) {
            // dd('ok');
            return (new GrievanceListService())->getGrievance($query, $user);
        }

        if ($user->hasRole($this->committee) && $user->committee_type_id) {
            return (new GrievanceComitteeService())->getGrievance($query, $user);
        }

        if ($user->hasRole($this->superAdmin)) {
            return (new OfficeGrievanceService())->applyLocationTypeFilter(
                query: $query,
                divisionId: request('division_id'),
                districtId: request('district_id')
            );
        }

    }

    public function getColumnValue($column, $application)
    {
        return match ($column) {
            'name' => $application->name,
            'program.name_en' => $application->program?->name_bn,
            'application_id' => $application->application_id,
            'status' => $application->getStatus(),
            'score' => $application->score,
            'account_number' => $application->account_number,
            'verification_number' => $application->verification_number,
            'division' => $application->division?->name_bn,
            'district' => $application->district?->name_bn,
            'location' => $application->cityCorporation?->name_bn ?: ($application->districtPouroshova?->name_bn ?: $application->upazila?->name_bn),
            'union_pouro_city' => $application->thana?->name_bn ?: ($application->union?->name_bn ?: $application->pourashava?->name_bn),
            'ward' => $application->ward?->name_bn,
            'father_name_en' => $application->father_name_bn,
            'mother_name_en' => $application->mother_name_bn,
            'marital_status' => $application->marital_status,
            'spouse_name_en' => $application->spouse_name_bn,
            'nominee_en' => $application->nominee_bn,
            'nominee_relation_with_beneficiary' => $application->nominee_relation_with_beneficiary,
            'mobile' => $application->mobile,
        };
    }

    public function getTableHeaders()
    {
        return [
            'name_en' => 'নাম',
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

        $fileName = 'আবেদনের_তালিকা_' . now()->timestamp . '_' . auth()->id() . '.pdf';

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

        if ($searchText) {
            $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayMotherNameEn[] = ['mother_name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayMotherNameBn[] = ['mother_name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayFatherNameEn[] = ['father_name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayFatherNameBn[] = ['father_name_bn', 'LIKE', '%' . $searchText . '%'];
        }

        if ($application_id) {
            $filterArrayApplicationId[] = ['application_id', 'LIKE', '%' . $application_id . '%'];
        }

        if ($nominee_name) {
            $filterArrayNomineeNameEn[] = ['nominee_en', 'LIKE', '%' . $nominee_name . '%'];
            $filterArrayNomineeNameBn[] = ['nominee_bn', 'LIKE', '%' . $nominee_name . '%'];
        }

        if ($account_no) {
            $filterArrayAccountNo[] = ['account_number', 'LIKE', '%' . $account_no . '%'];
        }

        if ($nid_no) {
            $filterArrayNidNo[] = ['verification_number', 'LIKE', '%' . $nid_no . '%'];
        }

        if ($list_type_id) {
            $filterArrayListTypeId[] = ['forward_committee_id', '=', $list_type_id];
        }

        if ($program_id) {
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
    public function getApplicationById($id)
    {
        $application = Application::where('application_id', '=', $id)
            ->with([
                'current_location.parent.parent.parent.parent',
                'permanent_location.parent.parent.parent.parent',
                'program',
                'allowAddiFields',
                'allowAddiFieldValue.allowAddiField',
                'variable',
                'subvariable',
            ])->first();

        if (!$application) {
            return response()->json(['error' => 'Application not found'], Response::HTTP_NOT_FOUND);
        }

        // Manually filter subvariable based on application_id

        // $emu = $application->image;
        // $image =Storage::url($application->image);

        //    $image = Storage::disk('public')->url($application->image);
        $image = asset('storage/' . $application->image);

        $signature = asset('storage/' . $application->signature);

        $nominee_image = asset('storage/' . $application->nominee_image);

        $nominee_signature = asset('storage/' . $application->nominee_signature);
        //    url(Storage::url( $application->nominee_signature));
        //  Storage::url($application->image);
        // $signature = url('storage/app/' . $application->signature);
        // $nominee_image = url('uploads/application/app' . $application->nominee_image);
        // $nominee_signature = url('uploads/application/app' . $application->nominee_signature);
        $groupedAllowAddiFields = $application->allowAddiFields->groupBy('id')->values();
        $groupedAllowAddiFields = $application->allowAddiFields->groupBy('pivot.allow_addi_fields_id');

        // Get the first item from each group (assuming it's the same for each 'allow_addi_fields_id')
        $distinctAllowAddiFields = $groupedAllowAddiFields->map(function ($group) {
            return $group->first();
        });

        return response()->json([
            // 'emu' => $emu,
            'application' => $application,
            'unique_additional_fields' => $distinctAllowAddiFields,
            'image' => $image,
            'signature' => $signature,
            'nominee_image' => $nominee_image,
            'nominee_signature' => $nominee_signature,

        ], Response::HTTP_OK);
    }
//    public function getApplicationById($id){

//         $application = Application::where('id','=',$id)
//         ->with('current_location.parent.parent.parent.parent',
//                 'permanent_location.parent.parent.parent.parent',
//                 'program',  // Assuming you have defined this relationship in your Application model
//             'allowAddiFields.allowAddiFieldValues', // Assuming you have defined these relationships in your models
//                 )->first();
//                 $image=Application::where('id','=',$id)
//                 ->value('image');
//                 $image= asset('uploads/application/' . $application->nominee_image);

//                 // Grouping additional fields by ID
//         $groupedAdditionalFields = $application->allowAddiFields->groupBy('id');

//         // Mapping to get only one instance of each additional field with its values
//         $uniqueAdditionalFields = $groupedAdditionalFields->map(function ($fields) {
//         $additionalField = $fields->first();
//         $additionalField->allowAddiFieldValues = $fields->first()->allowAddiFieldValues;
//         return $additionalField;

//     });

//         return \response()->json([
//             'application' => $application,
//             'image'=>$image,
//             // 'id'=>$id

//             'unique_additional_fields' => $uniqueAdditionalFields->values(), // Convert to values to remove keys

//             ],Response::HTTP_OK);

//     }
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
    public function getApplicationCopyById(Request $request)
    {
        $id = $request->application_id;
        $application = Application::where('application_id', '=', $id)
            ->with([
                'current_location.parent.parent.parent.parent',
                'permanent_location.parent.parent.parent.parent',
                'program',
                'allowAddiFields.allowAddiFieldValues',
                'variable',
                'subvariable',
            ])->first();

        if (!$application) {
            return response()->json(['error' => 'Application not found'], Response::HTTP_NOT_FOUND);
        }

        //  $image = asset('storage/' . $application->image);

        //  $imageUrl = 'https://picsum.photos/200/300';
        //  $imageData = file_get_contents($image);
        $imagePath = $application->image;
        $imageData = Storage::disk('public')->get($imagePath);
        $image = Helper::urlToBase64($imageData);
        $signaturePath = $application->signature;
        $signatureData = Storage::disk('public')->get($signaturePath);
        $signature = Helper::urlToBase64($signatureData);
        $nomineeimagePath = $application->nominee_image;
        $nominee_imageData = Storage::disk('public')->get($nomineeimagePath);
        $nominee_image = Helper::urlToBase64($nominee_imageData);
        $nominee_signaturePath = $application->nominee_signature;
        $nominee_signature_Data = Storage::disk('public')->get($nominee_signaturePath);
        $nominee_signature = Helper::urlToBase64($nominee_signature_Data);

        $dynamic = $request->all();

        $title = $request->title;
        $data = ['data' => $application,
            'request' => $dynamic,
            'title' => $title,
            'image' => $image,
            'nominee_image' => $nominee_image,
            'signature' => $signature,
            'nominee_signature' => $nominee_signature,

        ];

        $pdf = LaravelMpdf::loadView('reports.applicant_copy', $data, [],
            [
                'mode' => 'utf-8',
                'format' => 'A4-P',
                'title' => $title,
                'orientation' => 'L',
                'default_font_size' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_header' => 10,
                'margin_footer' => 10,
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

    }

    public function getPreviewById($id)
    {
        //   $decryptedId = Crypt::decryptString($id);
        $application = Application::where('id', '=', $id)
            ->with([
                'current_location.parent.parent.parent.parent',
                'permanent_location.parent.parent.parent.parent',
                'program',
                'allowAddiFields',
                // 'allowAddiFieldValue.allowAddiField',
                'allowAddiFieldValue.allowAddiField',
                'variable',
                'subvariable',
            ])
            ->where('status', 9)->first();

        if (!$application) {
            return response()->json(['error' => 'Application not found'], Response::HTTP_NOT_FOUND);
        }

        $image = asset('storage/' . $application->image);

        $signature = asset('storage/' . $application->signature);

        $nominee_image = asset('storage/' . $application->nominee_image);

        $nominee_signature = asset('storage/' . $application->nominee_signature);

        $groupedAllowAddiFields = $application->allowAddiFields->groupBy('id')->values();
        $groupedAllowAddiFields = $application->allowAddiFields->groupBy('pivot.allow_addi_fields_id');

        // Get the first item from each group (assuming it's the same for each 'allow_addi_fields_id')
        $distinctAllowAddiFields = $groupedAllowAddiFields->map(function ($group) {
            return $group->first();
        });

        return response()->json([
            // 'emu' => $emu,
            'application' => $application,
            'unique_additional_fields' => $distinctAllowAddiFields,
            'image' => $image,
            'signature' => $signature,
            'nominee_image' => $nominee_image,
            'nominee_signature' => $nominee_signature,

        ], Response::HTTP_OK);
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

    public function getAllMobileOperatorPaginated(Request $request)
    {
        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage');
        $page = $request->query('page');

        $filterArrayValue = [];

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

    public function insertMobileOperator(MobileOperatorRequest $request)
    {

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

        if ($mobile) {
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
    public function updateMobileOperator(MobileOperatorUpdateRequest $request)
    {

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
    public function changeApplicationsStatus(UpdateStatusRequest $request)
    {
        if (!$request->applications_id) {
            return response()->json([
                'success' => false,
                'error' => 'You have to select atleast one applicant .',
            ]);
        }

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

        DB::beginTransaction();
        try {
            $this->updateApplications($request, $user, $query->get());
            DB::commit();
            return $this->sendResponse([], 'Update success');
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->sendError('Internal server error', []);
        }

    }

    public function updateApplications($request, $user, $applications, )
    {
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

        Application::whereIn('id', $applications->pluck('id'))->update($data);

        if ($request->status == ApplicationStatus::REJECTED) {
            Beneficiary::whereIn('application_table_id', $applications->pluck('id'))->delete();
        }

    }

    public function changeCommitteeApplicationsStatus($request, $applications, $committeeId)
    {
        foreach ($applications as $application) {
            $committeeApplication = $application->committeeApplication()->updateOrCreate([
                'committee_id' => $committeeId,
            ]
            );

            $committeeApplication->status = $request->status;
            $committeeApplication->remark = $request->remark;
            $committeeApplication->save();

            activity("Application Status Change")
                ->causedBy(auth()->user())
                ->performedOn($committeeApplication)
                ->withProperties(['userInfo' => Helper::BrowserIpInfo(), 'data' => $committeeApplication])
                ->log("Application Status Change");

            if ($request->status == ApplicationStatus::APPROVE && !$application->approve_date) {
                $application->approve_date = now();
                $application->save();

                activity("Application Status Change APPROVE")
                    ->causedBy(auth()->user())
                    ->performedOn($application)
                    ->withProperties(['userInfo' => Helper::BrowserIpInfo(), 'data' => $application])
                    ->log("Application Status Change");
            }

            if ($request->status == ApplicationStatus::APPROVE || $request->status == ApplicationStatus::WAITING) {
                $status = ApplicationStatus::APPROVE == $request->status ? 1 : 3;
                $this->createBeneficiary($application, $status);
                activity("Application Status Change Approve/Waiting")
                    ->causedBy(auth()->user())
                    ->performedOn($application)
                    ->withProperties(['userInfo' => Helper::BrowserIpInfo(), 'data' => $status])
                    ->log("Application Status Change");
            }
        }
    }

    /**
     * @param Application $application
     * @return mixed
     */
    public function createBeneficiary($application, $status)
    {
        $program_code = $application->program_id;
        $district_geo_code = Application::permanentDistrict($application->permanent_location_id);
        $district_geo_code = $district_geo_code->code;
        // $district_geo_code = 02;
        $remaining_digits = 11 - strlen($program_code) - strlen($district_geo_code);
        $incremental_value = DB::table('beneficiaries')->count() + 1;
        $incremental_value_formatted = str_pad($incremental_value, $remaining_digits, '0', STR_PAD_LEFT);
        $beneficiary_id = $program_code . $district_geo_code . $incremental_value_formatted;
        $is_unique = DB::table('beneficiaries')->where('beneficiary_id', $beneficiary_id)->doesntExist();
        while (!$is_unique) {
            $incremental_value++;
            $incremental_value_formatted = str_pad($incremental_value, $remaining_digits, '0', STR_PAD_LEFT);
            $beneficiary_id = $program_code . $district_geo_code . $incremental_value_formatted;
            $is_unique = DB::table('beneficiaries')->where('beneficiary_id', $beneficiary_id)->doesntExist();
        }
        // $application->application_id = $application_id;
        $beneficiary = Beneficiary::firstOrNew(
            [
                "application_table_id" => $application->id,
            ],

            [
                "program_id" => $application->program_id,
                "application_id" => $application->application_id,
                "beneficiary_id" => $beneficiary_id,
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
                "nationality" => $application->nationality,
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
                "nominee_date_of_birth" => $application->nominee_date_of_birth,
                "nominee_image" => $application->nominee_image,
                "nominee_signature" => $application->nominee_signature,
                "nominee_relation_with_beneficiary" => $application->nominee_relation_with_beneficiary,
                "nominee_nationality" => $application->nominee_nationality,
                "account_name" => $application->account_name,
                "account_number" => $application->account_number,
                "account_owner" => $application->account_owner,

                "score" => $application->score,
                "forward_committee_id" => $application->forward_committee_id,
                "remarks" => $application->remark,
                "monthly_allowance" => $application->allowance_amount,
                "application_date" => $application->created_at,
            ]
        );

        $beneficiary->status = $status;
        $beneficiary->approve_date = $application->approve_date;
        $beneficiary->save();
        if ($status === 1) {
            $programName = AllowanceProgram::where('id', $application->program_id)->first('name_en');
            $program = $programName->name_en;

            //  $message = " Dear $application->name_en, "."\n We are thrilled to inform you that you have been selected as a recipient for the ". $program ."\n Sincerely,"."\nDepartment of Social Services";
            $message = "Dear $application->name_en," . "\nWe are thrilled to inform you that you have been selected as a recipient for the $program.\n\nYour Beneficiary ID is $beneficiary_id.\n\nSincerely," . "\nDepartment of Social Services";

            $message = " Dear $application->name_en, " . "\n We are thrilled to inform you that you have been selected as a recipient for the " . $program . "\n Sincerely," . "\nDepartment of Social Services";

            $this->SMSservice->sendSms($application->mobile, $message);
            if ($application->email) {
                $this->dispatch(new SendEmail($application->email, $application->name_en, $program));

            }

        }

    }

    public function forwardApplication($request, $applications)
    {
        foreach ($applications as $application) {
            $committeeApplication = $application->committeeApplication()->firstOrNew([
                'committee_id' => $request->committee_id,
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
                'committee_id' => $committeeId,
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
                'permission' => $this->getPermission($user),
            ]
        );

    }

    public function getPermission($user)
    {
        //superadmin
        if ($user->user_type == 1) {
            return [
                'approve' => false,
                'recommendation' => false,
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
                'recommendation' => false,
                'forward' => $canForward,
                'reject' => false,
                'waiting' => false,
            ];
        }

        //committee user

        return [
            'approve' => (bool) $user->committeePermission?->approve,
            'recommendation' => (bool) $user->committeePermission?->recommendation,
            'forward' => (bool) $user->committeePermission?->forward,
            'reject' => (bool) $user->committeePermission?->reject,
            'waiting' => (bool) $user->committeePermission?->waiting,
        ];
    }

}
