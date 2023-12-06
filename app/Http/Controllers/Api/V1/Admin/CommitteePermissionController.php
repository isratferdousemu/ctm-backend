<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CommitteePermission\StoreRequest;
use App\Http\Services\Admin\CommitteePermission\CommitteePermissionService;

class CommitteePermissionController extends Controller
{

    public function __construct(public CommitteePermissionService $permissionService)
    {
    }

    /**
     * @OA\Get(
     *      path="/admin/committee-permissions",
     *      operationId="getCommitteePermissions",
     *      tags={"COMMITTEE-PERMISSION-MANAGEMENT"},
     *      summary="get committees with permissions list",
     *      description="Returns permissions of each committee",
     *      security={{"bearer_token":{}}},
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
     *          response=404,
     *          description="Not Found!"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *     )
     */
    public function index()
    {
        return $this->sendResponse(
            $this->permissionService->getCommitteePermissions()
        );
    }

    /**
     *
     * @OA\Post(
     *      path="/admin/committee-permissions",
     *      operationId="storeCommitteePermission",
     *      tags={"COMMITTEE-PERMISSION-MANAGEMENT"},
     *      summary="store permission of committee",
     *      description="store permission of committee",
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
     *                      property="committee_type_id",
     *                      description="id from lookup table by committee_type",
     *                      type="integer",
     *
     *                   ),
     *                   @OA\Property(
     *                       property="approve",
     *                       description="permission",
     *                       type="integer",
     *
     *                    ),
     *                   @OA\Property(
     *                       property="forward",
     *                       description="permission",
     *                       type="integer",
     *
     *                    ),
     *                        @OA\Property(
     *                        property="reject",
     *                        description="permission",
     *                        type="integer",
     *
     *                     ),
     *                        @OA\Property(
     *                        property="waiting",
     *                        description="permission",
     *                        type="integer",
     *
     *                     ),
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
    public function store(StoreRequest $request)
    {
        return $this->sendResponse($this->permissionService
            ->saveCommitteePermission($request)
        );

    }


    /**
     *
     * @OA\Post(
     *      path="/admin/committee-permissions/update",
     *      operationId="updateCommitteePermission",
     *      tags={"COMMITTEE-PERMISSION-MANAGEMENT"},
     *      summary="update permission of committee",
     *      description="update permission of committee will store new entry",
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
     *                      property="committee_type_id",
     *                      description="id from lookup table by committee_type",
     *                      type="integer",
     *
     *                   ),
     *                   @OA\Property(
     *                       property="approve",
     *                       description="permission",
     *                       type="integer",
     *
     *                    ),
     *                   @OA\Property(
     *                       property="forward",
     *                       description="permission",
     *                       type="integer",
     *
     *                    ),
     *                        @OA\Property(
     *                        property="reject",
     *                        description="permission",
     *                        type="integer",
     *
     *                     ),
     *                        @OA\Property(
     *                        property="waiting",
     *                        description="permission",
     *                        type="integer",
     *
     *                     ),
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
    public function update(StoreRequest $request)
    {
        return $this->sendResponse($this->permissionService
            ->saveCommitteePermission($request)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return $this->sendResponse($this->permissionService->deleteByCommitteeType($id));
    }
}
