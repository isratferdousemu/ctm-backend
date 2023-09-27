<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Menu\MenuRequest;
use App\Http\Requests\Admin\Menu\MenuUpdateRequest;
use App\Http\Resources\Admin\Menu\MenuResource;
use App\Http\Services\Admin\Menu\MenuService;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\UserTrait;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class MenuController extends Controller
{
    use MessageTrait, UserTrait;

    private $MenuService;

    public function __construct(MenuService $MenuService)
    {
        $this->MenuService = $MenuService;
    }

    /**
     * @OA\Get(
     *     path="/admin/menu/get",
     *      operationId="getAllMenu",
     *      tags={"MENU-MANAGEMENT"},
     *      summary="get all menus",
     *      description="get all menus",
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

    public function getAllMenu(Request $request)
    {


//    $menus = Menu::with("children.children.pageLink","children.pageLink","pageLink")->whereParentId(null)->get();
//
//    return MenuResource::collection($menus)->additional([
//        'success' => true,
//        'message' => $this->fetchSuccessMessage,
//    ]);

        $menu = Menu::select(
            'menus.*',
            'permissions.page_url as link'
            )
            ->leftJoin('permissions', function ($join) {
                $join->on('menus.page_link_id', '=', 'permissions.id');
            });

        if($request->has('sortBy'))
        {
            if($request->get('sortDesc') === true)
            {
                $menu = $menu->orderBy($request->get('sortBy'), 'desc');
            }else{
                $menu = $menu->orderBy($request->get('sortBy'), 'asc');
            }
        }else{
            $menu = $menu->orderBy('id', 'desc');
        }

        $searchValue = $request->input('search');

        if($searchValue)
        {
            $menu->where(function($query) use ($searchValue) {
                $query->where('label_name_en', 'like', '%' . $searchValue . '%');
                $query->orWhere('label_name_bn', 'like', '%' . $searchValue . '%');
                $query->orWhere('link_type', 'like', '%' . $searchValue . '%');
            });
        }

        $itemsPerPage = 10;

        if($request->has('itemsPerPage'))
        {
            $itemsPerPage = $request->get('itemsPerPage');
        }

        return $menu->paginate($itemsPerPage);
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
     *                   @OA\Property(
     *                      property="link_type",
     *                      description="page link type. ex:1->external, 2->internal",
     *                      type="integer",
     *                   ),
     *                   @OA\Property(
     *                      property="link",
     *                      description="page link if link type is external",
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
    public function insertMenu(MenuRequest $request)
    {

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

    /**
     * @OA\Get(
     *     path="/admin/menu/get_page_url",
     *      operationId="getPageUrl",
     *      tags={"MENU-MANAGEMENT"},
     *      summary="get paginated role",
     *      description="get paginated role",
     *      security={{"bearer_token":{}}},
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

    public function getPageUrl()
    {
        $page_urls = Permission::select('id', 'page_url')->get();

        return response()->json([
            'page_urls' => $page_urls
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/admin/menu/get_parent",
     *      operationId="getParent",
     *      tags={"MENU-MANAGEMENT"},
     *      summary="get paginated role",
     *      description="get paginated role",
     *      security={{"bearer_token":{}}},
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
    public function getParent()
    {
        $parents = Menu::select('id', 'parent_id', 'label_name_en', 'label_name_bn')->where('parent_id', null)->get();

        return \response()->json([
            'parents' => $parents
        ], Response::HTTP_OK);
    }



    public function edit($id)
    {
        $menu = Menu::find($id);

        return \response()->json([
            'menu' => $menu
        ],Response::HTTP_OK);
    }

    /**
     *
     * @OA\Post(
     *      path="/admin/menu/update/{id}",
     *      operationId="update",
     *      tags={"MENU-MANAGEMENT"},
     *      summary="update a menu",
     *      description="update a menu",
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
     *                   @OA\Property(
     *                      property="link_type",
     *                      description="page link type. ex:1->external, 2->internal",
     *                      type="integer",
     *                   ),
     *                   @OA\Property(
     *                      property="link",
     *                      description="page link if link type is external",
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

    public function update(MenuUpdateRequest $request, $id)
    {
        if ($request->_method == 'PUT')
        {
            \DB::beginTransaction();

            try {

                $menu = Menu::findOrFail($id);

                $menu->label_name_en = $request->label_name_en;
                $menu->label_name_bn = $request->label_name_bn;
                $menu->order = $request->order;

                $menu->page_link_id = $request->page_link_id;

                $menu->link_type              = $request->link_type;
                $menu->link                   = $request->link;

                if ($request->parent_id == null)
                {
                    if ($menu->save()) {
                        $menu->parent_id = null;
                        $menu->save();
                    }
                }else{
                    if ($menu->save()) {
                        $menu->parent_id = $request->parent_id;
                        $menu->save();
                    }
                }

                 activity("Menu")
                 ->causedBy(auth()->user())
                 ->performedOn($menu)
                 ->log('Menu Updated !');

                DB::commit();

                return \response()->json([
                    'message' => 'Menu updated successful'
                ],Response::HTTP_OK);

            }catch (\Exception $e){
                \DB::rollBack();

                $error = $e->getMessage();

                return \response()->json([
                    'error' => $error
                ],Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    /**
     * @OA\Get(
     *     path="/admin/menu/destroy/{id}",
     *      operationId="destroy",
     *      tags={"MENU-MANAGEMENT"},
     *      summary="destroy menu",
     *      description="destroy menu from menu lists",
     *      security={{"bearer_token":{}}},
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
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return \response()->json([
            'message' => 'Menu destroy successful'
        ],Response::HTTP_OK);
    }
}
