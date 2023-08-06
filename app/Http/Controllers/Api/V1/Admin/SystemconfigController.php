<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AllowanceProgram;
use App\Http\Traits\MessageTrait;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\Systemconfig\SystemconfigService;
use App\Http\Requests\Admin\Systemconfig\Allowance\AllowanceRequest;
use App\Http\Resources\Admin\Systemconfig\Allowance\AllowanceResource;
use App\Http\Requests\Admin\Systemconfig\Allowance\AllowanceUpdateRequest;

class SystemconfigController extends Controller
{
    use MessageTrait;
    private $systemconfigService;

    public function __construct(SystemconfigService $systemconfigService) {
        $this->systemconfigService= $systemconfigService;
    }

  /**
    * @OA\Get(
    *     path="/admin/allowance/get",
    *      operationId="getAllallowancePaginated",
    *     tags={"ALLOWANCE-PROGRAMM MANAGEMENT"},
    *      summary="get paginated Allowances",
    *      description="get paginated Allowances",
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

    public function getAllallowancePaginated(Request $request){
        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage');
        $page = $request->query('page');

        $filterArrayNameEn=[];
        $filterArrayNameBn=[];
        $filterArrayDesription=[];
        $filterArrayGuideline=[];
        $filterArrayServiceType=[];

        if ($searchText) {
            $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayDesription[] = ['description', 'LIKE', '%' . $searchText . '%'];
            $filterArrayGuideline[] = ['guideline', 'LIKE', '%' . $searchText . '%'];
            $filterArrayServiceType[] = ['service_type', 'LIKE', '%' . $searchText . '%'];

        }
        $office = AllowanceProgram::query()
        ->where(function ($query) use ($filterArrayNameEn,$filterArrayNameBn,$filterArrayDesription,$filterArrayGuideline,$filterArrayServiceType) {
            $query->where($filterArrayNameEn)
                  ->orWhere($filterArrayNameBn)
                  ->orWhere($filterArrayDesription)
                  ->orWhere($filterArrayGuideline)
                  ->orWhere($filterArrayServiceType);
        })
        ->with('lookup')

        ->latest()
        ->paginate($perPage, ['*'], 'page');

        return AllowanceResource::collection($office)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }
      /**
     *
     * @OA\Post(
     *      path="/admin/allowance/insert",
     *      operationId="insertAllowance",
     *      tags={"ALLOWANCE-PROGRAMM MANAGEMENT"},
     *      summary="insert a allowance program",
     *      description="insert a allowance program",
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
     *                      description="Insert allowance program  name in english",
     *                      type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                    property="name_bn",
     *                    description="Insert allowance program  name in Bengali",
     *                    type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                    property="guideline",
     *                    description="Insert allowance program  guideline",
     *                    type="text",
     *
     *                   ),
     *                  @OA\Property(
     *                    property="description",
     *                    description="Insert allowance program  description",
     *                    type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="service_type",
     *                      description="insert Service type of Allowance program",
     *                      type="integer",
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
    public function insertAllowance(AllowanceRequest $request){
        // return $request->all();
        try {
            $allowance = $this->systemconfigService->createallowance($request);
            activity("Allowance")
            ->causedBy(auth()->user())
            ->performedOn($allowance)
            ->log('Allowance Created !');
            return AllowanceResource::make($allowance)->additional([
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
     *      path="/admin/allowance/update",
     *      operationId="allowanceUpdate",
     *      tags={"ALLOWANCE-PROGRAMM MANAGEMENT"},
     *      summary="update a office",
     *      description="updatet a office",
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
     *                      description="id of the Allowance program",
     *                      type="integer",
     *                   ),
     *                    @OA\Property(
     *                      property="name_en",
     *                      description="Insert allowance program  name in english",
     *                      type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                    property="name_bn",
     *                    description="Insert allowance program  name in Bengali",
     *                    type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                    property="guideline",
     *                    description="Insert allowance program  guideline",
     *                    type="text",
     *
     *                   ),
     *                  @OA\Property(
     *                    property="description",
     *                    description="Insert allowance program  description",
     *                    type="text",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="service_type",
     *                      description="insert Service type of Allowance program",
     *                      type="integer",
     *
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

     public function allowanceUpdate(AllowanceUpdateRequest $request){

        try {
            $allowance = $this->systemconfigService->updateAllowance($request);
            activity("Allowance")
            ->causedBy(auth()->user())
            ->performedOn($allowance)
            ->log('Office Updated !');
            return AllowanceResource::make($allowance)->additional([
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
     *      path="/admin/allowance/destroy/{id}",
     *      operationId="destroyAllowance",
     *     tags={"ALLOWANCE-PROGRAMM MANAGEMENT"},
     *      summary=" destroy Allowance programm",
     *      description="Returns allowance destroy by id",
     *      security={{"bearer_token":{}}},
     *
     *       @OA\Parameter(
     *         description="id of allowance to return",
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
    public function destroyAllowance($id)
    {


        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:allowance_programs,id',
        ]);

        $validator->validated();

        $allowance = AllowanceProgram::whereId($id)->first();
        if($allowance){
            $allowance->delete();
        }
        activity("Allowance")
        ->causedBy(auth()->user())
        ->log('Allowance Deleted!!');
         return $this->sendResponse($allowance, $this->deleteSuccessMessage, Response::HTTP_OK);
    }
}
