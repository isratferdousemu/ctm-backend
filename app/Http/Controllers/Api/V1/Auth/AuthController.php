<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminAuthResource;
use App\Http\Services\Auth\AuthService;
use Illuminate\Http\Request;

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
     *                      property="user",
     *                      description="user email or phone number",
     *                      type="string",
     *                   ),
     *                  @OA\Property(
     *                      property="password",
     *                      description="password",
     *                      type="text",
     *
     *                   ),
     *                  @OA\Property(
     *                      property="device",
     *                      description="device id for generating token for the device.",
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
         $permissions = $authData['user']->getAllPermissions()->pluck('name')->toArray();

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
}
