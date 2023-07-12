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
use App\Http\Requests\Admin\Geographic\Uinion\UnionRequest;
use App\Http\Requests\Admin\Geographic\Uinion\UnionUpdateRequest;
use App\Http\Requests\Admin\Geographic\WardRequest;
use App\Http\Requests\Admin\Geographic\WardUpdateRequest;
use App\Http\Resources\Admin\Geographic\CityResource;
use App\Http\Resources\Admin\Geographic\DistrictResource;
use App\Http\Resources\Admin\Geographic\DivisionResource;
use App\Http\Resources\Admin\Geographic\UnionResource;
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
    use MessageTrait,UserTrait,LocationTrait;
    private $locationService;

    public function __construct(LocationService $locationService) {
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

 public function getAllDivisionPaginated(Request $request){
        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage');
        $page = $request->query('page');

        $filterArrayNameEn=[];
        $filterArrayNameBn=[];
        $filterArrayCode=[];

        if ($searchText) {
            $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayCode[] = ['code', 'LIKE', '%' . $searchText . '%'];
        }
        $division = Location::query()
        ->where(function ($query) use ($filterArrayNameEn,$filterArrayNameBn,$filterArrayCode) {
            $query->where($filterArrayNameEn)
                  ->orWhere($filterArrayNameBn)
                  ->orWhere($filterArrayCode);
        })
        ->whereParentId(null)
        ->latest()
        ->paginate($perPage, ['*'], 'page');

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
    public function insertDivision(DivisionRequest $request){

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
    public function divisionUpdate(DivisionUpdateRequest $request){

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
        if($division){
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

 public function getAllDistrictPaginated(Request $request){
    // Retrieve the query parameters
    $searchText = $request->query('searchText');
    $perPage = $request->query('perPage');
    $page = $request->query('page');

    $filterArrayNameEn=[];
    $filterArrayNameBn=[];
    $filterArrayCode=[];

    if ($searchText) {
        $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
        $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
        $filterArrayCode[] = ['code', 'LIKE', '%' . $searchText . '%'];
    }
    $district = Location::query()
    ->where(function ($query) use ($filterArrayNameEn,$filterArrayNameBn,$filterArrayCode) {
        $query->where($filterArrayNameEn)
              ->orWhere($filterArrayNameBn)
              ->orWhere($filterArrayCode);
    })
    ->whereType($this->district)
    ->with('parent')
    ->latest()
    ->paginate($perPage, ['*'], 'page');
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
    public function insertDistrict(DistrictRequest $request){

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
    public function districtUpdate(DistrictUpdateRequest $request){

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

        $district = Location::whereId($id)->whereType($this->district)->first();
        if($district){
            $district->delete();
        }
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
    *         description="number of city per page",
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

 public function getAllCityPaginated(Request $request){
    // Retrieve the query parameters
    $searchText = $request->query('searchText');
    $perPage = $request->query('perPage');
    $page = $request->query('page');

    $filterArrayNameEn=[];
    $filterArrayNameBn=[];
    $filterArrayCode=[];

    if ($searchText) {
        $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
        $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
        $filterArrayCode[] = ['code', 'LIKE', '%' . $searchText . '%'];
    }
    $district = Location::query()
    ->where(function ($query) use ($filterArrayNameEn,$filterArrayNameBn,$filterArrayCode) {
        $query->where($filterArrayNameEn)
              ->orWhere($filterArrayNameBn)
              ->orWhere($filterArrayCode);
    })
    ->whereType($this->city)
    ->with('parent.parent')
    ->latest()
    ->paginate($perPage, ['*'], 'page');
    return CityResource::collection($district)->additional([
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
    public function insertCity(CityRequest $request){

        try {
            $city = $this->locationService->createCity($request);
            activity("City")
            ->causedBy(auth()->user())
            ->performedOn($city)
            ->log('City Created !');
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
    public function cityUpdate(CityUpdateRequest $request){

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
        if($city){
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

 public function getAllThanaPaginated(Request $request){
    // Retrieve the query parameters
    $searchText = $request->query('searchText');
    $perPage = $request->query('perPage');
    $page = $request->query('page');

    $filterArrayNameEn=[];
    $filterArrayNameBn=[];
    $filterArrayCode=[];

    if ($searchText) {
        $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
        $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
        $filterArrayCode[] = ['code', 'LIKE', '%' . $searchText . '%'];
    }
    $thana = Location::query()
    ->where(function ($query) use ($filterArrayNameEn,$filterArrayNameBn,$filterArrayCode) {
        $query->where($filterArrayNameEn)
              ->orWhere($filterArrayNameBn)
              ->orWhere($filterArrayCode);
    })
    ->whereType($this->thana)
    ->with('parent.parent')
    ->latest()
    ->paginate($perPage, ['*'], 'page');
    return CityResource::collection($thana)->additional([
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
    public function insertThana(ThanaRequest $request){

        try {
            $thana = $this->locationService->createThana($request);
            activity("Thana")
            ->causedBy(auth()->user())
            ->performedOn($thana)
            ->log('Thana Created !');
            return CityResource::make($thana->load('parent.parent'))->additional([
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
    public function thanaUpdate(CityUpdateRequest $request){

        try {
            $thana = $this->locationService->updateCity($request);
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
        if($thana){
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
    *         description="number of union per page",
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

 public function getAllUnionPaginated(Request $request){
    // Retrieve the query parameters
    $searchText = $request->query('searchText');
    $perPage = $request->query('perPage');
    $page = $request->query('page');

    $filterArrayNameEn=[];
    $filterArrayNameBn=[];
    $filterArrayCode=[];

    if ($searchText) {
        $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
        $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
        $filterArrayCode[] = ['code', 'LIKE', '%' . $searchText . '%'];
    }
    $union = Location::query()
    ->where(function ($query) use ($filterArrayNameEn,$filterArrayNameBn,$filterArrayCode) {
        $query->where($filterArrayNameEn)
              ->orWhere($filterArrayNameBn)
              ->orWhere($filterArrayCode);
    })
    ->whereType($this->union)
    ->with('parent.parent.parent')
    ->latest()
    ->paginate($perPage, ['*'], 'page');
    return UnionResource::collection($union)->additional([
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
    public function insertUnion(UnionRequest $request){

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
    public function unionUpdate(UnionUpdateRequest $request){

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
        if($union){
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
    *         description="number of ward per page",
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

 public function getAllWardPaginated(Request $request){
    // Retrieve the query parameters
    $searchText = $request->query('searchText');
    $perPage = $request->query('perPage');
    $page = $request->query('page');

    $filterArrayNameEn=[];
    $filterArrayNameBn=[];
    $filterArrayCode=[];

    if ($searchText) {
        $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
        $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
        $filterArrayCode[] = ['code', 'LIKE', '%' . $searchText . '%'];
    }
    $ward = Location::query()
    ->where(function ($query) use ($filterArrayNameEn,$filterArrayNameBn,$filterArrayCode) {
        $query->where($filterArrayNameEn)
              ->orWhere($filterArrayNameBn)
              ->orWhere($filterArrayCode);
    })
    ->whereType($this->ward)
    ->with('parent.parent.parent.parent')
    ->latest()
    ->paginate($perPage, ['*'], 'page');
    return WardResource::collection($ward)->additional([
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
    public function insertWard(WardRequest $request){

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
    public function wardUpdate(WardUpdateRequest $request){

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
        if($ward){
            $ward->delete();
        }
        activity("Ward")
        ->causedBy(auth()->user())
        ->log('Ward Deleted!!');
         return $this->sendResponse($ward, $this->deleteSuccessMessage, Response::HTTP_OK);
    }
}
