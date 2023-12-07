<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Validator;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\MessageTrait;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\Office\OfficeService;
use App\Http\Resources\Admin\Office\OfficeResource;
use App\Http\Requests\Admin\System\Office\OfficeRequest;
use App\Http\Requests\Admin\System\Office\OfficeUpdateRequest;
use App\Models\OfficeHasWard;

class OfficeController extends Controller
{
    use MessageTrait;
    private $OfficeService;

    public function __construct(OfficeService $OfficeService)
    {
        $this->OfficeService = $OfficeService;
    }
    /**
     * @OA\Get(
     *     path="/admin/office/get",
     *      operationId="getAllOfficePaginated",
     *      tags={"SYSTEM-OFFICE-MANAGEMENT"},
     *      summary="get paginated Offices",
     *      description="get paginated Offices",
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

    public function getAllOfficePaginated(Request $request)
    {
        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage');
        $page = $request->query('page');
        $sortBy = $request->query('sortBy') ?? 'name_en';
        $orderBy = $request->query('orderBy') ?? 'asc';

        $filterArrayNameEn = [];
        $filterArrayNameBn = [];
        $filterArrayComment = [];
        $filterArrayAddress = [];

        if ($searchText) {
            $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayComment[] = ['comment', 'LIKE', '%' . $searchText . '%'];
            $filterArrayAddress[] = ['office_address', 'LIKE', '%' . $searchText . '%'];

            if ($searchText != null) {
                $page = 1;
            }
        }
        $office = Office::query()
            ->where(function ($query) use ($filterArrayNameEn, $filterArrayNameBn, $filterArrayComment, $filterArrayAddress) {
                $query->where($filterArrayNameEn)
                    ->orWhere($filterArrayNameBn)
                    ->orWhere($filterArrayComment)
                    ->orWhere($filterArrayAddress);
            })
            ->with('assignLocation.parent.parent.parent', 'assignLocation.locationType', 'officeType', 'wards')
            // ->latest()
            // ->paginate($perPage, ['*'], 'page');
            ->orderBy($sortBy, $orderBy)
            ->paginate($perPage, ['*'], 'page', $page);

        return $office;
        return OfficeResource::collection($office)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }
    /**
     * @OA\Get(
     *     path="/admin/office/get-ward-under-office",
     *     operationId="getAllWardUnderOffice",
     *     tags={"SYSTEM-OFFICE-MANAGEMENT"},
     *     summary="get paginated Ward Offices",
     *     description="get paginated Ward under Offices",
     *     security={{"bearer_token":{}}},
     *
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="Office Id",
     *         @OA\Schema(type="integer")
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful Insert operation",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity"
     *     )
     * )
     */
    public function getAllWardUnderOffice(Request $request)
    {
        // Retrieve the query parameters
        // echo "jelp";
        // return;
        $id = $request->query('id');
        // $searchText = $request->query('searchText');
        // $perPage = $request->query('perPage');
        // $page = $request->query('page');
        // $sortBy = $request->query('sortBy') ?? 'name_en';
        // $orderBy = $request->query('orderBy') ?? 'asc';

        // $filterArrayNameEn=[];
        // $filterArrayNameBn=[];
        // $filterArrayComment=[];
        // $filterArrayAddress=[];

        // if ($searchText) {
        //     $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
        //     $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
        //     $filterArrayComment[] = ['comment', 'LIKE', '%' . $searchText . '%'];
        //     $filterArrayAddress[] = ['office_address', 'LIKE', '%' . $searchText . '%'];
        //     if ($searchText != null) {
        //         $page = 1;
        //     }
        // }


        $office = Office::query()
            ->whereId($id)
            ->with('wards.parent.parent.parent.parent.parent')
            ->get();

        // ->latest()
        // ->paginate($perPage, ['*'], 'page');
        // ->orderBy($sortBy, $orderBy)
        // ->paginate($perPage, ['*'], 'page', $page);

        return $office;
        return OfficeResource::collection($office)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }

