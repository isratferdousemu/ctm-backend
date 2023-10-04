<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\PermissionRequest;
use App\Http\Requests\Admin\Role\RoleRequest;
use App\Http\Requests\Admin\Role\RoleUpdateRequest;
use App\Http\Resources\Admin\Role\RoleResource;
use App\Http\Services\Admin\Role\RoleService;
use App\Http\Traits\MessageTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use MessageTrait;
    private $RoleService;

    public function __construct(RoleService $RoleService) {
        $this->RoleService = $RoleService;

    }

    /**
    * @OA\Get(
    *     path="/admin/role/all/filtered",
    *      operationId="getAllRolePaginated",
    *      tags={"ADMIN-ROLE"},
    *      summary="get paginated role",
    *      description="get paginated role",
    *      security={{"bearer_token":{}}},
    *     @OA\Parameter(
    *         name="searchText",
    *         in="query",
    *         description="search by name",
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="perPage",
    *         in="query",
    *         description="number of role per page",
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

 public function getAllRolePaginated(Request $request){
    // Retrieve the query parameters
     $role = new Role;

     if($request->has('sortBy'))
     {
         if($request->get('sortDesc') === true)
         {
             $role = $role->orderBy($request->get('sortBy'), 'desc');
         }else{
             $role = $role->orderBy($request->get('sortBy'), 'asc');
         }
     }else{
         $role = $role->orderBy('id', 'desc');
     }

     $searchValue = $request->input('search');

     if($searchValue)
     {
         $role->where(function($query) use ($searchValue) {
             $query->orWhere('name_bn', 'like', '%' . $searchValue . '%');
             $query->orWhere('code', 'like', '%' . $searchValue . '%');
             $query->orWhere('name_en', 'like', '%' . $searchValue . '%');
         });
     }

     $itemsPerPage = 10;

     if($request->has('itemsPerPage'))
     {
         $itemsPerPage = $request->get('itemsPerPage');
     }

     return $role->paginate($itemsPerPage);
}
    /**
    * @OA\Get(
    *     path="/admin/role/permission/roles/unassign",
    *      operationId="getUnAssignPermissionRole",
    *      tags={"ADMIN-PERMISSIONS"},
    *      summary="get all role",
    *      description="get all role",
    *      security={{"bearer_token":{}}},
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
    * )
    */

 public function getUnAssignPermissionRole(){

    //$role =Role::whereDoesntHave('permissions')->get();
    $role =Role::where('name','!=','super-admin')->get();


    return RoleResource::collection($role)->additional([
        'success' => true,
        'message' => $this->insertSuccessMessage,
    ]);
}
    /**
    * @OA\Get(
    *     path="/admin/role/permission/roles/all",
    *      operationId="getAllRole",
    *      tags={"ADMIN-PERMISSIONS"},
    *      summary="get all role",
    *      description="get all role",
    *      security={{"bearer_token":{}}},
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
    * )
    */

 public function getAllRole(){

    $role = Role::with('permissions')->get();


    return RoleResource::collection($role)->additional([
        'success' => true,
        'message' => $this->insertSuccessMessage,
    ]);
}

     /**
     *
     * @OA\Post(
     *      path="/admin/role/insert",
     *      operationId="insert",
     *      tags={"ADMIN-ROLE"},
     *      summary="insert a role",
     *      description="insert a role",
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
     *                      property="code",
     *                      description="code of the role",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_en",
     *                      description="name of the role",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_bn",
     *                      description="Native name of the role",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="comment",
     *                      description="comment of the role",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="status",
     *                      description="status of the role",
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
    public function insert(RoleRequest $request){
        try {
            //code...
            $role = $this->RoleService->createRole($request);
            activity("Role")
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->log('Role Created !');
            return RoleResource::make($role->load('permissions'))->additional([
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
     *      path="/admin/role/edit/{id}",
     *      operationId="edit",
     *      tags={"ADMIN-ROLE"},
     *      summary="get edit role data",
     *      description="Returns Role Details by id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of Role to return",
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
    public function edit(Request $request)
    {

         // return $id;
        //  $request->validate([
        //     'id' => "required|exists:roles,id"
        // ]);
        $role = Role::whereId($request->id)->whereDefault(0)->first();

        return RoleResource::make($role->load("permissions"))->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage
        ]);
    }

     /**
     *
     * @OA\Post(
     *      path="/admin/role/update",
     *      operationId="updateRole",
     *      tags={"ADMIN-ROLE"},
     *      summary="update a role",
     *      description="update a role",
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
     *                      description="id of the role",
     *                      type="text",
     *
     *                   ),
 *                   @OA\Property(
     *                      property="code",
     *                      description="code of the role",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_en",
     *                      description="name of the role",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_bn",
     *                      description="Native name of the role",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="comment",
     *                      description="comment of the role",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="status",
     *                      description="status of the role",
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
    public function updateRole(RoleUpdateRequest $request){
        try {
            //code...
        $role = $this->RoleService->updateRole($request);
            activity("Role")
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->log('Role Updated !');
            return RoleResource::make($role->load('permissions'))->additional([
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
     *      path="/admin/role/destroy/{id}",
     *      operationId="destroRole",
     *      tags={"ADMIN-ROLE"},
     *      summary=" destroy role data",
     *      description="Returns Role destroy by id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of Role to return",
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
    public function destroyRole($id)
    {

        $role = Role::whereId($id)->whereDefault(0)->first();
        if($role){
            $role->delete();
        }
        activity("Role")
        ->causedBy(auth()->user())
        ->log('Role Deleted!!');
         return $this->sendResponse($role, $this->deleteSuccessMessage, Response::HTTP_OK);
    }

    /* -------------------------------------------------------------------------- */
    /*                             Permission Function                            */
    /* -------------------------------------------------------------------------- */

    /**
    * @OA\Get(
    *     path="/admin/role/permission/get",
    *      operationId="getAllPermission",
    *      tags={"ADMIN-PERMISSIONS"},
    *      summary="get permissions",
    *      description="get permissions",
    *      security={{"bearer_token":{}}},
    *     @OA\Parameter(
    *         name="searchText",
    *         in="query",
    *         description="search by name",
    *         @OA\Schema(type="string")
    *     ),
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
    * )
    */

 public function getAllPermission(Request $request){
        // Retrieve the query parameters

        $permissions = Permission::latest()->get();

         $result = [];

         $data = [];

         $result_data = [];

        foreach ($permissions as $permission)
        {
            $name = explode('-', $permission->name);

            $result[$permission->module_name][] = $permission;

            $result_data[$permission->module_name][$name[0]][] = $permission;
        }


        foreach ($result as $key => $r)
        {
            $data[] = array("name" => $key);
        }

        return \response()->json(['data' => $data, 'result' => $result_data]);

    }

     /**
     *
     * @OA\Post(
     *      path="/admin/role/permission/assign",
     *      operationId="AssignPermissionRole",
     *      tags={"ADMIN-PERMISSIONS"},
     *      summary="assign permission to a role",
     *      description="assign permission to a role",
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
     *                      property="role_id",
     *                      description="id of the role",
     *                      type="text",
     *                   ),
     *          @OA\Property(property="permissions[0]", type="integer"),
     *          @OA\Property(property="permissions[1]", type="integer"),
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
    public function AssignPermissionRole(PermissionRequest $request){
        try {
            //code...
            $role = $this->RoleService->AssignPermissionToRole($request);
            activity("Permission")
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->log('Permission Assign successfully !');
            return RoleResource::make($role->load('permissions'))->additional([
                'success' => true,
                'message' => $this->insertSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function rolePermissionEdit($id)
    {
        $rolePermissions = \DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        $rp_array = [];
        foreach ($rolePermissions as $rp) {
            $rp_array[] = $rp;
        }

        return \response()->json([
            'success' => true,
            'role_permission' => $rp_array,
            'status_code' => 200
        ], Response::HTTP_OK);
    }
}
