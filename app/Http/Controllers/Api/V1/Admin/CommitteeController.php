<?php

namespace App\Http\Controllers\Api\V1\Admin;
use Validator;
use App\Models\Committee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\MessageTrait;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\Beneficiary\BeneficiaryService;
use App\Http\Requests\Admin\Beneficiary\CommitteeUpdateRequest;
use App\Http\Requests\Admin\Beneficiary\Committee\CommitteeRequest;
use App\Http\Resources\Admin\Beneficiary\Committee\CommitteeResource;

class CommitteeController extends Controller
{
    use MessageTrait;
    private $beneficiaryService;

    public function __construct(BeneficiaryService $beneficiaryService) {
        $this->beneficiaryService = $beneficiaryService;
    }

  /**
    * @OA\Get(
    *     path="/admin/committee/get",
    *      operationId="getAllCommitteePaginated",
    *       tags={"BENEFICIARY-COMMITTEE MANAGEMENT"},
    *      summary="get paginated Commitees with members",
    *      description="get paginated Commitees",
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
    *         description="number of committee per page",
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

    public function getAllCommitteePaginated(Request $request){
        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage');
        $page = $request->query('page');

        $filterArrayCode=[];
        $filterArrayName=[];
        $filterArrayDetails=[];
        $filterArrayMemberName=[];



        if ($searchText) {
            $filterArrayCode[] = ['code', 'LIKE', '%' . $searchText . '%'];
            $filterArrayName[] = ['name', 'LIKE', '%' . $searchText . '%'];
            $filterArrayDetails[] = ['details', 'LIKE', '%' . $searchText . '%'];




        }
        $committee = Committee::query()
        ->where(function ($query) use ($filterArrayCode,$filterArrayName,$filterArrayDetails) {
            $query->where($filterArrayCode)
                  ->orWhere($filterArrayName)
                  ->orWhere($filterArrayDetails);



        })
        ->with('program','division','district','office','members')
        ->latest()
        ->paginate($perPage, ['*'], 'page');

        return CommitteeResource::collection($committee)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }

 /**
     *
     * @OA\Post(
     *      path="/admin/committee/insert",
     *      operationId="insertCommittee",
     *      tags={"BENEFICIARY-COMMITTEE MANAGEMENT"},
     *      summary="insert a Committee",
     *      description="insert a Committee",
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

     *                      @OA\Property(
     *                      property="code",
     *                      description="insert code  of Committee",
     *                      type="text",
     *
     *                   ),
     *                    @OA\Property(
     *                      property="name",
     *                      description="insert Name  of Committee",
     *                      type="text",
     *
     *                   ),
     *
     *
     *                   @OA\Property(
     *                      property="program_id",
     *                      description="insert Program Id",
     *                      type="integer",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="details",
     *                      description="insert Details ",
     *                      type="integer",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="division_id",
     *                      description="insert division Id",
     *                      type="integer",
     *
     *                   ),
     *                  @OA\Property(
     *                      property="district_id",
     *                      description="insert district Id",
     *                      type="integer",
     *
     *                   ),
     *                  @OA\Property(
     *                      property="office_id",
     *                      description="insert office_id",
     *                      type="integer",
     *
     *                   ),
     *                 @OA\Property(
     *                      property="location_id",
     *                      description="insert location_id",
     *                      type="integer",
     *
     *                   ),
     *
     *                  @OA\Property(
     *                      property="members[0]['member_name]",
     *                      description="insert memeber_name ",
     *                      type="text",
     *
     *                   ),
     *                 @OA\Property(
     *                      property="members[0]['designation]",
     *                      description="insert designation",
     *                      type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="members[0]['address]",
     *                      description="insert address",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="members[0]['email]",
     *                      description="email",
     *                      type="text",
     *                   ),
     *                  @OA\Property(
     *                      property="members[0]['phone]",
     *                      description="phone",
     *                      type="integer",
     *                   ),
     *
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

    public function insertCommittee(CommitteeRequest $request){
        // return $request->all();
        try {
            $committee = $this->beneficiaryService->createCommittee($request);
            activity("Committee")
            ->causedBy(auth()->user())
            ->performedOn($committee)
            ->log('Committee Created !');
            return CommitteeResource::make($committee)->additional([
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
     *      path="/admin/committee/destroy/{id}",
     *      operationId="destroyCommittee",
     *       tags={"BENEFICIARY-COMMITTEE MANAGEMENT"},
     *      summary=" destroy Committee",
     *      description="Returns Committee destroy by id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of Committee to return",
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
    public function destroyCommittee($id)
    {


        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:committees,id',
        ]);

        $validator->validated();

        $committee = Committee::whereId($id)->first();
        if($committee){
            $committee->delete();
        }
        activity("Committee")
        ->causedBy(auth()->user())
        ->log('Committee Deleted!!');
         return $this->sendResponse($committee, $this->deleteSuccessMessage, Response::HTTP_OK);
    }
         /**
     * @OA\Get(
     *      path="/admin/committee/{id}",
     *      operationId="editCommittee",
     *     tags={"BENEFICIARY-COMMITTEE MANAGEMENT"},
     *      summary=" get committee by id",
     *      description="get committee by id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of committee to return",
     *         in="path",
     *         name="id",
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

 public function editCommittee($id){



    $committee = Committee::whereId($id)->with('program','division','district','office','members')->first();

    return CommitteeResource::make($committee)->additional([
        'success' => true,
        'message' => $this->fetchSuccessMessage,
    ]);
}

 /**
     *
     * @OA\Post(
     *      path="/admin/committee/update",
     *      operationId="committeeUpdate",
     *     tags={"BENEFICIARY-COMMITTEE MANAGEMENT"},
     *      summary="update a committee",
     *      description="updatet a committee",
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
     *                      description="id of the Committee",
     *                      type="integer",
     *                   ),
     *                    @OA\Property(
     *                      property="code",
     *                      description="Insert Code",
     *                      type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                    property="name",
     *                    description="Insert Name",
     *                    type="text",
     *
     *                   ),
     *                    @OA\Property(
     *                    property="program_id",
     *                    description="Insert program_id",
     *                    type="integer",
     *
     *                   ),
     *                   @OA\Property(
     *                    property="details",
     *                    description="Insert details",
     *                    type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                    property="division_id",
     *                    description="Insert division_id",
     *                    type="integer",
     *
     *                   ),
     *                   @OA\Property(
     *                    property="district_id",
     *                    description="Insert district_id",
     *                    type="integer",
     *
     *                   ),
     *                   @OA\Property(
     *                    property="office_id",
     *                    description="Insert office_id",
     *                    type="integer",
     *
     *                   ),
     *                    @OA\Property(
     *                      property="location_id",
     *                      description="insert location_id",
     *                      type="integer",
     *
     *                   ),
     *
     *                  @OA\Property(
     *                      property="members[0]['member_name]",
     *                      description="insert memeber_name ",
     *                      type="text",
     *
     *                   ),
     *                 @OA\Property(
     *                      property="members[0]['designation]",
     *                      description="insert designation",
     *                      type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="members[0]['address]",
     *                      description="insert address",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="members[0]['email]",
     *                      description="email",
     *                      type="text",
     *                   ),
     *                  @OA\Property(
     *                      property="members[0]['phone]",
     *                      description="phone",
     *                      type="integer",
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

     public function committeeUpdate(CommitteeUpdateRequest $request){

        try {
            $committee = $this->beneficiaryService->updateCommitee($request);
            activity("Committee")
            ->causedBy(auth()->user())
            ->performedOn($committee)
            ->log('Committee Updated !');
            return CommitteeResource::make($committee)->additional([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }
}