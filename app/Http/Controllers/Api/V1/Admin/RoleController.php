<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\RoleRequest;
use App\Http\Resources\Admin\Role\RoleResource;
use App\Http\Services\Admin\Role\RoleService;
use App\Http\Traits\MessageTrait;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use MessageTrait;
    private $RoleService;

    public function __construct(RoleService $RoleService) {
        $this->RoleService = $RoleService;

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
     *                      property="name",
     *                      description="name of the role",
     *                      type="text",
     *
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
    public function insert(RoleRequest $request){
        try {
            //code...
           return $role = $this->RoleService->createRole($request);
            activity()
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
}
