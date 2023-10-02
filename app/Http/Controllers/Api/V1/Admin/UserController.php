<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserRequest;
use App\Http\Resources\Admin\User\UserResource;
use App\Http\Services\Admin\User\UserService;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\RoleTrait;
use App\Http\Traits\UserTrait;
use App\Jobs\UserCreateJob;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use MessageTrait,UserTrait,RoleTrait;
    private $UserService;

    public function __construct(UserService $UserService) {
        $this->UserService = $UserService;
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
}
