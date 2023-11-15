<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Geographic\City\CityRequest;
use App\Http\Requests\Admin\Geographic\City\CityUpdateRequest;
use App\Http\Requests\Admin\Geographic\District\DistrictRequest;
use App\Http\Requests\Admin\Geographic\District\DistrictUpdateRequest;
use App\Http\Requests\Admin\Geographic\Division\DivisionRequest;
use App\Http\Requests\Admin\Geographic\Division\DivisionUpdateRequest;
use App\Http\Requests\Admin\Geographic\Thana\ThanaRequest;
use App\Http\Requests\Admin\Geographic\Thana\ThanaUpdateRequest;
use App\Http\Requests\Admin\Geographic\Uinion\UnionRequest;
use App\Http\Requests\Admin\Geographic\Uinion\UnionUpdateRequest;
use App\Http\Requests\Admin\Geographic\Village\VillageRequest;
use App\Http\Requests\Admin\Geographic\Village\VillageUpdateRequest;
use App\Http\Requests\Admin\Geographic\WardRequest;
use App\Http\Requests\Admin\Geographic\WardUpdateRequest;
use App\Http\Resources\Admin\Geographic\CityResource;
use App\Http\Resources\Admin\Geographic\DistrictResource;
use App\Http\Resources\Admin\Geographic\DivisionResource;
use App\Http\Resources\Admin\Geographic\UnionResource;
use App\Http\Resources\Admin\Geographic\VillageResource;
use App\Http\Resources\Admin\Geographic\WardResource;
use App\Http\Services\Admin\Location\LocationService;
use App\Http\Traits\LocationTrait;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\UserTrait;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

