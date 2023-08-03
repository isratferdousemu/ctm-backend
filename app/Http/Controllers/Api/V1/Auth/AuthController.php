<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Events\RealTimeMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminAuthResource;
use App\Http\Services\Auth\AuthService;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\UserTrait;
use App\Models\User;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    use UserTrait, MessageTrait;
    private $authService;
    public function __construct(
        AuthService $authService
    ) {
        $this->authService = $authService;
        // $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'store']]);
        // $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     *
     * @OA\Post(
     *      path="/admin/login/otp",
     *      operationId="LoginAdminOtp",
     *      tags={"Auth"},
     *      summary="Login to the Application",
     *      description="login to the application",
     *
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="Pass user credentials",
     *           @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(
     *
     *                   @OA\Property(
     *                      property="email",
     *                      description="user email",
     *                      type="string",
     *                   ),
     *                  @OA\Property(
     *                      property="password",
     *                      description="password",
     *                      type="text",
     *
     *                   ),
     *
     *               ),
     *               ),
     *
     *         ),
     *
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
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *
     *          )
     *        )
     *     )
     *
     */

     public function LoginAdminOtp(Request $request)
     {

         //validate login
         $this->authService->validateLogin($request);
         //login
         $data = $this->authService->Adminlogin($request,1);

         activity("Login")
         ->log('Login OTP Send!!');

         return response()->json(['success' => true, 'message' => 'Verification OTP Sent!', 'data' => $data]);

     }
    /**
     *
     * @OA\Post(
     *      path="/admin/login",
     *      operationId="LoginAdmin",
     *      tags={"Auth"},
     *      summary="Login to the Application",
     *      description="login to the application",
     *
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="Pass user credentials",
     *           @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(
     *
     *                   @OA\Property(
     *                      property="device_token",
     *                      description="Browser Fingerprint",
     *                      type="string",
     *                   ),
     *                      property="otp",
     *                      description="OTP code",
     *                      type="string",
     *                   ),
     *                   @OA\Property(
     *                      property="email",
     *                      description="user email",
     *                      type="string",
     *                   ),
     *                  @OA\Property(
     *                      property="password",
     *                      description="password",
     *                      type="text",
     *
     *                   ),
     *
     *               ),
     *               ),
     *
     *         ),
     *
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
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *
     *          )
     *        )
     *     )
     *
     */

     public function LoginAdmin(Request $request)
     {
        broadcast(new RealTimeMessage('Hello World! I am an event 😄'));

         //validate login
         $this->authService->validateLogin($request);
         //login
         $authData = $this->authService->Adminlogin($request,2);
         $permissions = $authData['user']->getAllPermissions();

         activity()
         ->causedBy(auth()->user())
         ->performedOn($authData['user'])
         ->log('Logged In!!');

         return AdminAuthResource::make($authData['user'])
             ->token($authData['token'])
             ->permissions($permissions)
             ->success(true)
             ->message("Login Success");
     }

      /**
     * /**
     * @OA\Get(
     *      path="/admin/logout",
     *      summary="Logout From The Application",
     *      description="Logout user and invalidate token",
     *      operationId="LogoutAdmin",
     *      tags={"Auth"},
     *      security={{"bearer_token":{}}},
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Returns when user is not authenticated",
     *
     *  )
     * )

     */
    public function LogoutAdmin(Request $request)
    {
        $this->authService->logout($request);
        return new JsonResponse([], 204);
    }


      /**
     * /**
     * @OA\Get(
     *      path="/admin/tokens",
     *      summary="all token The Application",
     *      description="all token",
     *      operationId="adminTokens",
     *      tags={"Auth"},
     *      security={{"bearer_token":{}}},
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Returns when user is not authenticated",
     *
     *  )
     * )

     */
    public function adminTokens(){
        // return PersonalAccessToken::all();
        return $tokens = Auth()->user()->tokens;
    }

    /**
    * @OA\Get(
    *     path="/admin/users/blocked/list",
    *      operationId="getAllBlockedUsers",
    *      tags={"Auth"},
    *      summary="get paginated block users",
    *      description="get paginated block users",
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
    *         description="number of users per page",
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

    public function getAllBlockedUsers(Request $request){
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage');
        $page = $request->query('page');

        $filterArrayName=[];
        $filterArrayEmail=[];

        if ($searchText) {
            $filterArrayName[] = ['full_name', 'LIKE', '%' . $searchText . '%'];
            $filterArrayEmail[] = ['email', 'LIKE', '%' . $searchText . '%'];
        }
        $users = User::query()
            ->where(function ($query) use ($filterArrayName, $filterArrayEmail) {
                $query->where($filterArrayName)
                    ->orWhere($filterArrayEmail);
        })
        ->whereStatus($this->userAccountBanned)
        ->latest()
        ->paginate($perPage, ['*'], 'page');

        return AdminAuthResource::collection($users)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }

    /**
     *
     * @OA\Post(
     *      path="/admin/users/unblock",
     *      operationId="unBlockUser",
     *      tags={"Auth"},
     *      summary="update a blocked user",
     *      description="update a blocked user",
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
     *                      property="user_id",
     *                      description="id of the user",
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
    public function unBlockUser(Request $request){
        $validator = Validator::make(['id' => $request->user_id], [
            'id' => 'required|exists:users,id,deleted_at,NULL',
        ]);
        $validator->validated();

        DB::beginTransaction();
        try {
                $user = User::findOrFail($request->user_id);
                $user->status = $this->userAccountApproved;
                $user->save();
                DB::commit();

                activity("User")
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log('User Unblocked');
         return $this->sendResponse($user, $this->updateSuccessMessage, Response::HTTP_OK);


        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
