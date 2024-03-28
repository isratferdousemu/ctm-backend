<?php

namespace App\Http\Controllers\Api\V1\Setting;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ActivityResource;
use App\Http\Traits\MessageTrait;
use App\Models\ActivityModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
            ->with('subject','causer')
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
            activity("Activity Log")
                ->causedBy(auth()->user())
                ->performedOn($activity_log)
                ->log('Activity Log Deleted !');
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

}
