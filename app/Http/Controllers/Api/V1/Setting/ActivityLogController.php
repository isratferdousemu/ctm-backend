<?php

namespace App\Http\Controllers\Api\V1\Setting;

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
    public function getAllActivityLogsPaginated(Request $request){
        $perPage = $request->perPage;
        $filterArrayName = [];
        if ($request->filled('searchText')) {
            $filteredText = $request->searchText;
            $filterArrayName[] = ['description', 'LIKE', '%' . $filteredText . '%'];
        }

        $divisions = ActivityModel::query()
            ->where($filterArrayName)
            ->with('subject','causer')
            ->latest()
            ->paginate($perPage, ['*'], 'page');
            return ActivityResource::collection($divisions)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);

            // return $this->sendResponse($divisions, $this->fetchSuccessMessage, Response::HTTP_OK);


    }
}