    /**
     *
     * @OA\Post(
     *      path="/admin/office/insert",
     *      operationId="insertOffice",
     *      tags={"SYSTEM-OFFICE-MANAGEMENT"},
     *      summary="insert a office",
     *      description="insert a office",
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
     *                      description="insert Division Id",
     *                      type="integer",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="district_id",
     *                      description="insert District Id",
     *                      type="integer",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="thana_id",
     *                      description="insert Thana Id",
     *                      type="integer",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="city_corpo_id",
     *                      description="insert city corporation Id",
     *                      type="integer",
     *
     *                   ),
     *                  @OA\Property(
     *                      property="office_type",
     *                      description="insert office_type",
     *                      type="integer",
     *
     *                   ),
     *                 @OA\Property(
     *                      property="name_en",
     *                      description="insert name_en",
     *                      type="text",
     *
     *                   ),
     *                 @OA\Property(
     *                      property="name_bn",
     *                      description="insert name_en",
     *                      type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="office_address",
     *                      description="bangla name of office_address",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="comment",
     *                      description="comment",
     *                      type="text",
     *                   ),
     *                  @OA\Property(
     *                      property="status",
     *                      description="status",
     *                      type="tinyInteger",
     *                   ),
     * 
     *                  @OA\Property(
     *                      property="ward_under_office[0][office_id]",
     *                      description="insert Office id",
     *                      type="integer",
     *                   ),
     * 
     *                  @OA\Property(
     *                     property="ward_under_office[0][ward_id]",
     *                      description="insert ward id",
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
    public function insertOffice(OfficeRequest $request)
    {
        try {
            $office = $this->OfficeService->createOffice($request);
            activity("Office")
                ->causedBy(auth()->user())
                ->performedOn($office)
                ->log('Office Created !');
            return OfficeResource::make($office)->additional([
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
     *      path="/admin/office/update",
     *      operationId="officeUpdate",
     *      tags={"SYSTEM-OFFICE-MANAGEMENT"},
     *      summary="update a office",
     *      description="update a office",
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
     *                      description="id of the Office",
     *                      type="integer",
     *                   ),
     *                  @OA\Property(
     *                      property="division_id",
     *                      description="insert Division Id",
     *                      type="integer",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="district_id",
     *                      description="insert District Id",
     *                      type="integer",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="thana_id",
     *                      description="insert Thana Id",
     *                      type="integer",
     *
     *                   ),
     *                    @OA\Property(
     *                      property="city_corpo_id",
     *                      description="insert city corporation Id",
     *                      type="integer",
     *
     *                   ),
     *                  @OA\Property(
     *                      property="office_type",
     *                      description="insert office_type",
     *                      type="integer",
     *
     *                   ),
     *                 @OA\Property(
     *                      property="name_en",
     *                      description="insert name_en",
     *                      type="text",
     *
     *                   ),
     *                 @OA\Property(
     *                      property="name_bn",
     *                      description="insert name_en",
     *                      type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="office_address",
     *                      description="bangla name of office_address",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="comment",
     *                      description="comment",
     *                      type="text",
     *                   ),
     *                  @OA\Property(
     *                      property="status",
     *                      description="status",
     *                      type="tinyInteger",
     *                   ),
     *
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

    public function officeUpdate(OfficeUpdateRequest $request)
    {

        try {
            $office = $this->OfficeService->updateOffice($request);
            activity("Office")
                ->causedBy(auth()->user())
                ->performedOn($office)
                ->log('Office Updated !');
            return OfficeResource::make($office)->additional([
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
     *      path="/admin/office/get/{district_id}",
     *      operationId="getAllOfficeByDistrictId",
     *     tags={"SySTEM-OFFICE-MANAGEMENT"},
     *      summary=" get office by district",
     *      description="get office by district",
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

    public function getAllOfficeByDistrictId($district_id)
    {


        $office = Office::whereDistrictId($district_id)->get();

        return OfficeResource::collection($office)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/admin/office/destroy/{id}",
     *      operationId="destroyOffice",
     *      tags={"SYSTEM-OFFICE-MANAGEMENT"},
     *      summary=" destroy Office",
     *      description="Returns office destroy by id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of office to return",
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
    public function destroyOffice($id)
    {


        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:offices,id',
        ]);

        $validator->validated();

        $office = Office::whereId($id)->first();
        if ($office) {
            $office->delete();
        }
        activity("Office")
            ->causedBy(auth()->user())
            ->log('Office Deleted!!');
        return $this->sendResponse($office, $this->deleteSuccessMessage, Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/admin/office/destroy/ward-under-office",
     *      operationId="destroyWardUnderOffice",
     *      tags={"SYSTEM-OFFICE-MANAGEMENT"},
     *      summary=" destroy Office",
     *      description="Returns office destroy by id",
     *      security={{"bearer_token":{}}},
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
     *                      description="id of the Office",
     *                      type="integer",
     *                   ),
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
    public function destroyWardUnderOffice()
    {

        $id = request()->id;

        // $validator = Validator::make(['id' => $id], [
        //     'id' => 'required|exists:offices,id',
        // ]);

        $office = OfficeHasWard::where('id', $id)->delete();

        activity("Office")
            ->causedBy(auth()->user())
            ->log('Office Deleted!!');

        return $this->sendResponse($office, $this->deleteSuccessMessage, Response::HTTP_OK);
    }
}
