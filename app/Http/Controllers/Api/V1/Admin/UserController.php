<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserRequest;
use App\Http\Requests\Admin\User\UserUpdateRequest;
use App\Http\Resources\Admin\Office\OfficeResource;
use App\Http\Resources\Admin\User\UserResource;
use App\Http\Services\Admin\User\UserService;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\RoleTrait;
use App\Http\Traits\UserTrait;
use App\Jobs\UserCreateJob;
use App\Models\Office;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use MessageTrait,UserTrait,RoleTrait;
    private $UserService;

    public function __construct(UserService $UserService) {
        $this->UserService = $UserService;
    }

    /**
    * @OA\Get(
    *     path="/admin/user/get",
    *      operationId="getAllUserPaginated",
    *      tags={"ADMIN-USER"},
    *      summary="get paginated users",
    *      description="get paginated users",
    *      security={{"bearer_token":{}}},
    *     @OA\Parameter(
    *         name="searchText",
    *         in="query",
    *         description="search by name, phone, email, username",
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="userId",
    *         in="query",
    *         description="search by user_id",
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="officeId",
    *         in="query",
    *         description="search by office_id",
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="perPage",
    *         in="query",
    *         description="number of division per page",
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

 public function getAllUserPaginated(Request $request){
    // Retrieve the query parameters
    $searchText = $request->query('searchText');
    $userId = $request->query('userId');
    $officeId = $request->query('officeId');
    $perPage = $request->query('perPage');
    $page = $request->query('page');

    $filterArrayName=[];
    $filterArrayUserName=[];
    $filterArrayUserId=[];
    $filterArrayEmail=[];
    $filterArrayPhone=[];
    $filterArrayOfficeId=[];

    if ($searchText) {
        $filterArrayName[] = ['full_name', 'LIKE', '%' . $searchText . '%'];
        $filterArrayUserName[] = ['username', 'LIKE', '%' . $searchText . '%'];
        $filterArrayUserId[] = ['user_id', 'LIKE', '%' . $userId . '%'];
        $filterArrayEmail[] = ['email', 'LIKE', '%' . $searchText . '%'];
        $filterArrayPhone[] = ['mobile', 'LIKE', '%' . $searchText . '%'];
        $filterArrayOfficeId[] = ['office_id', 'LIKE', '%' . $officeId . '%'];
    }

        // check this user is super-admin or not if not then check this user is office head or not if yes then get users under this office
            if(auth()->user()->user_type != $this->superAdminId && Auth::user()->hasRole($this->officeHead)){
                $users = User::query()
                ->where(function ($query) use ($filterArrayName,$filterArrayUserName,$filterArrayUserId,$filterArrayEmail,$filterArrayPhone,$filterArrayOfficeId) {
                    $query->where($filterArrayName)
                          ->orWhere($filterArrayUserName)
                          ->orWhere($filterArrayUserId)
                          ->orWhere($filterArrayEmail)
                          ->orWhere($filterArrayOfficeId)
                          ->orWhere($filterArrayPhone);
                })
                ->where('office_id',auth()->user()->office_id)
                ->where('user_type','!=',$this->superAdminId)
                ->whereHas('office', function ($query) {
                // assign_location_id is locations id of office get location all users and location
                $query->where('assign_location_id',auth()->user()->office->assign_location_id);
                //and assign_location_id location one child down user office head
                $query->orWhere('assign_location_id',auth()->user()->office?->location?->parent_id);
                })
                ->with('office','assign_location','office_type','roles')
                ->latest()
                ->paginate($perPage, ['*'], 'page');
            }else{
    $users = User::query()
    ->where(function ($query) use ($filterArrayName,$filterArrayUserName,$filterArrayUserId,$filterArrayEmail,$filterArrayPhone,$filterArrayOfficeId) {
        $query->where($filterArrayName)
              ->orWhere($filterArrayUserName)
              ->orWhere($filterArrayUserId)
              ->orWhere($filterArrayEmail)
              ->orWhere($filterArrayOfficeId)
              ->orWhere($filterArrayPhone);
    })
    ->with('office','assign_location','office_type','roles')
    ->latest()
    ->paginate($perPage, ['*'], 'page');
}

    return UserResource::collection($users)->additional([
        'success' => true,
        'message' => $this->fetchSuccessMessage,
    ]);
}

    /**
     *
     * @OA\Post(
     *      path="/admin/user/insert",
     *      operationId="insertUser",
     *      tags={"ADMIN-USER"},
     *      summary="insert a user",
     *      description="insert a user",
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
     *                      description="full name",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="username",
     *                      description="unique username",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="mobile",
     *                      description="Mobile number",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="email",
     *                      description="user email address",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="role_id[0]",
     *                      description="id of role",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="status",
     *                      description="enter status. ex: 0 => pending, 1 => active",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="office_type",
     *                      description="id of office type",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="office_id",
     *                      description="id of office",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="division_id",
     *                      description="id of division",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="district_id",
     *                      description="id of district",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="thana_id",
     *                      description="id of thana",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="city_corpo_id",
     *                      description="id of city corporation",
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
    public function insertUser(UserRequest $request){
        $password = Helper::GeneratePassword();
        // check any user assign this office as a officeHead role permission or not and this request roles has officeHead role or not
        if($request->has('role_id')){
            $role = Role::whereName($this->officeHead)->first();
            if(in_array($role->id,$request->role_id)){
                $officeHead = User::where('office_id',$request->office_id)->whereHas('roles', function ($query) {
                    $query->where('name', $this->officeHead);
                })->first();
                if($officeHead){
                    return $this->sendError('This office already has a office head', [], 500);
                }
            }
        }



        try {
            $user = $this->UserService->createUser($request,$password);

            $this->dispatch(new UserCreateJob($user->email,$user->full_name,$password));

            activity("User")
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('User Created !');
            return UserResource::make($user)->additional([
                'success' => true,
                'message' => $this->insertSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        if ($request->_method == 'PUT')
        {

            try {

                if($request->has('role_id')){
                    $role = Role::whereName($this->officeHead)->first();
                    if(in_array($role->id,$request->role_id)){
                        $officeHead = User::where('office_id',$request->office_id)->whereHas('roles', function ($query) {
                            $query->where('name', $this->officeHead);
                        })->first();
                        if($officeHead){
                            return $this->sendError('This office already has a office head', [], 500);
                        }
                    }
                }

                $user = $this->UserService->upddateUser($request, $id);


                activity("User")
                    ->causedBy(auth()->user())
                    ->performedOn($user)
                    ->log('User Created !');
                return UserResource::make($user)->additional([
                    'success' => true,
                    'message' => $this->updateSuccessMessage,
                ]);

            }catch (\Exception $e){
                \DB::rollBack();

                $error = $e->getMessage();

                return $this->sendError($error, [], 500);
            }
        }
    }

    /**
     *
     * @OA\Post(
     *      path="/admin/user/office/by-location",
     *      operationId="getOfficeByLocationAssignId",
     *      tags={"SYSTEM-OFFICE-MANAGEMENT"},
     *      summary="get a office",
     *      description="get a office",
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
     *                      property="assign_location_id",
     *                      description="get office by assign location Id",
     *                      type="integer",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="office_type_id",
     *                      description="get office by office type Id",
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
    public function getOfficeByLocationAssignId(Request $request){
        try {
            $getAssignLocationId = [];
            if ($request->has('location_type_id')) {
                $getAssignLocationId[] = ['assign_location_id',$request->location_type_id];
            }
            $office = Office::query()
                ->where(function ($query) use ($getAssignLocationId, $request) {
                    $query->where($getAssignLocationId)
                        ->where('office_type', $request->office_type_id);
                })
                ->with('assignLocation.parent.parent.parent', 'assignLocation.locationType', 'officeType')
                ->get();

            return OfficeResource::collection($office)->additional([
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
     * @OA\Delete(
     *      path="/admin/user/destroy/{id}",
     *      operationId="destroyUser",
     *      tags={"ADMIN-USER"},
     *      summary="delete a user",
     *      description="delete a user",
     *      security={{"bearer_token":{}}},
     *
     *
     *       @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id of user",
     *         required=true,
     *         @OA\Schema(type="integer")
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
     *        )
     *     )
     *
     */

    public function destroyUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            activity("User")
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log('User Deleted !');
            return UserResource::make($user)->additional([
                'success' => true,
                'message' => $this->deleteSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }
}