class LocationController extends Controller
{
    use MessageTrait, UserTrait, LocationTrait;
    private $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * @OA\Get(
     *     path="/admin/division/get",
     *      operationId="getAllDivisionPaginated",
     *      tags={"GEOGRAPHIC-DIVISION"},
     *      summary="get paginated Divisions",
     *      description="get paginated Divisions",
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

    public function getAllDivisionPaginated(Request $request)
    {
        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage');
        $page = $request->get('page');
        $sortBy = $request->query('sortBy') ?? 'name_en';
        $orderBy = $request->query('orderBy') ?? 'asc';
        
        $filterArrayNameEn = [];
        $filterArrayNameBn = [];
        $filterArrayCode = [];

        if ($searchText) {
            $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayCode[] = ['code', 'LIKE', '%' . $searchText . '%'];

            
            if ($searchText != null) {
                $page = 1;
            }
        }
        $division = Location::query()
            ->where(function ($query) use ($filterArrayNameEn, $filterArrayNameBn, $filterArrayCode) {
                $query->where($filterArrayNameEn)
                    ->orWhere($filterArrayNameBn)
                    ->orWhere($filterArrayCode);
            })
            ->whereParentId(null)
            // ->latest()
            ->orderBy($sortBy, $orderBy)
            ->paginate($perPage, ['*'], 'page', $page);


        return DivisionResource::collection($division)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }

    /**
     *
     * @OA\Post(
     *      path="/admin/division/insert",
     *      operationId="insertDivision",
     *      tags={"GEOGRAPHIC-DIVISION"},
     *      summary="insert a Division",
     *      description="insert a Division",
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
     *                      property="name_en",
     *                      description="english name of the Division",
     *                      type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="name_bn",
     *                      description="bangla name of the Division",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="code",
     *                      description="code of the Division",
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
    public function insertDivision(DivisionRequest $request)
    {

        try {
            $division = $this->locationService->createDivision($request);
            activity("Division")
                ->causedBy(auth()->user())
                ->performedOn($division)
                ->log('Division Created !');
            return DivisionResource::make($division)->additional([
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
     * @OA\Post(
     *      path="/admin/division/update",
     *      operationId="divisionUpdate",
     *      tags={"GEOGRAPHIC-DIVISION"},
     *      summary="update a Division",
     *      description="update a Division",
     *      security={{"bearer_token":{}}},
     *
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="enter inputs",
     *
     *            @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(
     *                   @OA\Property(
     *                      property="id",
     *                      description="id of the Division",
     *                      type="integer",
     *                   ),
     *                   @OA\Property(
     *                      property="name_en",
     *                      description="english name of the Division",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_bn",
     *                      description="bangla name of the Division",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="code",
     *                      description="code of the Division",
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
    public function divisionUpdate(DivisionUpdateRequest $request)
    {

        try {
            $division = $this->locationService->updateDivision($request);
            activity("Division")
                ->causedBy(auth()->user())
                ->performedOn($division)
                ->log('Division Update !');
            return DivisionResource::make($division)->additional([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/admin/division/destroy/{id}",
     *      operationId="destroyDivision",
     *      tags={"GEOGRAPHIC-DIVISION"},
     *      summary=" destroy divisions",
     *      description="Returns division destroy by id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of division to return",
     *         in="path",
     *         name="id",
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
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
    public function destroyDivision($id)
    {

        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:locations,id,deleted_at,NULL',
        ]);

        $validator->validated();

        $division = Location::whereId($id)->first();

        // check if division has any child if yes then return exception else delete
        if ($division->children->count() > 0) {

            return $this->sendError('This record cannot be deleted because it is linked to other data.', [], 500);
        }


        if ($division) {
            $division->delete();
        }

        activity("Division")
            ->causedBy(auth()->user())
            ->log('Division Deleted!!');
        return $this->sendResponse($division, $this->deleteSuccessMessage, Response::HTTP_OK);
    }


    /* -------------------------------------------------------------------------- */
    /*                                 District Function                          */
    /* -------------------------------------------------------------------------- */


    /**
     * @OA\Get(
     *     path="/admin/district/get",
     *      operationId="getAllDistrictPaginated",
     *      tags={"GEOGRAPHIC-DISTRICT"},
     *      summary="get paginated Districts",
     *      description="get paginated Districts",
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
     *         description="number of Districts per page",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page number",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="sortBy",
     *         in="query",
     *         description="sortBy column name",
     *         @OA\Schema(type="text")
     *     ),
     *     @OA\Parameter(
     *         name="orderBy",
     *         in="query",
     *         description="asc or desc",
     *         @OA\Schema(type="text")
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


    public function getAllDistrictPaginated(Request $request)
    {
        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage') ?? 10;
        $page = $request->query('page');
        $sortBy = $request->query('sortBy') ?? 'name_en';
        $orderBy = $request->query('orderBy') ?? 'asc';
        // if($orderBy){
        //     $orderBy='desc';
        // }else{
        //     $orderBy='asc';
        // }

        $filterArrayNameEn = [];
        $filterArrayNameBn = [];
        $filterArrayCode = [];

        $parent1filterArrayNameEn = [];
        $parent1filterArrayNameBn = [];
        $parent1filterArrayCode = [];

        if ($searchText) {
            $filterArrayNameEn[] = ['locations.name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayNameBn[] = ['locations.name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayCode[] = ['locations.code', 'LIKE', '%' . $searchText . '%'];

            $parent1filterArrayNameEn[] = ['parent1.name_en', 'LIKE', '%' . $searchText . '%'];
            $parent1filterArrayNameBn[] = ['parent1.name_bn', 'LIKE', '%' . $searchText . '%'];
            $parent1filterArrayCode[] = ['parent1.code', 'LIKE', '%' . $searchText . '%'];

            if ($searchText != null) {
                $page = 1;
            }
        }

        // if ($sortBy == 'parent.name_en') {
        //     $sortBy = 'parent.name_en';
        // } else if ($sortBy == 'name_bn') {
        //     $sortBy = 'parent.name_bn';
        // } else if ($sortBy == 'parent.code') {
        //     $sortBy = 'locations.code';
        // }

        // Level 3
        if ($sortBy == 'name_en') {
            $sortBy = 'name_en';
        }
        // Level 2
        if ($sortBy == 'parent.name_en') {
            $sortBy = 'parent1.name_en';
        }
        // Level 3
        if ($sortBy == 'name_bn') {
            $sortBy = 'name_bn';
        }
        // Level 2
        if ($sortBy == 'parent.name_bn') {
            $sortBy = 'parent1.name_bn';
        }

        $district = Location::query()
        ->join('locations as parent1', 'locations.parent_id', '=', 'parent1.id') // Join with the parent table
            ->select(
                'locations.*',
            )
            ->where(function ($query) use (
                $parent1filterArrayNameEn,
                $parent1filterArrayNameBn,
                $parent1filterArrayCode,
                $filterArrayNameEn,
                $filterArrayNameBn,
                $filterArrayCode
            ) {
                $query->where($filterArrayNameEn)
                    ->orWhere($filterArrayNameBn)
                    ->orWhere($filterArrayCode)

                    ->orWhereHas('parent', function ($query) use (
                        $parent1filterArrayNameEn,
                        $parent1filterArrayNameBn,
                        $parent1filterArrayCode,
                    ) {
                        $query->where($parent1filterArrayNameEn)
                            ->orWhere($parent1filterArrayNameBn)
                            ->orWhere($parent1filterArrayCode); // District Search
                    });
            })
            ->where('locations.type', '=', $this->district)
            ->orderBy($sortBy, $orderBy)
            ->with('parent')
            ->paginate($perPage, ['*'], 'page', $page);

        return $district;
        return DistrictResource::collection($district)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }


    /**
     *
     * @OA\Post(
     *      path="/admin/district/insert",
     *      operationId="insertDistrict",
     *      tags={"GEOGRAPHIC-DISTRICT"},
     *      summary="insert a district",
     *      description="insert a district",
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
     *                      property="division_id",
     *                      description="id of division",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_en",
     *                      description="english name of the district",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_bn",
     *                      description="bangla name of the district",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="code",
     *                      description="code of the district",
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
    public function insertDistrict(DistrictRequest $request)
    {

        try {
            $District = $this->locationService->createDistrict($request);
            activity("District")
                ->causedBy(auth()->user())
                ->performedOn($District)
                ->log('District Created !');
            return DivisionResource::make($District)->additional([
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
     * @OA\Post(
     *      path="/admin/district/update",
     *      operationId="districtUpdate",
     *      tags={"GEOGRAPHIC-DISTRICT"},
     *      summary="update a district",
     *      description="update a district",
     *      security={{"bearer_token":{}}},
     *
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="enter inputs",
     *
     *            @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(
     *                   @OA\Property(
     *                      property="id",
     *                      description="id of the district",
     *                      type="integer",
     *                   ),
     *           @OA\Property(
     *                      property="division_id",
     *                      description="id of division",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_en",
     *                      description="english name of the district",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_bn",
     *                      description="bangla name of the district",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="code",
     *                      description="code of the Division",
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
    public function districtUpdate(DistrictUpdateRequest $request)
    {

        try {
            $district = $this->locationService->updateDistrict($request);
            activity("District")
                ->causedBy(auth()->user())
                ->performedOn($district)
                ->log('District Update !');
            return DistrictResource::make($district->load('parent'))->additional([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/admin/district/get/{division_id}",
     *      operationId="getAllDistrictByDivisionId",
     *      tags={"GEOGRAPHIC-DISTRICT"},
     *      summary=" get district by division",
     *      description="get district by division",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of division to return",
     *         in="path",
     *         name="division_id",
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
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

    public function getAllDistrictByDivisionId($division_id)
    {


        $district = Location::whereParentId($division_id)->whereType($this->district)->get();
        // return $district;
        return DistrictResource::collection($district)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }


    /**
     * @OA\Get(
     *      path="/admin/district/destroy/{id}",
     *      operationId="destroyDistrict",
     *      tags={"GEOGRAPHIC-DISTRICT"},
     *      summary=" destroy district",
     *      description="Returns district destroy by id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of district to return",
     *         in="path",
     *         name="id",
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
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
    public function destroyDistrict($id)
    {


        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:locations,id,deleted_at,NULL',
        ]);

        $validator->validated();

        $district = Location::where('parent_id',$id)->get();
        // dd($district->name_en);
        // print_r($district);
        if ($district->count() > 0) {
            // echo 'if';
            return $this->sendError('This record cannot be deleted because it is linked to other data.', [], 500);
        }else{
            // echo 'else';
             Location::where('id',$id)->delete();
        }

        // echo "<br>";
        // if ($district) {
        //     $district->delete();
        // }

        activity("District")
            ->causedBy(auth()->user())
            ->log('District Deleted!!');
        return $this->sendResponse($district, $this->deleteSuccessMessage, Response::HTTP_OK);
    }

    /* -------------------------------------------------------------------------- */
    /*                             TODO: Ciy Functions                            */
    /* -------------------------------------------------------------------------- */



    /**
     * @OA\Get(
     *     path="/admin/city/get",
     *      operationId="getAllCityPaginated",
     *      tags={"GEOGRAPHIC-CITY"},
     *      summary="get paginated city",
     *      description="get paginated city",
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
     *         description="number of Districts per page",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page number",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="sortBy",
     *         in="query",
     *         description="sortBy column name",
     *         @OA\Schema(type="text")
     *     ),
     *     @OA\Parameter(
     *         name="orderBy",
     *         in="query",
     *         description="asc or desc",
     *         @OA\Schema(type="text")
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

    public function getAllCityPaginated(Request $request)
    {
        // // Retrieve the query parameters
        // $searchText = $request->query('searchText');
        // $perPage = $request->query('perPage');
        // $page = $request->query('page');

        // $filterArrayNameEn = [];
        // $filterArrayNameBn = [];
        // $filterArrayCode = [];

        // if ($searchText) {
        //     $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
        //     $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
        //     $filterArrayCode[] = ['code', 'LIKE', '%' . $searchText . '%'];
        // }
        // $district = Location::query()
        //     ->where(function ($query) use ($filterArrayNameEn, $filterArrayNameBn, $filterArrayCode) {
        //         $query->where($filterArrayNameEn)
        //             ->orWhere($filterArrayNameBn)
        //             ->orWhere($filterArrayCode);
        //     })
        //     // ->whereType($this->city)
        //     ->whereIn('type', [$this->city, $this->thana])
        //     ->with('parent.parent', 'locationType')
        //     ->latest()
        //     ->paginate($perPage, ['*'], 'page');
        // return CityResource::collection($district)->additional([
        //     'success' => true,
        //     'message' => $this->fetchSuccessMessage,
        // ]);

        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage') ?? 10;
        $page = $request->query('page');
        $sortBy = $request->query('sortBy') ?? 'name_en';
        $orderBy = $request->query('orderBy') ?? 'asc';

        $filterArrayNameEn = [];
        $filterArrayNameBn = [];
        $filterArrayCode = [];

        $parent2filterArrayNameEn = [];
        $parent2filterArrayNameBn = [];
        $parent2filterArrayCode = [];

        $parent1filterArrayNameEn = [];
        $parent1filterArrayNameBn = [];
        $parent1filterArrayCode = [];

        if ($searchText) {
            $filterArrayNameEn[] = ['locations.name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayNameBn[] = ['locations.name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayCode[]   = ['locations.code', 'LIKE', '%' . $searchText . '%'];

            $parent2filterArrayNameEn[] = ['parent2.name_en', 'LIKE', '%' . $searchText . '%'];
            $parent2filterArrayNameBn[] = ['parent2.name_bn', 'LIKE', '%' . $searchText . '%'];
            $parent2filterArrayCode[]   = ['parent2.code', 'LIKE', '%' . $searchText . '%'];

            $parent1filterArrayNameEn[] = ['parent1.name_en', 'LIKE', '%' . $searchText . '%'];
            $parent1filterArrayNameBn[] = ['parent1.name_bn', 'LIKE', '%' . $searchText . '%'];
            $parent1filterArrayCode[]   = ['parent1.code', 'LIKE', '%' . $searchText . '%'];

            if ($searchText != null) {
                $page = 1;
            }
        }

        //
        // this is a 3 Level Search/Sorting
        // so this will start from name which is at level 3
        // then parent.name which is at level 2
        // then parent.parent.name which is at level 1
        //

        // Level 3
        if ($sortBy == 'name_en') {
            $sortBy = 'name_en';
        }
        // Level 2
        if ($sortBy == 'parent.name_en') {
            $sortBy = 'parent2.name_en';
        }
        // Level 1
        if ($sortBy == 'parent.parent.name_en') {
            $sortBy = 'parent1.name_en';
        }

        ///
        // parent4
        // parent3
        // parent2
        // parent1
        /// JOIN and Search in Nested 1 is Nested of 2 which means parent2.parent1

        $city = Location::query()
            ->join('locations as parent2', 'locations.parent_id', '=', 'parent2.id') // Join with the parent table
            ->join('locations as parent1', 'parent2.parent_id', '=', 'parent1.id') // Join with the grandparent table
            // ->leftJoin('locations as district', 'district.parent_id', '=', 'locations.id')
            // ->leftJoin('locations as city', 'city.parent_id', '=', 'locations.id')
            ->select(
                'locations.*',
                // 'parent.name_en as parent_name_en',
                // 'parent.name_bn as parent_name_bn',
                // 'parent.code as parent_code',
                // 'parent.type as parent_type',
                // 'parent.id as parent_id',
                // 'parent.parent_id as parent_parent_id',
                // 'parent.type as parent_type',
                // 'parent.location_type as parent_location_type'
                // 'district.name_en AS district_name'
            )

            //Works
            ->where(function ($query) use (
                $filterArrayNameEn,
                $filterArrayNameBn,
                $filterArrayCode,
                $parent2filterArrayNameEn,
                $parent2filterArrayNameBn,
                $parent2filterArrayCode,
                $parent1filterArrayNameEn,
                $parent1filterArrayNameBn,
                $parent1filterArrayCode
            ) {

                $query->where($filterArrayNameEn)
                    ->orWhere($filterArrayNameBn)
                    ->orWhere($filterArrayCode) // City Search

                    ->orWhereHas('parent', function ($query) use (
                        $parent2filterArrayNameEn,
                        $parent2filterArrayNameBn,
                        $parent2filterArrayCode,
                        $parent1filterArrayNameEn,
                        $parent1filterArrayNameBn,
                        $parent1filterArrayCode
                    ) {
                        $query->where($parent2filterArrayNameEn)
                            ->orWhere($parent2filterArrayNameBn)
                            ->orWhere($parent2filterArrayCode) // District Search

                            ->orWhereHas('parent', function ($query) use ($parent1filterArrayNameEn, $parent1filterArrayNameBn, $parent1filterArrayCode) {
                                $query->where($parent1filterArrayNameEn)
                                    ->orWhere($parent1filterArrayNameBn)
                                    ->orWhere($parent1filterArrayCode); // Division Search
                            });
                    });
            })
            //Works
            // ->where('locations.type', '=', $this->city)
            ->whereIn('locations.type', [$this->city, $this->thana])
            ->orderBy($sortBy, $orderBy)
            ->with('parent.parent', 'locationType')
            ->paginate($perPage, ['*'], 'page', $page);

        return $city;
        return CityResource::collection($city)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/admin/city/get/{district_id}/{location_type}",
     *      operationId="getAllCityByDistrictId",
     *      tags={"GEOGRAPHIC-CITY"},
     *      summary=" get city by district id",
     *      description="get city by district id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of district to return",
     *         in="path",
     *         name="district_id",
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="location type id for get city, eg: 3 for city, 2 for upazila, 1 for District Pouroshava",
     *         in="path",
     *         name="location_type",
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
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

    public function getAllCityByDistrictId($district_id, $location_type = 3)
    {


        $cities = Location::whereParentId($district_id)->whereType($this->city)->whereLocationType($location_type)->get();

        return DistrictResource::collection($cities)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }

    /**
     *
     * @OA\Post(
     *      path="/admin/city/insert",
     *      operationId="insertCity",
     *      tags={"GEOGRAPHIC-CITY"},
     *      summary="insert a city",
     *      description="insert a city",
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
     *                      property="name_en",
     *                      description="english name of the city",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_bn",
     *                      description="bangla name of the city",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="code",
     *                      description="code of the city",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="location_type",
     *                      description="location type of the city",
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
    public function insertCity(CityRequest $request)
    {

        try {
            $city = $this->locationService->createCity($request);
            // activity($request->location_type==3?$this->city:$this->districtPouroshava)
            activity($this->city)
                ->causedBy(auth()->user())
                ->performedOn($city)
                ->log($request->location_type == 3 ? $this->city : $this->districtPouroshava . ' Created !');
            return CityResource::make($city->load('parent.parent'))->additional([
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
     * @OA\Post(
     *      path="/admin/city/update",
     *      operationId="cityUpdate",
     *      tags={"GEOGRAPHIC-CITY"},
     *      summary="update a city",
     *      description="update a city",
     *      security={{"bearer_token":{}}},
     *
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="enter inputs",
     *
     *            @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(
     *                   @OA\Property(
     *                      property="id",
     *                      description="id of the city",
     *                      type="integer",
     *                   ),
     *           @OA\Property(
     *                      property="division_id",
     *                      description="id of division",
     *                      type="text",
     *                   ),
     *           @OA\Property(
     *                      property="district_id",
     *                      description="id of district",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_en",
     *                      description="english name of the city",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_bn",
     *                      description="bangla name of the city",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="code",
     *                      description="code of the city",
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
    public function cityUpdate(CityUpdateRequest $request)
    {

        try {
            $city = $this->locationService->updateCity($request);
            activity("City")
                ->causedBy(auth()->user())
                ->performedOn($city)
                ->log('City Update !');
            return CityResource::make($city->load('parent.parent'))->additional([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }


    /**
     * @OA\Get(
     *      path="/admin/city/destroy/{id}",
     *      operationId="destroyCity",
     *      tags={"GEOGRAPHIC-CITY"},
     *      summary=" destroy city",
     *      description="Returns city destroy by id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of city to return",
     *         in="path",
     *         name="id",
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
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
    public function destroyCity($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:locations,id,deleted_at,NULL',
        ]);

        $validator->validated();

        $city = Location::whereId($id)->whereType($this->city)->first();
        if ($city->children->count() > 0) {

            return $this->sendError('This record cannot be deleted because it is linked to other data.', [], 500);
        }
        if ($city) {
            $city->delete();
        }
        activity("City")
            ->causedBy(auth()->user())
            ->log('City Deleted!!');
        return $this->sendResponse($city, $this->deleteSuccessMessage, Response::HTTP_OK);
    }


    /* -------------------------------------------------------------------------- */
    /*                               Thana Functions                              */
    /* -------------------------------------------------------------------------- */


    /**
     * @OA\Get(
     *     path="/admin/thana/get",
     *      operationId="getAllThanaPaginated",
     *      tags={"GEOGRAPHIC-THANA"},
     *      summary="get paginated thana",
     *      description="get paginated thana",
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
     *         description="number of thana per page",
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

    public function getAllThanaPaginated(Request $request)
    {
        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage');
        $page = $request->query('page');

        $filterArrayNameEn = [];
        $filterArrayNameBn = [];
        $filterArrayCode = [];

        if ($searchText) {
            $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayCode[] = ['code', 'LIKE', '%' . $searchText . '%'];
        }
        $thana = Location::query()
            ->where(function ($query) use ($filterArrayNameEn, $filterArrayNameBn, $filterArrayCode) {
                $query->where($filterArrayNameEn)
                    ->orWhere($filterArrayNameBn)
                    ->orWhere($filterArrayCode);
            })
            ->whereType($this->thana)
            ->with('parent.parent.parent', 'locationType')
            ->latest()
            ->paginate($perPage, ['*'], 'page');
        // return $thana;
        return CityResource::collection($thana)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/admin/thana/get/{district_id}",
     *      operationId="getAllThanaByDistrictId",
     *      tags={"GEOGRAPHIC-THANA"},
     *      summary=" get thana by district id",
     *      description="get thana by district id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of district to return",
     *         in="path",
     *         name="district_id",
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
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

    public function getAllThanaByDistrictId($district_id)
    {


        $thanas = Location::whereParentId($district_id)->whereType($this->thana)->whereLocationType(2)->get();

        return DistrictResource::collection($thanas)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }
    /**
     * @OA\Get(
     *      path="/admin/thana/get/city/{city_id}",
     *      operationId="getAllThanaByCityId",
     *      tags={"GEOGRAPHIC-THANA"},
     *      summary=" get thana by city  id",
     *      description="get thana by city id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of city to return",
     *         in="path",
     *         name="city_id",
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
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

    public function getAllThanaByCityId($city_id)
    {


        $thanas = Location::whereParentId($city_id)->whereType($this->thana)->whereLocationType(3)->get();
        return DistrictResource::collection($thanas)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }

    /**
     *
     * @OA\Post(
     *      path="/admin/thana/insert",
     *      operationId="insertThana",
     *      tags={"GEOGRAPHIC-THANA"},
     *      summary="insert a thana",
     *      description="insert a thana",
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
     *                      property="city_corporation_id",
     *                      description="id of city corporation",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_en",
     *                      description="english name of the thana",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_bn",
     *                      description="bangla name of the thana",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="location_type",
     *                      description="id of the location type ",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="code",
     *                      description="code of the city",
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
    public function insertThana(ThanaRequest $request)
    {

        try {
            $thana = $this->locationService->createThana($request);
            activity("Thana")
                ->causedBy(auth()->user())
                ->performedOn($thana)
                ->log('Thana Created !');
            return CityResource::make($thana->load('parent.parent', 'locationType'))->additional([
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
     * @OA\Post(
     *      path="/admin/thana/update",
     *      operationId="thanaUpdate",
     *      tags={"GEOGRAPHIC-THANA"},
     *      summary="update a thana",
     *      description="update a thana",
     *      security={{"bearer_token":{}}},
     *
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="enter inputs",
     *
     *            @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(
     *                   @OA\Property(
     *                      property="id",
     *                      description="id of the thana",
     *                      type="integer",
     *                   ),
     *           @OA\Property(
     *                      property="division_id",
     *                      description="id of division",
     *                      type="text",
     *                   ),
     *           @OA\Property(
     *                      property="district_id",
     *                      description="id of district",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_en",
     *                      description="english name of the thana",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_bn",
     *                      description="bangla name of the thana",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="code",
     *                      description="code of the thana",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="location_type",
     *                      description="if fo the location type ",
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
    public function thanaUpdate(ThanaUpdateRequest $request)
    {

        try {
            $thana = $this->locationService->updateThana($request);
            activity("Thana")
                ->causedBy(auth()->user())
                ->performedOn($thana)
                ->log('Thana Update !');
            return CityResource::make($thana->load('parent.parent'))->additional([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/admin/thana/destroy/{id}",
     *      operationId="destroyThana",
     *      tags={"GEOGRAPHIC-THANA"},
     *      summary=" destroy thana",
     *      description="Returns thana destroy by id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of thana to return",
     *         in="path",
     *         name="id",
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
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
    public function destroyThana($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:locations,id,deleted_at,NULL',
        ]);

        $validator->validated();

        $thana = Location::whereId($id)->whereType($this->thana)->first();
        if ($thana->children->count() > 0) {

            return $this->sendError('This record cannot be deleted because it is linked to other data.', [], 500);
        }
        if ($thana) {
            $thana->delete();
        }
        activity("Thana")
            ->causedBy(auth()->user())
            ->log('Thana Deleted!!');
        return $this->sendResponse($thana, $this->deleteSuccessMessage, Response::HTTP_OK);
    }

    /* -------------------------------------------------------------------------- */
    /*                            TODO: UNION Functions                           */
    /* -------------------------------------------------------------------------- */


    /**
     * @OA\Get(
     *     path="/admin/union/get",
     *      operationId="getAllUnionPaginated",
     *      tags={"GEOGRAPHIC-UNION"},
     *      summary="get paginated union",
     *      description="get paginated union",
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
     *         description="number of Districts per page",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page number",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="sortBy",
     *         in="query",
     *         description="sortBy column name",
     *         @OA\Schema(type="text")
     *     ),
     *     @OA\Parameter(
     *         name="orderBy",
     *         in="query",
     *         description="asc or desc",
     *         @OA\Schema(type="text")
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

    public function getAllUnionPaginated(Request $request)
    {
        // // Retrieve the query parameters
        // $searchText = $request->query('searchText');
        // $perPage = $request->query('perPage');
        // $page = $request->query('page');

        // $filterArrayNameEn = [];
        // $filterArrayNameBn = [];
        // $filterArrayCode = [];

        // if ($searchText) {
        //     $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
        //     $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
        //     $filterArrayCode[] = ['code', 'LIKE', '%' . $searchText . '%'];
        // }
        // $union = Location::query()
        //     ->where(function ($query) use ($filterArrayNameEn, $filterArrayNameBn, $filterArrayCode) {
        //         $query->where($filterArrayNameEn)
        //             ->orWhere($filterArrayNameBn)
        //             ->orWhere($filterArrayCode);
        //     })
        //     // ->whereType($this->union)
        //     ->whereIn('type', [$this->pouro, $this->union])
        //     ->with('parent.parent.parent')
        //     ->latest()
        //     ->paginate($perPage, ['*'], 'page');
        // return UnionResource::collection($union)->additional([
        //     'success' => true,
        //     'message' => $this->fetchSuccessMessage,
        // ]);

        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage') ?? 10;
        $page = $request->query('page');
        $sortBy = $request->query('sortBy') ?? 'name_en';
        $orderBy = $request->query('orderBy') ?? 'asc';

        $filterArrayNameEn = [];
        $filterArrayNameBn = [];
        $filterArrayCode = [];

        $parent3filterArrayNameEn = [];
        $parent3filterArrayNameBn = [];
        $parent3filterArrayCode = [];

        $parent2filterArrayNameEn = [];
        $parent2filterArrayNameBn = [];
        $parent2filterArrayCode = [];

        $parent1filterArrayNameEn = [];
        $parent1filterArrayNameBn = [];
        $parent1filterArrayCode = [];

        if ($searchText) {

            /// Union/Thana/Pouro
            $filterArrayNameEn[] = ['locations.name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayNameBn[] = ['locations.name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayCode[]   = ['locations.code', 'LIKE', '%' . $searchText . '%'];

            /// Upazila/City/District Pouroshava
            $parent3filterArrayNameEn[] = ['parent3.name_en', 'LIKE', '%' . $searchText . '%'];
            $parent3filterArrayNameBn[] = ['parent3.name_bn', 'LIKE', '%' . $searchText . '%'];
            $parent3filterArrayCode[]   = ['parent3.code', 'LIKE', '%' . $searchText . '%'];
            /// District
            $parent2filterArrayNameEn[] = ['parent2.name_en', 'LIKE', '%' . $searchText . '%'];
            $parent2filterArrayNameBn[] = ['parent2.name_bn', 'LIKE', '%' . $searchText . '%'];
            $parent2filterArrayCode[]   = ['parent2.code', 'LIKE', '%' . $searchText . '%'];

            /// Division
            $parent1filterArrayNameEn[] = ['parent1.name_en', 'LIKE', '%' . $searchText . '%'];
            $parent1filterArrayNameBn[] = ['parent1.name_bn', 'LIKE', '%' . $searchText . '%'];
            $parent1filterArrayCode[]   = ['parent1.code', 'LIKE', '%' . $searchText . '%'];

            if ($searchText != null) {
                $page = 1;
            }
        }

        //
        // this is a 3 Level Search/Sorting
        // so this will start from name which is at level 3
        // then parent.name which is at level 2
        // then parent.parent.name which is at level 1
        //

        // Level 3
        if ($sortBy == 'name_en') {
            $sortBy = 'name_en';
        }
        // Level 2
        if ($sortBy == 'parent.name_en') {
            $sortBy = 'parent3.name_en';
        }
        // Level 2
        if ($sortBy == 'parent.parent.name_en') {
            $sortBy = 'parent2.name_en';
        }
        // Level 1
        if ($sortBy == 'parent.parent.parent.name_en') {
            $sortBy = 'parent1.name_en';
        }

        ///
        // parent4
        // parent3
        // parent2
        // parent1
        /// JOIN and Search in Nested 1 is Nested of 2 which means parent2.parent1

        $union = Location::query()
            ->join('locations as parent3', 'locations.parent_id', '=', 'parent3.id') // Join with the parent table
            ->join('locations as parent2', 'parent3.parent_id', '=', 'parent2.id') // Join with the parent table
            ->join('locations as parent1', 'parent2.parent_id', '=', 'parent1.id') // Join with the grandparent table
            // ->leftJoin('locations as district', 'district.parent_id', '=', 'locations.id')
            // ->leftJoin('locations as city', 'city.parent_id', '=', 'locations.id')
            ->select(
                'locations.*',
                // 'parent.name_en as parent_name_en',
                // 'parent.name_bn as parent_name_bn',
                // 'parent.code as parent_code',
                // 'parent.type as parent_type',
                // 'parent.id as parent_id',
                // 'parent.parent_id as parent_parent_id',
                // 'parent.type as parent_type',
                // 'parent.location_type as parent_location_type'
                // 'district.name_en AS district_name'
            )

            //Works
            ->where(function ($query) use (
                $filterArrayNameEn,
                $filterArrayNameBn,
                $filterArrayCode,
                $parent3filterArrayNameEn,
                $parent3filterArrayNameBn,
                $parent3filterArrayCode,
                $parent2filterArrayNameEn,
                $parent2filterArrayNameBn,
                $parent2filterArrayCode,
                $parent1filterArrayNameEn,
                $parent1filterArrayNameBn,
                $parent1filterArrayCode
            ) {

                $query->where($filterArrayNameEn)
                    ->orWhere($filterArrayNameBn)
                    ->orWhere($filterArrayCode) // Union Level Search

                    ->orWhereHas('parent', function ($query) use (
                        $parent3filterArrayNameEn,
                        $parent3filterArrayNameBn,
                        $parent3filterArrayCode,
                        $parent2filterArrayNameEn,
                        $parent2filterArrayNameBn,
                        $parent2filterArrayCode,
                        $parent1filterArrayNameEn,
                        $parent1filterArrayNameBn,
                        $parent1filterArrayCode
                    ) {
                        $query->where($parent3filterArrayNameEn)
                            ->orWhere($parent3filterArrayNameBn)
                            ->orWhere($parent3filterArrayCode) // City Level Search

                            ->orWhereHas('parent', function ($query) use (
                                $parent2filterArrayNameEn,
                                $parent2filterArrayNameBn,
                                $parent2filterArrayCode,
                                $parent1filterArrayNameEn,
                                $parent1filterArrayNameBn,
                                $parent1filterArrayCode
                            ) {
                                $query->where($parent2filterArrayNameEn)
                                    ->orWhere($parent2filterArrayNameBn)
                                    ->orWhere($parent2filterArrayCode) // District Level Search

                                    ->orWhereHas('parent', function ($query) use ($parent1filterArrayNameEn, $parent1filterArrayNameBn, $parent1filterArrayCode) {
                                        $query->where($parent1filterArrayNameEn)
                                            ->orWhere($parent1filterArrayNameBn)
                                            ->orWhere($parent1filterArrayCode); // Division Level Search
                                    });
                            });
                    });
            })

            //Works
            // ->where('locations.type', '=', $this->union)
            // ->orwhere('locations.type', '=', $this->thana)
            // thana1E
            // ->where('locations.location_type', '=', '2')
            // ->orWhere('locations.type', [$this->thana])
            // ->whereIn('locations.type', [$this->thana])
            ->whereIn('locations.type', [$this->pouro, $this->thana, $this->union])
            // ->whereType($this->union)
            // ->where('locations.type', [$this->thana])
            // ->get();
            // , $this->union, $this->thana
            // ->latest()
            ->orderBy($sortBy, $orderBy)
            ->with('parent.parent.parent', 'locationType')
            ->paginate($perPage, ['*'], 'page', $page);

        return $union;
        return UnionResource::collection($union)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/admin/union/get/{thana_id}",
     *      operationId="getAllUnionByThanaId",
     *      tags={"GEOGRAPHIC-UNION"},
     *      summary=" get union by thana id",
     *      description="get union by thana id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of union to return",
     *         in="path",
     *         name="thana_id",
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
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

    public function getAllUnionByThanaId($thana_id)
    {


        $unions = Location::whereParentId($thana_id)->whereType($this->union)->get();

        return DistrictResource::collection($unions)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/admin/union/pouro/get/{upazila_id}",
     *      operationId="getAllPouroByThanaId",
     *      tags={"GEOGRAPHIC-UNION"},
     *      summary=" get pouro by upazila id",
     *      description="get pouro by upazila id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of pouro to return",
     *         in="path",
     *         name="upazila_id",
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
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

    public function getAllPouroByThanaId($upazila_id)
    {


        $pouros = Location::whereParentId($upazila_id)->whereType($this->pouro)->get();

        return DistrictResource::collection($pouros)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }

    /**
     *
     * @OA\Post(
     *      path="/admin/union/insert",
     *      operationId="insertUnion",
     *      tags={"GEOGRAPHIC-UNION"},
     *      summary="insert a union",
     *      description="insert a union",
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
     *                      property="name_en",
     *                      description="english name of the union",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_bn",
     *                      description="bangla name of the union",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="code",
     *                      description="code of the union",
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
    public function insertUnion(UnionRequest $request)
    {

        try {
            $union = $this->locationService->createUnion($request);
            activity("Union")
                ->causedBy(auth()->user())
                ->performedOn($union)
                ->log('Union Created !');
            return UnionResource::make($union->load('parent.parent.parent'))->additional([
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
     * @OA\Post(
     *      path="/admin/union/update",
     *      operationId="unionUpdate",
     *      tags={"GEOGRAPHIC-UNION"},
     *      summary="update a union",
     *      description="update a union",
     *      security={{"bearer_token":{}}},
     *
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="enter inputs",
     *
     *            @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(
     *                   @OA\Property(
     *                      property="id",
     *                      description="id of the union",
     *                      type="integer",
     *                   ),
     *           @OA\Property(
     *                      property="division_id",
     *                      description="id of division",
     *                      type="text",
     *                   ),
     *           @OA\Property(
     *                      property="district_id",
     *                      description="id of district",
     *                      type="text",
     *                   ),
     *           @OA\Property(
     *                      property="thana_id",
     *                      description="id of thana",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_en",
     *                      description="english name of the union",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_bn",
     *                      description="bangla name of the union",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="code",
     *                      description="code of the union",
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
    public function unionUpdate(UnionUpdateRequest $request)
    {

        try {
            $union = $this->locationService->updateUnion($request);
            activity("Union")
                ->causedBy(auth()->user())
                ->performedOn($union)
                ->log('Union Update !');
            return UnionResource::make($union->load('parent.parent.parent'))->additional([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/admin/union/destroy/{id}",
     *      operationId="destroyUnion",
     *      tags={"GEOGRAPHIC-UNION"},
     *      summary=" destroy union",
     *      description="Returns union destroy by id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of union to return",
     *         in="path",
     *         name="id",
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
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
    public function destroyUnion($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:locations,id,deleted_at,NULL',
        ]);

        $validator->validated();

        $union = Location::whereId($id)->whereType($this->union)->first();
        if ($union->children->count() > 0) {

            return $this->sendError('This record cannot be deleted because it is linked to other data.', [], 500);
        }
        if ($union) {
            $union->delete();
        }
        activity("Union")
            ->causedBy(auth()->user())
            ->log('Union Deleted!!');
        return $this->sendResponse($union, $this->deleteSuccessMessage, Response::HTTP_OK);
    }

    /* -------------------------------------------------------------------------- */
    /*                            TODO: WARD Functions                           */
    /* -------------------------------------------------------------------------- */


    /**
     * @OA\Get(
     *     path="/admin/ward/get",
     *      operationId="getAllWardPaginated",
     *      tags={"GEOGRAPHIC-WARD"},
     *      summary="get paginated ward",
     *      description="get paginated ward",
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
     *         description="number of Districts per page",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page number",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="sortBy",
     *         in="query",
     *         description="sortBy column name",
     *         @OA\Schema(type="text")
     *     ),
     *     @OA\Parameter(
     *         name="orderBy",
     *         in="query",
     *         description="asc or desc",
     *         @OA\Schema(type="text")
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

    public function getAllWardPaginated(Request $request)
    {
        // // Retrieve the query parameters
        // $searchText = $request->query('searchText');
        // $perPage = $request->query('perPage');
        // $page = $request->query('page');

        // $filterArrayNameEn = [];
        // $filterArrayNameBn = [];
        // $filterArrayCode = [];

        // if ($searchText) {
        //     $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
        //     $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
        //     $filterArrayCode[] = ['code', 'LIKE', '%' . $searchText . '%'];
        // }
        // $ward = Location::query()
        //     ->where(function ($query) use ($filterArrayNameEn, $filterArrayNameBn, $filterArrayCode) {
        //         $query->where($filterArrayNameEn)
        //             ->orWhere($filterArrayNameBn)
        //             ->orWhere($filterArrayCode);
        //     })
        //     ->whereType($this->ward)
        //     ->with('parent.parent.parent.parent', 'locationType')
        //     ->latest()
        //     ->paginate($perPage, ['*'], 'page');
        // return WardResource::collection($ward)->additional([
        //     'success' => true,
        //     'message' => $this->fetchSuccessMessage,
        // ]);


        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage') ?? 10;
        $page = $request->query('page');
        $sortBy = $request->query('sortBy') ?? 'name_en';
        $orderBy = $request->query('orderBy') ?? 'asc';

        $filterArrayNameEn = [];
        $filterArrayNameBn = [];
        $filterArrayCode = [];

        $parent4filterArrayNameEn = [];
        $parent4filterArrayNameBn = [];
        $parent4filterArrayCode = [];

        $parent3filterArrayNameEn = [];
        $parent3filterArrayNameBn = [];
        $parent3filterArrayCode = [];

        $parent2filterArrayNameEn = [];
        $parent2filterArrayNameBn = [];
        $parent2filterArrayCode = [];

        $parent1filterArrayNameEn = [];
        $parent1filterArrayNameBn = [];
        $parent1filterArrayCode = [];

        if ($searchText) {

            /// Union/Thana/Pouro
            $filterArrayNameEn[] = ['locations.name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayNameBn[] = ['locations.name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayCode[]   = ['locations.code', 'LIKE', '%' . $searchText . '%'];

            /// Upazila/City/District Pouroshava
            $parent4filterArrayNameEn[] = ['parent4.name_en', 'LIKE', '%' . $searchText . '%'];
            $parent4filterArrayNameBn[] = ['parent4.name_bn', 'LIKE', '%' . $searchText . '%'];
            $parent4filterArrayCode[]   = ['parent4.code', 'LIKE', '%' . $searchText . '%'];

            /// Upazila/City/District Pouroshava
            $parent3filterArrayNameEn[] = ['parent3.name_en', 'LIKE', '%' . $searchText . '%'];
            $parent3filterArrayNameBn[] = ['parent3.name_bn', 'LIKE', '%' . $searchText . '%'];
            $parent3filterArrayCode[]   = ['parent3.code', 'LIKE', '%' . $searchText . '%'];

            /// District
            $parent2filterArrayNameEn[] = ['parent2.name_en', 'LIKE', '%' . $searchText . '%'];
            $parent2filterArrayNameBn[] = ['parent2.name_bn', 'LIKE', '%' . $searchText . '%'];
            $parent2filterArrayCode[]   = ['parent2.code', 'LIKE', '%' . $searchText . '%'];

            /// Division
            $parent1filterArrayNameEn[] = ['parent1.name_en', 'LIKE', '%' . $searchText . '%'];
            $parent1filterArrayNameBn[] = ['parent1.name_bn', 'LIKE', '%' . $searchText . '%'];
            $parent1filterArrayCode[]   = ['parent1.code', 'LIKE', '%' . $searchText . '%'];

            if ($searchText != null) {
                $page = 1;
            }
        }

        //
        // this is a 3 Level Search/Sorting
        // so this will start from name which is at level 3
        // then parent.name which is at level 2
        // then parent.parent.name which is at level 1
        //

        // Level 3
        if ($sortBy == 'name_en') {
            $sortBy = 'name_en';
        }
        // Level 2
        if ($sortBy == 'parent.name_en') {
            $sortBy = 'parent4.name_en';
        }
        // Level 2
        if ($sortBy == 'parent.parent.name_en') {
            $sortBy = 'parent3.name_en';
        }
        // Level 2
        if ($sortBy == 'parent.parent.parent.name_en') {
            $sortBy = 'parent2.name_en';
        }
        // Level 1
        if ($sortBy == 'parent.parent.parent.parent.name_en') {
            $sortBy = 'parent1.name_en';
        }

        ///
        // parent4
        // parent3
        // parent2
        // parent1
        /// JOIN and Search in Nested 1 is Nested of 2 which means parent2.parent1

        $ward = Location::query()
            ->leftJoin('locations as parent4', 'locations.parent_id', '=', 'parent4.id') // Join with the parent table
            ->leftJoin('locations as parent3', 'parent4.parent_id', '=', 'parent3.id') // Join with the parent table
            ->leftJoin('locations as parent2', 'parent3.parent_id', '=', 'parent2.id') // Join with the parent table
            ->leftJoin('locations as parent1', 'parent2.parent_id', '=', 'parent1.id') // Join with the grandparent table
            // ->leftJoin('locations as district', 'district.parent_id', '=', 'locations.id')
            // ->leftJoin('locations as city', 'city.parent_id', '=', 'locations.id')
            ->select(
                'locations.*',
                // 'parent.name_en as parent_name_en',
                // 'parent.name_bn as parent_name_bn',
                // 'parent.code as parent_code',
                // 'parent.type as parent_type',
                // 'parent.id as parent_id',
                // 'parent.parent_id as parent_parent_id',
                // 'parent.type as parent_type',
                // 'parent.location_type as parent_location_type'
                // 'district.name_en AS district_name'
            )

            //Works

            ->where(function ($query) use (
                $filterArrayNameEn,
                $filterArrayNameBn,
                $filterArrayCode,
                $parent4filterArrayNameEn,
                $parent4filterArrayNameBn,
                $parent4filterArrayCode,
                $parent3filterArrayNameEn,
                $parent3filterArrayNameBn,
                $parent3filterArrayCode,
                $parent2filterArrayNameEn,
                $parent2filterArrayNameBn,
                $parent2filterArrayCode,
                $parent1filterArrayNameEn,
                $parent1filterArrayNameBn,
                $parent1filterArrayCode
            ) {

                $query->where($filterArrayNameEn)
                    ->orWhere($filterArrayNameBn)
                    ->orWhere($filterArrayCode) // Union Level Search

                    ->orWhereHas('parent', function ($query) use (
                        $parent4filterArrayNameEn,
                        $parent4filterArrayNameBn,
                        $parent4filterArrayCode,
                        $parent3filterArrayNameEn,
                        $parent3filterArrayNameBn,
                        $parent3filterArrayCode,
                        $parent2filterArrayNameEn,
                        $parent2filterArrayNameBn,
                        $parent2filterArrayCode,
                        $parent1filterArrayNameEn,
                        $parent1filterArrayNameBn,
                        $parent1filterArrayCode
                    ) {

                        $query->where($parent4filterArrayNameEn)
                            ->orWhere($parent4filterArrayNameBn)
                            ->orWhere($parent4filterArrayCode) // City Level Search

                            ->orWhereHas('parent', function ($query) use (
                                $parent3filterArrayNameEn,
                                $parent3filterArrayNameBn,
                                $parent3filterArrayCode,
                                $parent2filterArrayNameEn,
                                $parent2filterArrayNameBn,
                                $parent2filterArrayCode,
                                $parent1filterArrayNameEn,
                                $parent1filterArrayNameBn,
                                $parent1filterArrayCode
                            ) {

                                $query->where($parent3filterArrayNameEn)
                                    ->orWhere($parent3filterArrayNameBn)
                                    ->orWhere($parent3filterArrayCode) // City Level Search

                                    ->orWhereHas('parent', function ($query) use (
                                        $parent2filterArrayNameEn,
                                        $parent2filterArrayNameBn,
                                        $parent2filterArrayCode,
                                        $parent1filterArrayNameEn,
                                        $parent1filterArrayNameBn,
                                        $parent1filterArrayCode
                                    ) {
                                        $query->where($parent2filterArrayNameEn)
                                            ->orWhere($parent2filterArrayNameBn)
                                            ->orWhere($parent2filterArrayCode) // District Level Search

                                            ->orWhereHas('parent', function ($query) use ($parent1filterArrayNameEn, $parent1filterArrayNameBn, $parent1filterArrayCode) {
                                                $query->where($parent1filterArrayNameEn)
                                                    ->orWhere($parent1filterArrayNameBn)
                                                    ->orWhere($parent1filterArrayCode); // Division Level Search
                                            });
                                    });
                            });
                    });
            })

            //Works
            ->where('locations.type', '=', $this->ward)
            ->orderBy($sortBy, $orderBy)
            ->with('parent.parent.parent.parent', 'locationType')
            ->paginate($perPage, ['*'], 'page', $page);

        return $ward;
        return UnionResource::collection($ward)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }


    /**
     * @OA\Get(
     *      path="/admin/ward/get/thana/{thana_id}",
     *      operationId="getAllWardByThanaId",
     *      tags={"GEOGRAPHIC-WARD"},
     *      summary=" get ward by thana id",
     *      description="get ward by thana id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of thana to return",
     *         in="path",
     *         name="thana_id",
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
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

    public function getAllWardByThanaId($thana_id)
    {


        $wards = Location::whereParentId($thana_id)->whereType($this->ward)->get();

        return DistrictResource::collection($wards)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/admin/ward/get/pouro/{pouro_id}",
     *      operationId="getAllWardByPouroId",
     *      tags={"GEOGRAPHIC-WARD"},
     *      summary=" get ward by pouro id",
     *      description="get ward by pouro id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of pouro to return",
     *         in="path",
     *         name="pouro_id",
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
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

    public function getAllWardByPouroId($pouro_id)
    {


        $wards = Location::whereParentId($pouro_id)->whereType($this->ward)->get();

        return DistrictResource::collection($wards)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }
    /**
     * @OA\Get(
     *      path="/admin/ward/get/{union_id}",
     *      operationId="getAllWardByUnionId",
     *      tags={"GEOGRAPHIC-WARD"},
     *      summary=" get ward by union id",
     *      description="get ward by union id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of union to return",
     *         in="path",
     *         name="union_id",
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
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

    public function getAllWardByUnionId($union_id)
    {


        $wards = Location::whereParentId($union_id)->whereType($this->ward)->get();

        return DistrictResource::collection($wards)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }

    /**
     *
     * @OA\Post(
     *      path="/admin/ward/insert",
     *      operationId="insertWard",
     *      tags={"GEOGRAPHIC-WARD"},
     *      summary="insert a ward",
     *      description="insert a ward",
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
     *                      property="location_type",
     *                      description="location type of the ward",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="thana_id",
     *                      description="id of thana",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="union_id",
     *                      description="id of union",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="city_id",
     *                      description="id of city",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="city_thana_id",
     *                      description="id of city corporation thana",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="district_pouro_id",
     *                      description="id of city",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_en",
     *                      description="english name of the ward",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_bn",
     *                      description="bangla name of the ward",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="code",
     *                      description="code of the ward",
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
    public function insertWard(WardRequest $request)
    {

        try {
            $ward = $this->locationService->createWard($request);
            activity("Ward")
                ->causedBy(auth()->user())
                ->performedOn($ward)
                ->log('Ward Created !');
            return WardResource::make($ward->load('parent.parent.parent.parent'))->additional([
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
     * @OA\Post(
     *      path="/admin/ward/update",
     *      operationId="wardUpdate",
     *      tags={"GEOGRAPHIC-WARD"},
     *      summary="update a ward",
     *      description="update a ward",
     *      security={{"bearer_token":{}}},
     *
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="enter inputs",
     *
     *            @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(
     *                   @OA\Property(
     *                      property="id",
     *                      description="id of the ward",
     *                      type="integer",
     *                   ),
     *           @OA\Property(
     *                      property="division_id",
     *                      description="id of division",
     *                      type="text",
     *                   ),
     *           @OA\Property(
     *                      property="district_id",
     *                      description="id of district",
     *                      type="text",
     *                   ),
     *           @OA\Property(
     *                      property="thana_id",
     *                      description="id of thana",
     *                      type="text",
     *                   ),
     *           @OA\Property(
     *                      property="union_id",
     *                      description="id of union",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_en",
     *                      description="english name of the ward",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="name_bn",
     *                      description="bangla name of the ward",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="code",
     *                      description="code of the ward",
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
    public function wardUpdate(WardUpdateRequest $request)
    {

        try {
            $ward = $this->locationService->updateWard($request);
            activity("Ward")
                ->causedBy(auth()->user())
                ->performedOn($ward)
                ->log('Ward Update !');
            return WardResource::make($ward->load('parent.parent.parent.parent'))->additional([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/admin/ward/destroy/{id}",
     *      operationId="destroyWard",
     *      tags={"GEOGRAPHIC-WARD"},
     *      summary="destroy ward",
     *      description="Returns ward destroy by id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of ward to return",
     *         in="path",
     *         name="id",
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
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
    public function destroyWard($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:locations,id,deleted_at,NULL',
        ]);

        $validator->validated();

        $ward = Location::whereId($id)->whereType($this->ward)->first();
        if ($ward->children->count() > 0) {

            return $this->sendError('This record cannot be deleted because it is linked to other data.', [], 500);
        }
        if ($ward) {
            $ward->delete();
        }
        activity("Ward")
            ->causedBy(auth()->user())
            ->log('Ward Deleted!!');
        return $this->sendResponse($ward, $this->deleteSuccessMessage, Response::HTTP_OK);
    }
}
