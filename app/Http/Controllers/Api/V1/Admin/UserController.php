<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserRequest;
use App\Http\Requests\Admin\User\UserUpdateRequest;
use App\Http\Resources\Admin\Office\OfficeResource;
use App\Http\Resources\Admin\User\UserResource;
use App\Http\Services\Admin\User\OfficeHeadService;
use App\Http\Services\Admin\User\UserService;
use App\Http\Services\Notification\SMSservice;
use App\Http\Traits\LocationTrait;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\PermissionTrait;
use App\Http\Traits\RoleTrait;
use App\Http\Traits\UserTrait;
use App\Jobs\UserCreateJob;
use App\Models\Location;
use App\Models\Office;
use App\Models\User;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use MessageTrait,UserTrait,RoleTrait, PermissionTrait, LocationTrait;
    private $UserService;

    public function __construct(UserService $UserService, public OfficeHeadService $officeHeadService, public SMSservice $SMSservice) {
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
     *
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="user_id",
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
    $users = User::where(function ($query) use ($filterArrayName,$filterArrayUserName,$filterArrayUserId,$filterArrayEmail,$filterArrayPhone,$filterArrayOfficeId) {
        $query->where($filterArrayName)
              ->orWhere($filterArrayUserName)
//              ->orWhere($filterArrayUserId)
              ->orWhere($filterArrayEmail)
//              ->orWhere($filterArrayOfficeId)
              ->orWhere($filterArrayPhone)
        ;
    })
        ->whereIn('id', $this->officeHeadService->getUsersUnderOffice())
    ->with('office','assign_location.parent.parent.parent.parent','officeTypeInfo','roles', 'committee')
    ->orderByDesc('id')
    ->paginate($perPage, ['*'], 'page');
// }
    return $users;
    return UserResource::collection($users)->additional([
        'success' => true,
        'message' => $this->fetchSuccessMessage,
    ]);
}


    public function getUsersId()
    {
        return $this->officeHeadService->getUsersUnderOffice();
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
     *                      property="committee_id",
     *                      description="id of Committtee",
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


    //user Logics
    // 1. check if user is super admin or not
    // 2. if not super admin then check if user is office head or not

    // Users under Offices
    // 1. if user is office head then check if office already has a office head or not
    // 2. if office already has a office head then return error
    // 3. if office does not have a office head then system will allow to create office head user else create any office user except office Head
    // 4. if user is super admin then create any user

    // Users under Committees
    ////// Some rules for creating users under committees
    ///// Commiittee does not must belong to a office But there is ID assigned to it.

    // 1. if the user has a committee type then the user will be created under that committee type - which means that the users will have the committtee ID

    public function insertUser(UserRequest $request){

        $password = Helper::GeneratePassword();
        // check any user assign this office as a officeHead role permission or not and this request roles has officeHead role or not
        if($request->role_id && $request->user_type == 1){
            $role = Role::whereName($this->officeHead)->first();
            if(in_array($role->id,$request->role_id)){
                $officeHead = User::where('office_id',$request->office_id)->whereHas('roles', function ($query) {
                    $query->where('name', $this->officeHead);
                })->first();
                if($officeHead){
                    return $this->sendError('This office already has a office head', [
                        'office_id'=>'This office already has a office head',
                    ], 422);
                }
            }
        }



            $user = $this->UserService->createUser($request,$password);


            activity("User")
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('User Created !');

            return UserResource::make($user)->additional([
                'success' => true,
                'message' => $this->insertSuccessMessage,
            ]);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            if($request->role_id && $request->user_type == 1){
                $role = Role::whereName($this->officeHead)->first();
                if(in_array($role->id,$request->role_id)){
                    $officeHead = User::where('office_id',$request->office_id)->whereHas('roles', function ($query) {
                        $query->where('name', $this->officeHead);
                    })->first();
                    if($officeHead && $officeHead->id != $id){
                        return $this->sendError('This office already has a office head', [], 500);
                    }
                }
            }

            $user = $this->UserService->upddateUser($request, $id);


            activity("User")
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log('User updated !');
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



    public function approve($id)
    {
        $password = Helper::GeneratePassword();

        $user = User::findOrFail($id);
        $user->status = !$user->status;
        $user->password = bcrypt($user->salt . $password);
        $user->save();

        $tokenLink = env('APP_FRONTEND_URL') . '/browser-token';

        $message = "Welcome to the CTM application.Your account has been approved.".
            "\nTo register your device please visit {$tokenLink} then copy the code and provide it to your supervisor."
            .
        "\nOnce your device is registered you can access the CTM Application using following credentials:
        \nUsername: ". $user->username
            ."\nPassword: ". $password .
            "\nLogin URL: ". env('APP_FRONTEND_URL') . '/login'
        ;

        Log::info('password-'. $user->id, [$message]);

//        $user->mobile = "01747970935";
//        $user->email = "tarikul5357@gmail.com";


        $this->SMSservice->sendSms($user->mobile, $message);

        $this->dispatch(new UserCreateJob($user->email,$user->username, $password));


        activity("User")
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('User approval ');

        $status = $user->status ? 'approved' : 'deactivated';

        return $this->sendResponse($user, "User has been $status");
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
                ->whereStatus(1)
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


    public function getRoles()
    {
//        $isAdmin = auth()->user()->hasRole($this->superAdmin);

        $roles[] = $this->committee;
        $roles[] = $this->superAdmin;

//        if (!$isAdmin) {
//            $roles[] = $this->superAdmin;
//        }


        return Role::whereNotIn('name', $roles)->get();
    }



}
