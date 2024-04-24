<?php

namespace App\Http\Controllers\Api\V1\Setting;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ActivityResource;
use App\Http\Traits\MessageTrait;
use App\Models\ActivityModel;
use App\Models\Beneficiary;
use App\Models\Location;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    use MessageTrait;

    /**
     *@OA\Post(
     *      path="/admin/activity-log/all/filtered",
     *      operationId="getAllActivityLogsPaginated",
     *      tags={"SETTING"},
     *      summary="get paginated activity logs from database",
     *      description="get paginated activity logs from database",
     *      security={{"bearer_token":{}}},
     *
     *      @OA\RequestBody(
     *          required=false,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *
     *                  @OA\Property(
     *                      property="searchText",
     *                      description="search text for searching by description",
     *                      type="text",
     *                  ),
     *                  @OA\Property(
     *                      property="perPage",
     *                      description="number of activity log per page",
     *                      type="text",
     *                  ),
     *                  @OA\Property(
     *                      property="page",
     *                      description="page number",
     *                      type="text",
     *                  ),

     *               ),
     *           ),
     *       ),
     *
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
     *       @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *
     *     )
     */
//    public function getAllActivityLogsPaginated(Request $request){
//        $perPage = $request->perPage;
//        $filterArrayName = [];
//        if ($request->filled('searchText')) {
//            $filteredText = $request->searchText;
//            $filterArrayName[] = ['description', 'LIKE', '%' . $filteredText . '%'];
//        }
//
//        $activityLog = ActivityModel::query()
//            ->where($filterArrayName)
//            ->with('subject','causer')
//            ->latest()
//            ->paginate($perPage, ['*'], 'page');
//            return ActivityResource::collection($activityLog)->additional([
//                'success' => true,
//                'message' => $this->fetchDataSuccessMessage,
//                'meta' => [
//                    'current_page' => $activityLog->currentPage(),
//                    'per_page' => $activityLog->perPage(),
//                    'total' => $activityLog->total(),
//                    'last_page' => $activityLog->lastPage(),
//                ],
//            ]);
//            // return $this->sendResponse($activityLog, $this->fetchSuccessMessage, Response::HTTP_OK);
//    }

    public function getAllActivityLogsPaginated(Request $request)
    {
        $perPage = $request->perPage;
        $activityLog = ActivityModel::query()
            ->with('subject', 'causer')
            ->latest();

        $startDate = $request->from_date;
        $endDate = $request->to_date;

        if ($startDate && $endDate) {
            $activityLog->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($request->filled('office_id')) {
            $officeId = $request->office_id;
            $activityLog->whereHas('causer', function ($query) use ($officeId) {
                $query->where('office_id', $officeId);
            });
        }

        if ($request->filled('beneficiary_id')) {
            $beneficiaryId = $request->beneficiary_id;
            $beneficiary = Beneficiary::where('application_id',$beneficiaryId)->first('id');
            if ($beneficiary) {
                $activityLog->where('subject_id', $beneficiary->id)->where('log_name', 'Beneficiary');
            }
        }

        if ($request->filled('division_id') && $request->filled('district_id')) {
            $divisionId = $request->division_id;
            $districtId = $request->district_id;
            $divisionWiseDistricts = Location::where('id', $divisionId)->pluck('id');
            $userInfo = User::where('assign_location_id', $districtId)
//                ->orWhereIn('assign_location_id', $divisionWiseDistricts)
                ->pluck('id');
            $activityLog->whereHas('causer', function ($query) use ($userInfo) {
                $query->whereIn('user_id', $userInfo);
            });
        } elseif ($request->filled('division_id')) { // Check if only division_id is provided
            $divisionId = $request->division_id;
            $divisionWiseDistricts = Location::where('id', $divisionId)->pluck('id');
            $userInfo = User::whereIn('assign_location_id', $divisionWiseDistricts)->pluck('id');
            $activityLog->whereHas('causer', function ($query) use ($userInfo) {
                $query->whereIn('user_id', $userInfo);
            });
        } elseif ($request->filled('district_id')) { // Check if only district_id is provided
            $districtId = $request->district_id;
            $userInfo = User::where('assign_location_id', $districtId)->pluck('id');
            $activityLog->whereHas('causer', function ($query) use ($userInfo) {
                $query->whereIn('user_id', $userInfo);
            });
        }


        if ($request->filled('action_type')) {
            $actionType = $request->action_type;
            $activityLog->where('log_name', $actionType);
        }

        if ($request->filled('device_type')) {
            $deviceType = $request->device_type;
            $activityLog->where(function ($query) use ($deviceType) {
                $query->whereJsonContains('properties->userInfo->Device Type', $deviceType);
            });
        }

        if ($request->filled('user_id')) {
            $userId = $request->user_id;
            $activityLog->whereHas('causer', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });
        }

        if ($request->filled('user_name')) {
            $userName = $request->user_name;
            $activityLog->whereHas('causer', function ($query) use ($userName) {
                $query->where('username', $userName);
//                    ->orWhere('username', 'LIKE', '%' . $userName . '%');
            });
        }

        if ($request->filled('searchText')) {
            $searchText = $request->searchText;
            $activityLog->where(function ($query) use ($searchText) {
                $query->where('description', 'LIKE', '%' . $searchText . '%')
                ->orWhere('log_name', 'LIKE', '%' . $searchText . '%')
                    ->orWhereHas('causer', function ($query) use ($searchText) {
                        $query->where('email', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('username', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('mobile', 'LIKE', '%' . $searchText . '%');
                    })
                    ->orWhereJsonContains('properties->userInfo->Browser', $searchText)
                    ->orWhereJsonContains('properties->userInfo->Platform', $searchText)
                    ->orWhereJsonContains('properties->userInfo->Device Type', $searchText)
                    ->orWhereJsonContains('properties->userInfo->City Name', $searchText);
            });
        }

        $activityLog = $activityLog->paginate($perPage, ['*'], 'page');

        return ActivityResource::collection($activityLog)->additional([
            'success' => true,
            'message' => $this->fetchDataSuccessMessage,
            'meta' => [
                'current_page' => $activityLog->currentPage(),
                'per_page' => $activityLog->perPage(),
                'total' => $activityLog->total(),
                'last_page' => $activityLog->lastPage(),
            ],
        ]);
    }


    public function viewAnonymousActivityLog($id){
        $activityLog = ActivityModel::query()
            ->with('subject','causer','causer.office')
            ->where('id',$id)
            ->first();

        return (new ActivityResource($activityLog))->additional([
            'success' => true,
            'message' => $this->fetchDataSuccessMessage,
        ]);

        // return $this->sendResponse($activityLog, $this->fetchSuccessMessage, Response::HTTP_OK);
    }

    public function destroyActivityLog($id)
    {
        try {
            $activity_log = ActivityModel::findOrFail($id);
            $activity_log->delete();
            Helper::activityLogDelete($activity_log,'','Activity Log','Activity Log Deleted !');
//            activity("Activity Log")
//                ->causedBy(auth()->user())
//                ->performedOn($activity_log)
//                ->log('Activity Log Deleted !');
            return $this->sendResponse($activity_log, $this->deleteSuccessMessage, Response::HTTP_OK);

        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function getAnonymousActivityLog(Request $request)
    {
        $info = activity($request->info)
            ->withProperties(['userInfo' => Helper::BrowserIpInfo(),'data' => ''])
            ->log($request->info);

        return $info;
    }

    public function getAllLogName()
    {
        $logNamesWithCount = DB::table('activity_log')
            ->select('log_name', DB::raw('COUNT(*) as count'))
            ->groupBy('log_name')
            ->get();
        return $this->sendResponse($logNamesWithCount, "All Log Name Lists", Response::HTTP_OK);
    }

    public function divisionDistrictWiseOfficeList($id)
    {
        $office = Office::where('assign_location_id',$id)->get(['id','name_en','name_bn']);
        return $this->sendResponse($office, "Office Lists", Response::HTTP_OK);
    }

}
