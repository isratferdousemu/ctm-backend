<?php

namespace App\Http\Controllers\Api\V1\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Employee\WorkingShiftRequest;
use App\Http\Requests\Admin\Employee\WorkingShiftUpdateRequest;
use App\Http\Resources\Admin\Employee\WorkingShiftResource;
use App\Http\Services\Admin\Employee\WorkingShiftService;
use App\Http\Traits\MessageTrait;
use App\Models\WorkingShift;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WorkingShiftController extends Controller
{
    use MessageTrait;
    private $WorkingShiftService;

    public function __construct(WorkingShiftService $WorkingShiftService) {
        $this->WorkingShiftService = $WorkingShiftService;

    }


    /**
     *@OA\Post(
     *      path="/admin/employee/shift/all/filtered",
     *      operationId="getAllEmpShiftPaginated",
     *      tags={"EMPLOYEE-SHIFT"},
     *      summary="get paginated employee shift from database",
     *      description="get paginated employee shift from database",
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
     *                      description="search text for searching by employee shift name",
     *                      type="text",
     *                  ),
     *                  @OA\Property(
     *                      property="perPage",
     *                      description="number of employee shift per page",
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
    public function getAllEmpShiftPaginated(Request $request){
        $perPage = $request->perPage;
        $filterArrayName = [];
        if ($request->filled('searchText')) {
            $filteredText = $request->searchText;
            $filterArrayName[] = ['name', 'LIKE', '%' . $filteredText . '%'];
        }


        $cities = WorkingShift::query()
            ->where($filterArrayName)
            ->with('details')
            ->latest()
            ->paginate($perPage, ['*'], 'page');

            return WorkingShiftResource::collection($cities)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);

    }

     /**
     * @OA\Post(
     * path="/admin/employee/shift/insert",
     *   tags={"EMPLOYEE-SHIFT"},
     *   summary="insert a Employee Shift with Shift Details",
     *   operationId="InsertEmpShift",
     *     security={{"bearer_token":{}}},
     *
     *      @OA\RequestBody(
     *          required=true,
     *         @OA\JsonContent(
     *
     *              @OA\Property(property="name", type="string", example="Shift name"),
     *              @OA\Property(property="description", type="string", example="Shift name"),
     *              @OA\Property(property="type", type="string", example="shift Type is : regular OR scheduled"),
     *              @OA\Property(property="start_at", type="string", example="if type is regular start_at get value else null"),
     *              @OA\Property(property="end_at", type="string", example="if type is regular end_at get value else null"),
     *               @OA\Property(property="weekdays", type="array", example={
     *                  {
     *                  "weekday": "sun",
     *                  "start_at": "02:00",
     *                  "end_at": "02:00",
     *                  "is_weekend": 0,
     *                },
     *                  {
     *                  "weekday": "mon",
     *                  "start_at": "02:00",
     *                  "end_at": "02:00",
     *                  "is_weekend": 0,
     *                },
     *                  {
     *                  "weekday": "tue",
     *                  "start_at": "02:00",
     *                  "end_at": "02:00",
     *                  "is_weekend": 0,
     *                },
     *                  {
     *                  "weekday": "wed",
     *                  "start_at": "02:00",
     *                  "end_at": "02:00",
     *                  "is_weekend": 0,
     *                },
     *                  {
     *                  "weekday": "thu",
     *                  "start_at": "02:00",
     *                  "end_at": "02:00",
     *                  "is_weekend": 0,
     *                },
     *                  {
     *                  "weekday": "fri",
     *                  "start_at": "02:00",
     *                  "end_at": "02:00",
     *                  "is_weekend": 1,
     *                },
     *                  {
     *                  "weekday": "sat",
     *                  "start_at": "02:00",
     *                  "end_at": "02:00",
     *                  "is_weekend": 1,
     *                },
     *              },
     *          @OA\Items(
     *                      @OA\Property(
     *                         property="item_cost",
     *                         type="integer",
     *                         example=""
     *                      ),
     *                      @OA\Property(
     *                         property="item_name",
     *                         type="string",
     *                         example=""
     *                      ),
     *
     *                ),
     * ),
     *
     *             ),
     *
     *       ),
     *
     *
     *
     *
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/

     public function InsertEmpShift(WorkingShiftRequest $request){
        try {
            //code...
            $workingShift = $this->WorkingShiftService->createEmpShift($request);

            activity()
            ->causedBy(auth()->user())
            ->performedOn($workingShift)
            ->log('Working Shift Created !');
            return WorkingShiftResource::make($workingShift->load('details'))->additional([
                'success' => true,
                'message' => $this->insertSuccessMessage,
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }

     }

     /**
     *
     * @OA\Post(
     *      path="/admin/employee/shift/update",
     *      operationId="UpdateEmpShift",
     *      tags={"EMPLOYEE-SHIFT"},
     *      summary="update a employee shift",
     *      description="update a employee shift",
     *      security={{"bearer_token":{}}},
     *
      *      @OA\RequestBody(
     *          required=true,
     *         @OA\JsonContent(
     *
     *              @OA\Property(property="id", type="integer", example="Shift id"),
     *              @OA\Property(property="name", type="string", example="Shift name"),
     *              @OA\Property(property="description", type="string", example="Shift name"),
     *              @OA\Property(property="type", type="string", example="shift Type is : regular OR scheduled"),
     *              @OA\Property(property="start_at", type="string", example="if type is regular start_at get value else null"),
     *              @OA\Property(property="end_at", type="string", example="if type is regular end_at get value else null"),
     *               @OA\Property(property="weekdays", type="array", example={
     *                  {
     *                  "weekday": "sun",
     *                  "start_at": "02:00",
     *                  "end_at": "02:00",
     *                  "is_weekend": 0,
     *                },
     *                  {
     *                  "weekday": "mon",
     *                  "start_at": "02:00",
     *                  "end_at": "02:00",
     *                  "is_weekend": 0,
     *                },
     *                  {
     *                  "weekday": "tue",
     *                  "start_at": "02:00",
     *                  "end_at": "02:00",
     *                  "is_weekend": 0,
     *                },
     *                  {
     *                  "weekday": "wed",
     *                  "start_at": "02:00",
     *                  "end_at": "02:00",
     *                  "is_weekend": 0,
     *                },
     *                  {
     *                  "weekday": "thu",
     *                  "start_at": "02:00",
     *                  "end_at": "02:00",
     *                  "is_weekend": 0,
     *                },
     *                  {
     *                  "weekday": "fri",
     *                  "start_at": "02:00",
     *                  "end_at": "02:00",
     *                  "is_weekend": 1,
     *                },
     *                  {
     *                  "weekday": "sat",
     *                  "start_at": "02:00",
     *                  "end_at": "02:00",
     *                  "is_weekend": 1,
     *                },
     *              },
     *          @OA\Items(
     *                      @OA\Property(
     *                         property="item_cost",
     *                         type="integer",
     *                         example=""
     *                      ),
     *                      @OA\Property(
     *                         property="item_name",
     *                         type="string",
     *                         example=""
     *                      ),
     *
     *                ),
     * ),
     *
     *             ),
     *
     *       ),
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

     public function UpdateEmpShift(WorkingShiftUpdateRequest $request){

        $EmployeeShift = WorkingShift::findOrFail($request->id);
        try {
            $updatedEmployeeShift = $this->WorkingShiftService->updateEmpShift($request,$EmployeeShift);
            activity()
            ->causedBy(auth()->user())
            ->performedOn($updatedEmployeeShift)
            ->log('Employee shift Updated!!');
            return WorkingShiftResource::make($updatedEmployeeShift->load('details'))->additional([
                'success' => true,
                'message' => $this->insertSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }

    }

    /**
     *
     * @OA\Post(
     *      path="/admin/employee/shift/delete",
     *      operationId="DeleteEmpShift",
     *      tags={"EMPLOYEE-SHIFT"},
     *      summary="delete a employee Shift",
     *      description="delete a employee Shift",
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
     *                      property="id",
     *                      description="id of the employee shift",
     *                      type="integer",
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

     public function DeleteEmpShift(Request $request)
     {
         $request->validate([
             'id' => 'required|exists:working_shifts,id'
         ]);
         try {
             $WorkingShift = WorkingShift::findOrFail($request->id);
            $this->WorkingShiftService->deleteEmpShift($WorkingShift);

             activity()
            ->causedBy(auth()->user())
            ->log('Employee Shift Deleted!!');
             return $this->sendResponse([], $this->deleteSuccessMessage, Response::HTTP_OK);

         } catch (\Throwable $th) {
             //throw $th;
             return $this->sendError($th->getMessage(), [], 500);
         }
     }
}
