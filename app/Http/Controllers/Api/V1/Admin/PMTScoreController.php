<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Resources\Admin\PMTScore\PMTScoreResource;
use App\Http\Services\Admin\PMTScore\PMTScoreService;
use App\Models\PMTScore;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PMTScore\PMTScoreRequest;

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
use App\Http\Requests\Admin\PMTScore\DistrictFixedEffectRequest;
use App\Models\Location;

class PMTScoreController extends Controller
{
    use MessageTrait;
    private $PMTScoreService;

    public function __construct(PMTScoreService  $PMTScoreService)
    {
        $this->PMTScoreService = $PMTScoreService;
    }

    /**
     * @OA\Get(
     *     path="/admin/poverty/get",
     *      operationId="getAllPMTScorePaginated",
     *      tags={"PMT-Score"},
     *      summary="get paginated PMTScores",
     *      description="get paginated PMTScores",
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

    public function getAllPMTScorePaginated(Request $request)
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
        $office = PMTScore::select(
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
            ->where('default','0') // Cut Off
            ->with('assign_location.parent.parent.parent', 'assign_location.locationType')
            ->latest()
            ->paginate($perPage, ['*'], 'page');

        return PMTScoreResource::collection($office)->additional([
            'success' => true,
            // 'message' => $this->fetchSuccessMessage,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/admin/poverty/get/district-fixed-effect",
     *      operationId="getAllDistrictFixedEffectPaginated",
     *      tags={"PMT-Score"},
     *      summary="get paginated PMTScores",
     *      description="get paginated PMTScores",
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

    public function getAllDistrictFixedEffectPaginated(Request $request)
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
        $office = PMTScore::select(
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
            ->where('default','1') // Cut Off
            ->with('assign_location.parent.parent.parent', 'assign_location.locationType')
            ->latest()
            ->paginate($perPage, ['*'], 'page');

        return PMTScoreResource::collection($office)->additional([
            'success' => true,
            // 'message' => $this->fetchSuccessMessage,
        ]);
    }

    /**
     *
     * @OA\Post(
     *      path="/admin/poverty/poverty-cut-off/filter",
     *      operationId="filterDivisionCutOff",
     *      tags={"PMT-Score"},
     *      summary="filter a povertyPMTScore",
     *      description="filter a povertyPMTScore",
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
                $this->insertPMTScore($financial_year_id, $type);
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
        $office = PMTScore::query()
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

        return PMTScoreResource::collection($office)->additional([
            'success' => true,
            // 'message' => $this->fetchSuccessMessage,
        ]);
    }

    private function check_if_exists($financial_year_id, $type)
    {
        $data = PMTScore::get()
            ->where('financial_year_id', $financial_year_id)
            ->where('type', $type);

        if (count($data) > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function insertPMTScore($financial_year_id, $type)
    {
        // THIS FUNCTION POVERTY CUT OFF INSERT 
        // IF NOT EXISTED FOR A SPECIFIC FINANCIAL YEAR

        if ($type == 0) {

            // ALL OVER BANGLADESH CUTTOFF
            $poverty_score_cut_offs = new PMTScore;
            $poverty_score_cut_offs->type         = $type;
            $poverty_score_cut_offs->financial_year_id  = $financial_year_id;
            $poverty_score_cut_offs->score        = 0;
            $poverty_score_cut_offs->default      = 0;
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

                $poverty_score_cut_offs = new PMTScore;
                $poverty_score_cut_offs->type         = $type;
                $poverty_score_cut_offs->location_id  = $value['id'];
                $poverty_score_cut_offs->financial_year_id  = $financial_year_id;
                $poverty_score_cut_offs->score        = 0;
                $poverty_score_cut_offs->default      = 0;
                $poverty_score_cut_offs->save();
            }
        }
    }

    /**
     *
     * @OA\Post(
     *      path="/admin/poverty/poverty-cut-off/update",
     *      operationId="updatePMTScore",
     *      tags={"PMT-Score"},
     *      summary="update a povertyPMTScore",
     *      description="update a povertyPMTScore",
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

    public function updatePMTScore(PMTScoreRequest $request)
    {

        try {
            $PMTScore = $this->PMTScoreService->updatePMTScore($request);
            activity("DivisionCutOff")
                ->causedBy(auth()->user())
                ->performedOn($PMTScore)
                ->log('PMTScore Created !');
            return PMTScoreResource::make($PMTScore)->additional([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }
    /**
     *
     * @OA\Post(
     *      path="/admin/poverty/district-fixed-effect/update",
     *      operationId="updateDistrictFixedEffect",
     *      tags={"PMT-Score"},
     *      summary="update a povertyDistrictFixedEffect",
     *      description="update a povertyDistrictFixedEffect",
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
     *                      property="score",
     *                      description="score",
     *                      type="float",
     *                   ),
     *                    @OA\Property(
     *                      property="default",
     *                      description="default=1 for District Fixed Effect",
     *                      type="string",
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

    public function updateDistrictFixedEffect(DistrictFixedEffectRequest $request)
    {

        try {
            $PMTScore = $this->PMTScoreService->updatePMTScore($request);
            activity("DivisionCutOff")
                ->causedBy(auth()->user())
                ->performedOn($PMTScore)
                ->log('PMTScore Created !');
            return PMTScoreResource::make($PMTScore)->additional([
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
     *      path="/admin/poverty/poverty-cut-off/insert",
     *      operationId="insertDivisionCutOff",
     *      tags={"PMT-Score"},
     *      summary="insert a povertyPMTScore",
     *      description="insert a povertyPMTScore",
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

    public function insertDivisionCutOff(PMTScoreRequest $request)
    {

        try {
            $PMTScore = $this->PMTScoreService->createPMTScore($request);
            activity("DivisionCutOff")
                ->causedBy(auth()->user())
                ->performedOn($PMTScore)
                ->log('PMTScore Created !');
            return PMTScoreResource::make($PMTScore)->additional([
                'success' => true,
                'message' => $this->insertSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }
}
