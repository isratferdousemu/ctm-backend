<?php

namespace App\Http\Controllers\Api\V1\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Employee\DepartmentRequest;
use App\Http\Requests\Admin\Employee\DepartmentUpdateRequest;
use App\Http\Resources\Admin\Employee\DepartmentRrsource;
use App\Http\Services\Admin\Employee\DepartmentService;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\UserTrait;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DepartmentController extends Controller
{
    use MessageTrait,UserTrait;
    private $DepartmentService;

    public function __construct(DepartmentService $DepartmentService) {
        $this->DepartmentService = $DepartmentService;

    }

    /**
     *@OA\Post(
     *      path="/admin/employee/department/all/filtered",
     *      operationId="getAllEmpDepPaginated",
     *      tags={"EMPLOYEE"},
     *      summary="get paginated employee department from database",
     *      description="get paginated employee department from database",
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
     *                      description="search text for searching by employee department name",
     *                      type="text",
     *                  ),
     *                  @OA\Property(
     *                      property="perPage",
     *                      description="number of employee department per page",
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
    public function getAllEmpDepPaginated(Request $request){
        $perPage = $request->perPage;
        $filterArrayName = [];
        if ($request->filled('searchText')) {
            $filteredText = $request->searchText;
            $filterArrayName[] = ['name', 'LIKE', '%' . $filteredText . '%'];
        }
        $Department = Department::query()
            ->where($filterArrayName)
            ->whereType($this->EmployeeDepType)
            ->latest()
            ->paginate($perPage, ['*'], 'page');

            return $this->sendResponse($Department, $this->fetchSuccessMessage, Response::HTTP_OK);


    }

    /**
     *
     * @OA\Post(
     *      path="/admin/employee/department/insert",
     *      operationId="InsertEmpDep",
     *      tags={"EMPLOYEE"},
     *      summary="insert a Employee Department",
     *      description="insert a Employee Department",
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
     *                      property="name",
     *                      description="name of the Employee Department",
     *                      type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="description",
     *                      description="description of the Employee Department",
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
    public function InsertEmpDep(DepartmentRequest $request){
        try {
            //code...
            $department = $this->DepartmentService->createEmpDep($request,$this->EmployeeDepType);
            activity()
            ->causedBy(auth()->user())
            ->performedOn($department)
            ->log('Employee Department Created !');
            return DepartmentRrsource::make($department)->additional([
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
     *      path="/admin/employee/department/update",
     *      operationId="UpdateEmpDep",
     *      tags={"EMPLOYEE"},
     *      summary="update a employee department",
     *      description="update a employee department",
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
     *                      description="id of the employee department",
     *                      type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="name",
     *                      description="name of the employee department",
     *                      type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="description",
     *                      description="description of the employee department",
     *                      type="text",
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

     public function UpdateEmpDep(DepartmentUpdateRequest $request){

        $department = Department::findOrFail($request->id);
        try {
            $updatedDepartment = $this->DepartmentService->updateEmpDep($request, $department);
            activity()
            ->causedBy(auth()->user())
            ->performedOn($updatedDepartment)
            ->log('Department Updated!!');
            return DepartmentRrsource::make($updatedDepartment)->additional([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }

    }

    /**
     *
     * @OA\Post(
     *      path="/admin/employee/department/delete",
     *      operationId="DeleteEmpDep",
     *      tags={"EMPLOYEE"},
     *      summary="delete a employee department",
     *      description="delete a employee department",
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
     *                      description="id of the employee department",
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

     public function DeleteEmpDep(Request $request)
     {
         $request->validate([
             'id' => 'required|exists:departments,id'
         ]);
         try {
             $department = Department::findOrFail($request->id)->delete();
             activity()
            ->causedBy(auth()->user())
            ->log('Department Deleted!!');
             return $this->sendResponse($department, $this->deleteSuccessMessage, Response::HTTP_OK);

         } catch (\Throwable $th) {
             //throw $th;
             return $this->sendError($th->getMessage(), [], 500);
         }
     }

}
