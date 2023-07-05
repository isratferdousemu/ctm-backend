<?php

namespace App\Http\Controllers\Api\V1\Employee;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Employee\EmployeeRequest;
use App\Http\Requests\Admin\Employee\EmployeeUpdateRequest;
use App\Http\Resources\Admin\Employee\EmployeeResource;
use App\Http\Services\Admin\Employee\EmployeeService;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\UserTrait;
use App\Jobs\EmployeeWelcomeJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{

    use MessageTrait,UserTrait;
    private $EmployeeService;

    public function __construct(EmployeeService $EmployeeService) {
        $this->EmployeeService = $EmployeeService;

    }

    /**
     *@OA\Post(
     *      path="/admin/employee/all/filtered",
     *      operationId="getAllEmployeePaginated",
     *      tags={"EMPLOYEE"},
     *      summary="get paginated employees from database",
     *      description="get paginated employees from database",
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
     *                      property="branch_id",
     *                      description="search employee for searching by employee branch ID",
     *                      type="integer",
     *                  ),
     *                  @OA\Property(
     *                      property="department_id",
     *                      description="search employee for searching by employee department_id",
     *                      type="integer",
     *                  ),
     *                  @OA\Property(
     *                      property="employee_id",
     *                      description="search employee for searching by employee employee_id",
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
    public function getAllEmployeePaginated(Request $request){
        $perPage = $request->perPage;
        $searchText = $request->searchText;
        $department_id = $request->department_id;
        $employee_id = $request->employee_id;
        $branch_id = $request->branch_id;

        $filterArrayName = [];
        $filterArrayDepartmentId = [];
        $filterArrayEmployeeId = [];
        $filterArrayBranchId = [];
        if ($request->filled('searchText')) {
            $filterArrayName[] = ['name', 'LIKE', '%' . $searchText . '%'];
        }
        if ($request->filled('department_id')) {
            $filterArrayDepartmentId[] = ['department_id', $department_id];
        }
        if ($request->filled('employee_id')) {
            $filterArrayEmployeeId[] = ['employee_id', $employee_id];
        }
        if ($request->filled('branch_id')) {
            $filterArrayBranchId[] = ['branch_id', $branch_id];
        }
        $employees = User::query()
            ->where($filterArrayName)
            ->where($filterArrayDepartmentId)
            ->where($filterArrayEmployeeId)
            ->where($filterArrayBranchId)
            ->whereUserType($this->EmployeeUserType)
            ->with('branch','department')
            ->latest()
            ->paginate($perPage, ['*'], 'page');

            return EmployeeResource::collection($employees)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);


    }

    /**
     *
     * @OA\Post(
     *      path="/admin/employee/insert",
     *      operationId="InsertEmployee",
     *      tags={"EMPLOYEE"},
     *      summary="insert a New Employee",
     *      description="insert a New Employee",
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
     *                      property="full_name",
     *                      description="name of the Employee",
     *                      type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="branch_id",
     *                      description="branch_id of the Employee",
     *                      type="integer",
     *                   ),
     *                   @OA\Property(
     *                      property="department_id",
     *                      description="department_id of the Employee",
     *                      type="integer",
     *                   ),
     *                   @OA\Property(
     *                      property="email",
     *                      description="email of the Employee",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="phone",
     *                      description="phone of the Employee",
     *                      type="integer",
     *                   ),
     *                   @OA\Property(
     *                      property="date_of_birth",
     *                      description="date_of_birth of the Employee",
     *                      type="date",
     *                   ),
     *                   @OA\Property(
     *                      property="join_date",
     *                      description="join_date of the Employee",
     *                      type="date",
     *                   ),
     *                   @OA\Property(
     *                      property="permanent_address",
     *                      description="permanent_address of the Employee",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="present_address",
     *                      description="present_address of the Employee",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="employee_shift_id",
     *                      description="employee_shift_id of the Employee",
     *                      type="integer",
     *                   ),
     *                   @OA\Property(
     *                      property="gender",
     *                      description="gender of the Employee",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="salary",
     *                      description="salary of the Employee",
     *                      type="integer",
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
    public function InsertEmployee(EmployeeRequest $request){
        try {
            $password = Helper::GeneratePassword();
            $GlobalSettings='';
            $employee = $this->EmployeeService->createEmployee($request,$password);

            $this->dispatch(new EmployeeWelcomeJob($employee->email,$employee->full_name,$password,$GlobalSettings));

            activity()
            ->causedBy(auth()->user())
            ->performedOn($employee)
            ->log('Create New Employee');
            return EmployeeResource::make($employee->load('branch','department'))->additional([
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
     *      path="/admin/employee/update",
     *      operationId="UpdateEmployee",
     *      tags={"EMPLOYEE"},
     *      summary="update a employee",
     *      description="update a employee",
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
     *                      description="id of the employee",
     *                      type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="full_name",
     *                      description="name of the Employee",
     *                      type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="branch_id",
     *                      description="branch_id of the Employee",
     *                      type="integer",
     *                   ),
     *                   @OA\Property(
     *                      property="department_id",
     *                      description="department_id of the Employee",
     *                      type="integer",
     *                   ),
     *                   @OA\Property(
     *                      property="email",
     *                      description="email of the Employee",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="phone",
     *                      description="phone of the Employee",
     *                      type="integer",
     *                   ),
     *                   @OA\Property(
     *                      property="date_of_birth",
     *                      description="date_of_birth of the Employee",
     *                      type="date",
     *                   ),
     *                   @OA\Property(
     *                      property="join_date",
     *                      description="join_date of the Employee",
     *                      type="date",
     *                   ),
     *                   @OA\Property(
     *                      property="permanent_address",
     *                      description="permanent_address of the Employee",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="present_address",
     *                      description="present_address of the Employee",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="employee_shift_id",
     *                      description="employee_shift_id of the Employee",
     *                      type="integer",
     *                   ),
     *                   @OA\Property(
     *                      property="gender",
     *                      description="gender of the Employee",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="salary",
     *                      description="salary of the Employee",
     *                      type="integer",
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

     public function UpdateEmployee(EmployeeUpdateRequest $request){

        $employee = User::findOrFail($request->id);
        try {
            $employeeUpdated = $this->EmployeeService->updateEmployeeService($request, $employee);
            activity()
            ->causedBy(auth()->user())
            ->performedOn($employeeUpdated)
            ->log('Update Employee!!');
            return EmployeeResource::make($employeeUpdated->load('branch','department'))->additional([
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
     *      path="/admin/employee/delete",
     *      operationId="DeleteEmployee",
     *      tags={"EMPLOYEE"},
     *      summary="delete a employee",
     *      description="delete a employee",
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
     *                      description="id of the employee",
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

     public function DeleteEmployee(Request $request)
     {
         $request->validate([
             'id' => 'required|exists:users,id,deleted_at,NULL'
         ]);
         $employee = User::whereId($request->id)->whereUserType($this->EmployeeUserType)->first();
         try {
             $this->EmployeeService->deleteEmployee($employee);
             activity()
             ->causedBy(auth()->user())
             ->performedOn($employee)
             ->log('Employee Deleted!!');
             return $this->sendResponse([], $this->deleteSuccessMessage, Response::HTTP_OK);

         } catch (\Throwable $th) {
             //throw $th;
             return $this->sendError($th->getMessage(), [], 500);
         }
     }

}
