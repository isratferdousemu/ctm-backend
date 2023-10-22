<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Resources\Admin\PovertyScoreCutOff\PovertyScoreCutOffResource;
use App\Http\Services\Admin\PovertyScoreCutOff\PovertyScoreCutOffService;
use App\Models\PovertyScoreCutOff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PovertyScoreCutOff\PovertyScoreCutOffRequest;

use Validator;
use App\Models\Lookup;
use Illuminate\Http\Response;
use App\Http\Traits\UserTrait;
use App\Http\Traits\LookupTrait;
use App\Http\Traits\MessageTrait;
use App\Http\Requests\Admin\Lookup\LookupRequest;
use App\Http\Services\Admin\Lookup\LookupService;
use App\Http\Resources\Admin\Lookup\LookupResource;
use App\Http\Requests\Admin\Lookup\LookupUpdateRequest;
use App\Models\Location;

class PovertyScoreCutOffController extends Controller
{
    use MessageTrait;
    private $PovertyScoreCutOffService;

    public function __construct(PovertyScoreCutOffService  $PovertyScoreCutOffService)
    {
        $this->PovertyScoreCutOffService = $PovertyScoreCutOffService;
    }

    /**
     * @OA\Get(
     *     path="/admin/poverty/get",
     *      operationId="getAllPovertyScoreCutOffPaginated",
     *      tags={"Poverty-Score-Management"},
     *      summary="get paginated PovertyScoreCutOffs",
     *      description="get paginated PovertyScoreCutOffs",
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

    public function getAllPovertyScoreCutOffPaginated(Request $request)
    {
        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage');
        $page = $request->query('page');

        $filterArrayNameEn = [];
        // $filterArrayNameBn = [];
        // $filterArrayComment = [];
        // $filterArrayAddress = [];

        if ($searchText) {
            $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
            // $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
            // $filterArrayComment[] = ['comment', 'LIKE', '%' . $searchText . '%'];
        }
        // $menu = Menu::select(
        //     'menus.*',
        //     'permissions.page_url as link'
        // )
        // ->leftJoin('permissions', function ($join) {
        //     $join->on('menus.page_link_id', '=', 'permissions.id');
        // });
        $office = PovertyScoreCutOff::select(
            'poverty_score_cut_offs.*',
            'locations.name_en',
        )
            ->leftJoin('locations', function ($join) {
                $join->on('poverty_score_cut_offs.location_id', '=', 'locations.id');
            })
            ->where(function ($query) use ($filterArrayNameEn) {
                $query->where($filterArrayNameEn)
                    // ->orWhere($filterArrayNameBn)
                    // ->orWhere($filterArrayComment)
                    // ->orWhere($filterArrayAddress)
                ;
            })
            ->with('assign_location.parent.parent.parent', 'assign_location.locationType')
            ->latest()
            ->paginate($perPage, ['*'], 'page');

        return PovertyScoreCutOffResource::collection($office)->additional([
            'success' => true,
            // 'message' => $this->fetchSuccessMessage,
        ]);
    }

    /**
     *
     * @OA\Post(
     *      path="/admin/poverty/division-cut-off/filter",
     *      operationId="filterDivisionCutOff",
     *      tags={"Poverty-Score-Management"},
     *      summary="filter a povertyPovertyScoreCutOff",
     *      description="filter a povertyPovertyScoreCutOff",
     *      security={{"bearer_token":{}}},
     *
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="enter inputs",
     *            @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(

     *                    @OA\Property(
     *                      property="financial_year_id",
     *                      description="filter type",
     *                      type="integer",
     *                   ),
     *                    @OA\Property(
     *                      property="type",
     *                      description="filter type",
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

    public function getFiltered(Request $request)
    {
        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage');
        $page = $request->query('page');

        $financial_year_id = null;
        $type = null;
        if ($request->has('financial_year_id')) {
            $financial_year_id = $request->financial_year_id;
        }
        if ($request->has('type')) {
            $type = $request->type;
        }

        if ($request->has('financial_year_id') && $request->has('type')) {
            if (!$this->check_if_exists($financial_year_id, $type)) {
                // entry all division/district values with that financial ID and load the table table to be editable
                $this->insertPovertyScoreCutOff($financial_year_id, $type);
            }
        }

        $filterArrayNameEn = [];
        $filterArrayNameBn = [];
        $filterArrayComment = [];
        $filterArrayAddress = [];

        if ($searchText) {
            $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayComment[] = ['comment', 'LIKE', '%' . $searchText . '%'];
        }
        $office = PovertyScoreCutOff::query()
            ->where(function ($query) use ($filterArrayNameEn, $financial_year_id, $type) {
                $query->where($filterArrayNameEn)
                    ->where('financial_year_id', $financial_year_id)
                    ->where('type', $type)
                    // ->orWhere($filterArrayNameBn)
                    // ->orWhere($filterArrayComment)
                    // ->orWhere($filterArrayAddress)
                ;
            })
            ->with('assign_location.parent.parent.parent', 'assign_location.locationType')
            ->latest()
            ->paginate($perPage, ['*'], 'page');

        return PovertyScoreCutOffResource::collection($office)->additional([
            'success' => true,
            // 'message' => $this->fetchSuccessMessage,
        ]);
    }

    private function check_if_exists($financial_year_id, $type)
    {
        $data = PovertyScoreCutOff::get()
            ->where('financial_year_id', $financial_year_id)
            ->where('type', $type);

        if (count($data) > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function insertPovertyScoreCutOff($financial_year_id, $type)
    {
        // THIS FUNCTION POVERTY CUT OFF INSERT 
        // IF NOT EXISTED FOR A SPECIFIC FINANCIAL YEAR

        if ($type == 0) {

            // ALL OVER BANGLADESH CUTTOFF
            $poverty_score_cut_offs = new PovertyScoreCutOff;
            $poverty_score_cut_offs->type         = $type;
            $poverty_score_cut_offs->financial_year_id  = $financial_year_id;
            $poverty_score_cut_offs->score        = 0;
            $poverty_score_cut_offs->save();
            // END ALL OVER BANGLADESH CUTTOFF

        } else {
            if ($type == 1) {
                $locations = Location::get()->where('type', 'division'); // DIVISION CUTTOFF
            }
            if ($type == 2) {
                $locations = Location::get()->where('type', 'district'); //DISTRICT CUTTOFF
            }

            foreach ($locations as $value) {

                $poverty_score_cut_offs = new PovertyScoreCutOff;
                $poverty_score_cut_offs->type         = $type;
                $poverty_score_cut_offs->location_id  = $value['id'];
                $poverty_score_cut_offs->financial_year_id  = $financial_year_id;
                $poverty_score_cut_offs->score        = 0;
                $poverty_score_cut_offs->save();
            }
        }
    }

    /**
     *
     * @OA\Post(
     *      path="/admin/poverty/division-cut-off/update",
     *      operationId="updateDivisionCutOff",
     *      tags={"Poverty-Score-Management"},
     *      summary="update a povertyPovertyScoreCutOff",
     *      description="update a povertyPovertyScoreCutOff",
     *      security={{"bearer_token":{}}},
     *
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="enter inputs",
     *            @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(

     *                    @OA\Property(
     *                      property="id",
     *                      description="id",
     *                      type="integer",
     *                   ),
     *                    @OA\Property(
     *                      property="type",
     *                      description="update type",
     *                      type="integer",
     *                   ),
     *                    @OA\Property(
     *                      property="division_id",
     *                      description="update division_id",
     *                      type="integer",
     *                   ),
     *                    @OA\Property(
     *                      property="location_id",
     *                      description="update location_id",
     *                      type="integer",
     *                   ),
     *                    @OA\Property(
     *                      property="score",
     *                      description="score",
     *                      type="float",
     *
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

    public function updateDivisionCutOff(PovertyScoreCutOffRequest $request)
    {

        try {
            $PovertyScoreCutOff = $this->PovertyScoreCutOffService->updatePovertyScoreCutOff($request);
            activity("DivisionCutOff")
                ->causedBy(auth()->user())
                ->performedOn($PovertyScoreCutOff)
                ->log('PovertyScoreCutOff Created !');
            return PovertyScoreCutOffResource::make($PovertyScoreCutOff)->additional([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }


    // public function officeUpdate(OfficeUpdateRequest $request){

    //     try {
    //         $office = $this->OfficeService->updateOffice($request);
    //         activity("Office")
    //         ->causedBy(auth()->user())
    //         ->performedOn($office)
    //         ->log('Office Updated !');
    //         return OfficeResource::make($office)->additional([
    //             'success' => true,
    //             'message' => $this->updateSuccessMessage,
    //         ]);
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //         return $this->sendError($th->getMessage(), [], 500);
    //     }
    // }
    /**
     *
     * @OA\Post(
     *      path="/admin/poverty/division-cut-off/insert",
     *      operationId="insertDivisionCutOff",
     *      tags={"Poverty-Score-Management"},
     *      summary="insert a povertyPovertyScoreCutOff",
     *      description="insert a povertyPovertyScoreCutOff",
     *      security={{"bearer_token":{}}},
     *
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="enter inputs",
     *            @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(

     *                    @OA\Property(
     *                      property="type",
     *                      description="insert type",
     *                      type="integer",
     *                   ),
     *                    @OA\Property(
     *                      property="division_id",
     *                      description="insert division_id",
     *                      type="integer",
     *                   ),
     *                    @OA\Property(
     *                      property="location_id",
     *                      description="insert location_id",
     *                      type="integer",
     *                   ),
     *                    @OA\Property(
     *                      property="score",
     *                      description="score",
     *                      type="float",
     *
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

    public function insertDivisionCutOff(PovertyScoreCutOffRequest $request)
    {

        try {
            $PovertyScoreCutOff = $this->PovertyScoreCutOffService->createPovertyScoreCutOff($request);
            activity("DivisionCutOff")
                ->causedBy(auth()->user())
                ->performedOn($PovertyScoreCutOff)
                ->log('PovertyScoreCutOff Created !');
            return PovertyScoreCutOffResource::make($PovertyScoreCutOff)->additional([
                'success' => true,
                'message' => $this->insertSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }
}
