<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Constants\ApplicationStatus;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Application\MobileOperatorRequest;
use App\Http\Requests\Admin\Application\MobileOperatorUpdateRequest;
use App\Http\Resources\Admin\Application\MobileOperatorResource;
use App\Http\Services\Admin\Application\CommitteeApplicationService;
use App\Http\Services\Admin\Application\CommitteeListService;
use App\Http\Services\Admin\Application\MobileOperatorService;
use App\Http\Services\Admin\Application\OfficeApplicationService;
use App\Http\Services\Admin\Application\VerificationService;
use App\Http\Services\Admin\GrievanceManagement\GrievanceComitteeService;
use App\Http\Services\Admin\GrievanceManagement\GrievanceListService;
use App\Http\Services\Admin\GrievanceManagement\GrievanceService;
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
use App\Models\GrievanceSetting;
use App\Models\GrievanceStatusUpdate;
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
    public function getGrievanceSettings(Request $request)
    {
        $grievanceTypeId = $request->query('typeId');
        $grievanceSubjectId = $request->query('subjectId');
        $grievanceSettings = GrievanceSetting::with('firstOfficer', 'secoundOfficer', 'thirdOfficer')->where('grievance_type_id', $grievanceTypeId)
            ->where('grievance_subject_id', $grievanceSubjectId)
            ->first();
        return $grievanceSettings;
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

        $data = $this->grievanceService->onlineGrievanceEntry($request);
        Helper::activityLogInsert($data, '', 'Grievance entry', 'Grievance Created !');
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => $this->insertSuccessMessage,
        ], 200);

    }





    /* -------------------------------------------------------------------------- */
    /*                        Grievance list Methods                       */
    /* -------------------------------------------------------------------------- */

    public function getAllGrievancePaginated(Request $request)
    {
        // return 'ok';
        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $verification_number = $request->query('verification_number');
        $tracking_no = $request->query('tracking_no');
        $grievanceType = $request->query('grievanceType');
        $grievanceSubject = $request->query('grievanceSubject');

        $location_type = $request->query('location_type');
        $division_id = $request->query('division_id');
        $district_id = $request->query('district_id');

        $thana_id = $request->query('thana_id');
        $union_id = $request->query('union_id');
        $city_id = $request->query('city_id');
        $city_thana_id = $request->query('city_thana_id');
        $district_pouro_id = $request->query('district_pouro_id');
        $pouro_id = $request->query('pouro_id');
        $sub_location_type = $request->query('sub_location_type');
        $ward_id = $request->query('ward_id');
        $status = $request->query('status');

        $perPage = $request->query('perPage');
        $page = $request->query('page');

        $filterArrayTracking_no = [];
        $filterArrayGrievanceType = [];
        $filterArrayGrievanceSubject = [];
        $filterArrayName = [];
        $filterArrayVerificationNumber = [];
        $filterArrayLocationType = [];
        $filterArrayDivisionId = [];
        $filterArrayDistrictId = [];

        $filterArrayThanaId = [];
        $filterArrayUnionId = [];
        $filterArrayCityId = [];
        $filterArrayCityThanaId = [];
        $filterArrayDistrictPouroId = [];
        $filterArrayPouroId = [];
        $filterArraysubLocationType = [];
        $filterArrayWardId = [];
        $filterArrayStatus = [];

        if ($searchText) {
            $filterArrayName[] = ['name', 'LIKE', '%' . $searchText . '%'];
            $page = 1;

        }

        if ($verification_number) {
            $filterArrayVerificationNumber[] = ['verification_number', 'LIKE', '%' . $verification_number . '%'];
            $page = 1;

        }

        if ($tracking_no) {
            $filterArrayTracking_no[] = ['tracking_no', 'LIKE', '%' . $tracking_no . '%'];
            $page = 1;

        }

        if ($grievanceType) {
            $filterArrayGrievanceType[] = ['grievance_type_id', '=', $grievanceType];
            $page = 1;

        }

        if ($grievanceSubject) {
            $filterArrayGrievanceSubject[] = ['grievance_subject_id', '=', $grievanceSubject];
            $page = 1;

        }
        if ($location_type) {
            $filterArrayLocationType[] = ['location_type', '=', $location_type];
            $page = 1;

        }
        if ($division_id) {
            $filterArrayDivisionId[] = ['division_id', '=', $division_id];
            $page = 1;

        }
        if ($district_id) {
            $filterArrayDistrictId[] = ['district_id', '=', $district_id];
            $page = 1;

        }
        if ($thana_id) {
            $filterArrayThanaId[] = ['thana_id', '=', $thana_id];
            $page = 1;

        }
        if ($union_id) {
            $filterArrayUnionId[] = ['union_id', '=', $union_id];
            $page = 1;

        }

        if ($city_id) {
            $filterArrayCityId[] = ['city_id', '=', $city_id];
            $page = 1;

        }
        if ($city_thana_id) {
            $filterArrayCityThanaId[] = ['city_thana_id', '=', $city_thana_id];
            $page = 1;

        }

        if ($district_pouro_id) {
            $filterArrayDistrictPouroId[] = ['district_pouro_id', '=', $district_pouro_id];
            $page = 1;

        }
        if ($pouro_id) {
            $filterArrayPouroId[] = ['pouro_id', '=', $pouro_id];
            $page = 1;

        }
        if ($sub_location_type) {
            $filterArraysubLocationType[] = ['sub_location_type', '=', $sub_location_type];
            $page = 1;

        }
        if ($location_type == 3) {
            $filterArrayWardId[] = ['ward_id_city', '=', $ward_id];
            $page = 1;

        } else if ($location_type == 1) {
            $filterArrayWardId[] = ['ward_id_pouro', '=', $ward_id];
            $page = 1;

        } else {
            $filterArrayWardId[] = ['ward_id_dist', '=', $ward_id];
            $page = 1;

        }
        if ($ward_id) {
            $filterArrayWardId[] = ['ward_id_dist', '=', $ward_id];
            $page = 1;

        }
        if ($status) {
            $filterArrayStatus[] = ['status', '=', $status];
            $page = 1;

        }

        $query = Grievance::query();
        $this->applyUserWiseGrievacne($query);
        $query->when($searchText, function ($q) use ($filterArrayName) {
            $q->where($filterArrayName)

            ;
        });

        $query->when($verification_number, function ($q) use ($filterArrayVerificationNumber) {
            $q->where($filterArrayVerificationNumber);
        });

        $query->when($filterArrayTracking_no, function ($q) use ($filterArrayTracking_no) {
            $q->where($filterArrayTracking_no);
        });

        $query->when($grievanceType, function ($q) use ($filterArrayGrievanceType) {
            $q->where($filterArrayGrievanceType);
        });
        $query->when($grievanceSubject, function ($q) use ($filterArrayGrievanceSubject) {
            $q->where($filterArrayGrievanceSubject);
        });
        $query->when($location_type, function ($q) use ($filterArrayLocationType) {
            $q->where($filterArrayLocationType);
        });
        $query->when($division_id, function ($q) use ($filterArrayDivisionId) {
            $q->where($filterArrayDivisionId);
        });
        $query->when($district_id, function ($q) use ($filterArrayDistrictId) {
            $q->where($filterArrayDistrictId);
        });

        $query->when($thana_id, function ($q) use ($filterArrayThanaId) {
            $q->where($filterArrayThanaId);
        });
        $query->when($union_id, function ($q) use ($filterArrayUnionId) {
            $q->where($filterArrayUnionId);
        });
        $query->when($city_id, function ($q) use ($filterArrayCityId) {
            $q->where($filterArrayCityId);
        });
        $query->when($city_thana_id, function ($q) use ($filterArrayCityThanaId) {
            $q->where($filterArrayCityThanaId);
        });
        $query->when($district_pouro_id, function ($q) use ($filterArrayDistrictPouroId) {
            $q->where($filterArrayDistrictPouroId);
        });
        $query->when($pouro_id, function ($q) use ($filterArrayPouroId) {
            $q->where($filterArrayPouroId);
        });
        $query->when($sub_location_type, function ($q) use ($filterArraysubLocationType) {
            $q->where($filterArraysubLocationType);
        });
        $query->when($ward_id, function ($q) use ($filterArrayWardId) {
            $q->where($filterArrayWardId);
        });
        $query->when($status, function ($q) use ($filterArrayStatus) {
            $q->where($filterArrayStatus);
        });

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('gender_id')) {
            $query->where('gender_id', $request->gender_id);
        }
        if ($request->has('grievance_type')) {
            $query->where('title_en', $request->grievanceType);
        }

        $query->with('grievanceType', 'grievanceSubject', 'program', 'gender', 'division', 'district', 'districtPouroshova', 'cityCorporation', 'ward')
            ->orderBy('id')
        ;

        return $query->paginate($perPage, ['*'], 'page', $page);

    }

    public function applyUserWiseGrievacne($query)
    {
         $user = auth()->user()->load('assign_location.parent.parent.parent.parent');

        if($user->hasRole($this->officeHead) && $user->office_type || $user->hasRole($this->committee) && $user->committee_type_id){
            $roleIds = $user->roles->pluck('id');
            $settings = collect();
           foreach ($roleIds as $roleId) {
             $roleSettings = GrievanceSetting::where('first_tire_officer', $roleId)
               ->orwhere('secound_tire_officer', $roleId)
               ->orwhere('third_tire_officer', $roleId)
               ->select('grievance_type_id', 'grievance_subject_id')
               ->distinct()
               ->get();
            $settings = $settings->merge($roleSettings);
          }
           $settings = $settings->unique();
           $query->whereIn('grievance_type_id', $settings->pluck('grievance_type_id'));
           $query->whereIn('grievance_subject_id', $settings->pluck('grievance_subject_id'));

        }

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




    public function changeGrievanceStatus(Request $request)
    {
       
        DB::beginTransaction();
         try {
        if (!$request->id) {
            return response()->json([
                'success' => false,
                'error' => 'You have to select atleast one applicant .',
            ]);
        }
        $user = auth()->user();

        $grievance = new GrievanceStatusUpdate();
        $grievance->grievance_id = $request->id;
        $grievance->resolver_id = $user->id;
        $grievance->status = $request->status;
        $grievance->remarks = $request->remarks;
        $grievance->solution = $request->solution;
        if ($request->file('documents')) {
          $filePath = $request->file('documents')->store('public');
          $grievance->file = $filePath;
        }
         $grievance->save();

         $grievanceApplication=Grievance::where('id',$request->id)->first();
         $grievanceApplication->status=$request->status;
         $grievanceApplication->save();
            DB::commit();
            return $grievance;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

         
 

        // if ($user->committee_type_id) {
        //     $this->checkPermission($request, $user);
        // }

        // $query = Application::query();

        // $this->applyUserWiseFiltering($query);
        // $query->with(['committeeApplication']);
        // $query->whereIn('id', $request->applications_id);

        // $query->whereNot('status', ApplicationStatus::REJECTED)
        //     ->whereNot('status', ApplicationStatus::APPROVE);

        // DB::beginTransaction();
        // try {
        //     $this->updateApplications($request, $user, $query->get());
        //     DB::commit();
        //     return $this->sendResponse([], 'Update success');
        // } catch (\Exception $exception) {
        //     DB::rollBack();

        //     return $this->sendError('Internal server error', []);
        // }

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



}
