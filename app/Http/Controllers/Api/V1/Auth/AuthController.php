<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminAuthResource;
use App\Http\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{

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
         //validate login
         $this->authService->validateLogin($request);
         //login
         $authData = $this->authService->Adminlogin($request);
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
}
