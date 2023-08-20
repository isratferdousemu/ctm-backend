<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Menu\MenuRequest;
use App\Http\Resources\Admin\Menu\MenuResource;
use App\Http\Services\Admin\Menu\MenuService;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\UserTrait;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    use MessageTrait,UserTrait;
    private $MenuService;

    public function __construct(MenuService $MenuService) {
        $this->MenuService = $MenuService;
    }

    /**
     *
     * @OA\Post(
     *      path="/admin/menu/insert",
     *      operationId="insertMenu",
     *      tags={"MENU-MANAGEMENT"},
     *      summary="insert a menu",
     *      description="insert a menu",
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
     *                      property="label_name_en",
     *                      description="english name of the menu",
     *                      type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="label_name_bn",
     *                      description="bangla name of the menu",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="order",
     *                      description="sl of the menu",
     *                      type="integer",
     *                   ),
     *                   @OA\Property(
     *                      property="page_link_id",
     *                      description="page link id",
     *                      type="integer",
     *                   ),
     *                   @OA\Property(
     *                      property="parent_id",
     *                      description="parent menu id",
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
    public function insertMenu(MenuRequest $request){

        try {
            $menu = $this->MenuService->createMenu($request);
            activity("Menu")
            ->causedBy(auth()->user())
            ->performedOn($menu)
            ->log('Menu Created !');
            return MenuResource::make($menu)->additional([
                'success' => true,
                'message' => $this->insertSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }
}