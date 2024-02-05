<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AdditionalFields;
use App\Models\AllowanceProgram;
use App\Http\Traits\MessageTrait;
use App\Models\AllowanceProgramAge;
use App\Http\Controllers\Controller;
use App\Models\AllowanceProgramAmount;
use App\Models\AllowanceAdditionalField;
use App\Models\AllowanceProgramAdditionalField;
use App\Http\Services\Admin\Systemconfig\SystemconfigService;
use App\Http\Requests\Admin\Systemconfig\Allowance\AllowanceRequest;
use App\Http\Resources\Admin\Systemconfig\Allowance\AllowanceResource;
use App\Http\Requests\Admin\Systemconfig\Allowance\AllowanceUpdateRequest;
use App\Http\Resources\Admin\Systemconfig\Allowance\AdditionalFieldsResource;
use App\Http\Requests\Admin\Systemconfig\Allowance\AllowanceAdditionalField\AllowanceAdditionalFieldRequest;
use App\Http\Requests\Admin\Systemconfig\Allowance\AllowanceAdditionalField\AllowanceAdditionalFieldUpdateRequest;

class SystemconfigController extends Controller
{
    use MessageTrait;
    private $systemconfigService;

    public function __construct(SystemconfigService $systemconfigService) {
        $this->systemconfigService= $systemconfigService;
    }

 /**
     *
     * @OA\Post(
     *      path="/admin/allowance/allowance-additional-field/insert",
     *      operationId="insertAllowanceAdditionalField",
     *     tags={"ALLOWANCE-PROGRAM-MANAGEMENT"},
     *      summary="insert a AllowanceAdditionalField",
     *      description="insert a AllowanceAdditionalField",
     *      security={{"bearer_token":{}}},
     *
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="enter inputs",
     *            @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(

     *                   @OA\Property(
     *                      property="name_en",
     *                      description="insert name of AllowanceAdditionalField English ",
     *                      type="text",
     *                   ),
     *                      @OA\Property(
     *                      property="name_bn",
     *                      description="insert bangla  of AllowanceAdditionalField Bangla",
     *                      type="text",
     *                   ),
     *                    @OA\Property(
     *                      property="type",
     *                      description="insert Name  of AllowanceAdditionalField",
     *                      type="text",

     *                   ),
     *                 @OA\Property(
     *                      property="field_value[0]['value]",
     *                      description="insert designation",
     *                      type="text",
     *
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

     public function insertAllowanceAdditionalField(AllowanceAdditionalFieldRequest $request){
        try {
            $committee = $this->systemconfigService->createAllowanceAdditionalField($request);
            activity("AllowanceAdditionalField")
            ->causedBy(auth()->user())
            ->performedOn($committee)
            ->log('AllowanceAdditionalField Created !');
            return AdditionalFieldsResource::make($committee)->additional([
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
     *      path="/admin/allowance/allowance-additional-field/update",
     *      operationId="updateAllowanceAdditionalField",
     *     tags={"ALLOWANCE-PROGRAM-MANAGEMENT"},
     *      summary="update a AllowanceAdditionalField",
     *      description="update a AllowanceAdditionalField",
     *      security={{"bearer_token":{}}},
     *
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="enter inputs",
     *            @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(

     *                   @OA\Property(
     *                      property="name_en",
     *                      description="update name of AllowanceAdditionalField English ",
     *                      type="text",
     *                   ),
     *                      @OA\Property(
     *                      property="name_bn",
     *                      description="update bangla  of AllowanceAdditionalField Bangla",
     *                      type="text",
     *                   ),
     *                    @OA\Property(
     *                      property="type",
     *                      description="update Name  of AllowanceAdditionalField",
     *                      type="text",

     *                   ),
     *                    @OA\Property(
     *                      property="additional_field_id",
     *                      description="update Name  of ID",
     *                      type="text",

     *                   ),
     *                 @OA\Property(
     *                      property="field_value[0]['value']",
     *                      description="update designation",
     *                      type="text",
     *
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
     *          description="Successful update operation",
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

     public function updateAllowanceAdditionalField(AllowanceAdditionalFieldUpdateRequest $request){
        // print_r($request->all());




        // return $this->sendError('err', $request->field_value);




        try {
            $data = $this->systemconfigService->updateAllowanceAdditionalField($request);
            activity("AllowanceAdditionalField")
            ->causedBy(auth()->user())
            ->performedOn($data)
            ->log('AllowanceAdditionalField Created !');

            // return $data;

            return AdditionalFieldsResource::make($data)->additional([
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
    *     path="/admin/allowance/get",
    *      operationId="getAllallowancePaginated",
    *     tags={"ALLOWANCE-PROGRAM-MANAGEMENT"},
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
        $allowance = new AllowanceProgram;

        if ($request->has('sortBy') && $request->has('sortDesc')) {
            $sortBy = $request->query('sortBy');

            $sortDesc = $request->query('sortDesc') == true ? 'desc' : 'asc';

            $allowance = $allowance->orderBy($sortBy, $sortDesc);
        } else {
            $allowance = $allowance->orderBy('name_en', 'asc');
        }

        $searchValue = $request->input('search');

        if($searchValue)
        {
            $allowance->where(function($query) use ($searchValue) {
                $query->where('name_en', 'like', '%' . $searchValue . '%');
                $query->where('name_bn', 'like', '%' . $searchValue . '%');
                $query->orWhere('payment_cycle', 'like', '%' . $searchValue . '%');
            });

            $itemsPerPage = 10;

            if($request->has('itemsPerPage')) {
                $itemsPerPage = $request->get('itemsPerPage');

                return $allowance->paginate($itemsPerPage);
            }
        }else{
            $itemsPerPage = 10;

            if($request->has('itemsPerPage'))
            {
                $itemsPerPage = $request->get('itemsPerPage');

            }

        return $allowance->paginate($itemsPerPage);
        }


    }

    /**
     * @OA\Get(
     *     path="/admin/allowance/get_additional_field",
     *      operationId="getAdditionalField",
     *     tags={"ALLOWANCE-PROGRAM-MANAGEMENT"},
     *      summary="get addiontal field Allowances",
     *      description="get addiontal field Allowances",
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
    public function getAdditionalField()
    {
        $additional_fields = AdditionalFields::latest()->with('additional_field_value')->get();

        return \response()->json([
            'data' => $additional_fields
        ],Response::HTTP_OK);
    }

      /**
     *
     * @OA\Post(
     *      path="/admin/allowance/insert",
     *      operationId="insertAllowance",
     *      tags={"ALLOWANCE-PROGRAM-MANAGEMENT"},
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
        if ($request->isMethod('post'))
        {
            \DB::beginTransaction();

            try {
                $allowance_program = new AllowanceProgram();

                $allowance_program->name_en = $request->name_en;
                $allowance_program->name_bn = $request->name_bn;
                // $allowance_program->payment_cycle = $request->payment_cycle;

                // if ($request->is_marital == true)
                // {
                //     $allowance_program->is_marital = 1;
                // }else{
                //     $allowance_program->is_marital = 0;
                // }

                // $allowance_program->marital_status = $request->marital_status;
                // $allowance_program->is_active = 0;

                // if ($request->is_age_limit == true)
                // {
                //     $allowance_program->is_age_limit = 1;
                // }else{
                //     $allowance_program->is_age_limit = 0;
                // }

                // $allowance_program->is_disable_class = $request->is_disable_class;

                $allowance_program->save();


                if ($request->age_limit != null)
                {
                    foreach ($request->age_limit as $al)
                    {
                        $allowance_program_age = new AllowanceProgramAge();

                        $allowance_program_age->allowance_program_id = $allowance_program->id;
                        $allowance_program_age->gender_id = $al['gender_id'];
                        $allowance_program_age->min_age = $al['min_age'];
                        $allowance_program_age->max_age = $al['max_age'];
                        $allowance_program_age->amount = $al['amount'];

                        $allowance_program_age->save();
                    }
                }

                $amounts = json_decode($request->input('amount'), true);

                if ($amounts != null)
                {
                    foreach ($amounts as $a)
                    {
                        $allowance_program_amount = new AllowanceProgramAmount();

                        $allowance_program_amount->allowance_program_id = $allowance_program->id;
                        $allowance_program_amount->type_id = $a['type_id'];
                        $allowance_program_amount->amount = $a['amount'];

                        $allowance_program_amount->save();
                    }
                }

                if ($request->input('add_field_id') != null)
               {
                   foreach ($request->input('add_field_id') as $item => $value) {

                       $allowance_program_add_field = new AllowanceProgramAdditionalField();

                       $allowance_program_add_field->allowance_program_id = $allowance_program->id;
                       $allowance_program_add_field->field_id = $request->add_field_id[$item];

                       $allowance_program_add_field->save();
                   }
               }

                \DB::commit();


                activity("Allowance")
                    ->causedBy(auth()->user())
                    ->performedOn($allowance_program)
                    ->log('Allowance Created !');

                return \response()->json([
                    'success' => true,
                    'message' => $this->insertSuccessMessage,
                ],Response::HTTP_CREATED);

            } catch (\Throwable $th) {
                //throw $th;
                \DB::rollBack();

                return $this->sendError($th->getMessage(), [], 500);
            }
        }

    }


    /**
     * @OA\Get(
     *     path="/admin/allowance/edit/{id}",
     *      operationId="edit",
     *     tags={"ALLOWANCE-PROGRAM-MANAGEMENT"},
     *      summary="get edit Allowances",
     *      description="get edit Allowances",
     *      security={{"bearer_token":{}}},
     *      @OA\Parameter(
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
    public function edit($id)
    {
        $allowance = AllowanceProgram::findOrFail($id);

        $allowance_gender = AllowanceProgramAge::where('allowance_program_id', $id)->pluck('gender_id')->toArray();

        $allowance_age = AllowanceProgramAge::where('allowance_program_id', $id)->get();

        $allowance_amount = AllowanceProgramAmount::where('allowance_program_id', $id)->get();

        $allowance_field = AllowanceProgramAdditionalField::where('allowance_program_id', $id)->pluck('field_id')->toArray();

        return \response()->json([
            'allowance' => $allowance,
            'allowance_gender' => $allowance_gender,
            'allowance_age_limit' => $allowance_age,
            'allowance_amount' => $allowance_amount,
            'allowance_field' => $allowance_field
        ]);
    }

    /**
     *
     * @OA\Put(
     *      path="/admin/allowance/update",
     *      operationId="allowanceUpdate",
     *      tags={"ALLOWANCE-PROGRAM-MANAGEMENT"},
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
    //  public function allowanceUpdate(AllowanceUpdateRequest $request, $id){
    //     if ($request->_method == 'PUT')
    //     {

    //         \DB::beginTransaction();

    //         try {

    //             $allowance_program = AllowanceProgram::findOrFail($id);

    //             $allowance_program->name_en = $request->name_en;
    //             $allowance_program->name_bn = $request->name_bn;
    //             $allowance_program->payment_cycle = $request->payment_cycle;

    //             if ($request->is_marital == true)
    //             {
    //                 $allowance_program->is_marital = 1;
    //             }else{
    //                 $allowance_program->is_marital = 0;
    //             }

    //             $allowance_program->marital_status = $request->marital_status;

    //             if ($request->is_active == "0" || $request->is_active == false)
    //             {
    //                 $allowance_program->is_active = 0;
    //             }

    //             if ($request->is_active == "1" ||$request->is_active == true)
    //             {
    //                 $allowance_program->is_active = 1;

    //             }
    //              if ($request->is_active == "0" || $request->is_active == false)
    //             {
    //                 $allowance_program->is_active = 0;

    //             }

    //             if ($request->system_status == true)
    //             {
    //                 $allowance_program->system_status = 1;
    //             }
    //              if ($request->system_status == "0" || $request->system_status == false)
    //             {
    //                 $allowance_program->system_status = 0;
    //             }
    //               if ($request->pmt_status == true || $request->pmt_status == "1")
    //             {
    //                 $allowance_program->pmt_status = 1;
    //             }
    //              if ($request->pmt_status == "0" || $request->pmt_status == false)
    //             {
    //                 $allowance_program->pmt_status = 0;
    //             }

    //             if ($request->is_age_limit == true)
    //             {
    //                 $allowance_program->is_age_limit = 1;
    //             }else{
    //                 $allowance_program->is_age_limit = 0;
    //             }

    //             if ($request->is_disable_class == true)
    //             {
    //                 $allowance_program->is_disable_class = 1;
    //             }else{
    //                 $allowance_program->is_disable_class = 0;
    //             }


    //             $allowance_program->save();




    //             if ($request->input('age_limit') != null)
    //             {
    //                 $prevAges = $allowance_program->ages()->delete();

    //                 foreach ($request->input('age_limit') as $al)
    //                 {
    //                     $new_amount = 0;
    //                     if ($al['amount'] == null)
    //                     {
    //                         $new_amount = null;
    //                     }else{
    //                         $new_amount = $al['amount'];
    //                     }

    //                     AllowanceProgramAge::Insert(
    //                 [
    //                             "allowance_program_id" => $allowance_program->id,
    //                             "gender_id" => $al['gender_id'],
    //                             "min_age" => $al['min_age'],
    //                             "max_age" => $al['max_age'],
    //                             "amount" => $new_amount,
    //                         ]
    //                     );
    //                 }
    //             }



    //             if ($request->input('amount') != null)
    //             {
    //                 $allowanceProgramId = $allowance_program->id; // Assuming $allowance_program->id holds the ID you're working with

    //                 $arrayOfIds = [/* Your array of IDs */]; // Populate this array with your list of IDs

    //                 // Get the IDs existing in the database for the specified allowance_program_id
    //                 $existingIdsInDatabase = AllowanceProgramAmount::where('allowance_program_id', $allowanceProgramId)
    //                     ->pluck('id')
    //                     ->toArray();

    //                 // Find the IDs that exist in the database but not in the provided array
    //                 $idsToDelete = array_diff($existingIdsInDatabase, $arrayOfIds);

    //                 // Delete the records that are in the database but not in the provided array
    //                 if (!empty($idsToDelete)) {
    //                     AllowanceProgramAmount::where('allowance_program_id', $allowanceProgramId)
    //                         ->whereIn('id', $idsToDelete)
    //                         ->delete();
    //                 } else {
    //                     //
    //                 }

    //                 foreach ($request->input('amount') as $a)
    //                 {
    //                     AllowanceProgramAmount::updateOrInsert(
    //                         ['id' => $a['id']],
    //                         [
    //                             "allowance_program_id" => $allowance_program->id,
    //                             "type_id" => $a['type_id'],
    //                             "amount" => $a['amount'],
    //                             "created_at" => Carbon::now(),
    //                             "updated_at" => Carbon::now()
    //                         ]
    //                     );
    //                 }
    //             }

    //             $result = [];

    //             $updateAddField = $request->input('add_field_id');

    //             // check $updateAddField ids are exists in AllowanceProgramAdditionalField table or not if not exists then insert

    //             foreach ($updateAddField as $up)
    //             {
    //                 $check = AllowanceProgramAdditionalField::where('allowance_program_id', $id)->where('field_id', $up)->first();

    //                 if ($check == null)
    //                 {
    //                     $result[] = array(
    //                         "allowance_program_id" => $id,
    //                         "field_id" => $up,
    //                         "created_at" => Carbon::now(),
    //                         "updated_at" => Carbon::now()
    //                     );
    //                 }
    //             }
    //             $fields = AllowanceProgramAdditionalField::where('allowance_program_id', $id)->pluck('field_id')->toArray();

    //             // check fields ids are exists in $updateAddField or not if not exists then delete
    //             foreach ($fields as $field)
    //             {
    //                 if (!in_array($field, $updateAddField))
    //                 {
    //                     AllowanceProgramAdditionalField::where('allowance_program_id', $id)->where('field_id', $field)->delete();
    //                 }
    //             }

    //             AllowanceProgramAdditionalField::insert($result);

    //             // foreach ($updateAddField as $up)
    //             // {
    //             //     $result[] = array(
    //             //         "field_id" => $up,
    //             //         "created_at" => Carbon::now(),
    //             //         "updated_at" => Carbon::now()
    //             //     );

    //             // }

    //             // $allowance_program->addtionalfield()->syncWithoutDetaching($result);
    //             // $allowance_program->addtionalfield()->sync($result);

    //             \DB::commit();

    //             activity("Allowance")
    //                 ->causedBy(auth()->user())
    //                 ->performedOn($allowance_program)
    //                 ->log('Allowance Updated !');

    //             return \response()->json([
    //                 'success' => true,
    //                 'message' => $this->updateSuccessMessage,
    //             ],Response::HTTP_OK);

    //         }catch (\Throwable $th){
    //             \DB::rollBack();

    //             return $this->sendError($th->getMessage(), [], 500);
    //         }
    //     }
    // }
     public function allowanceUpdate(AllowanceUpdateRequest $request, $id){
        if ($request->_method == 'PUT')
        {

            \DB::beginTransaction();

            try {

                $allowance_program = AllowanceProgram::findOrFail($id);

                $allowance_program->name_en = $request->name_en;
                $allowance_program->name_bn = $request->name_bn;
                $allowance_program->payment_cycle = $request->payment_cycle;

                if ($request->is_marital == true)
                {
                    $allowance_program->is_marital = 1;
                }else{
                    $allowance_program->is_marital = 0;
                }

                $allowance_program->marital_status = $request->marital_status;

                // if ($request->is_active == "0" || $request->is_active == false)
                // {
                //     $allowance_program->is_active = 0;
                // }

                // if ($request->is_active == true)
                // {
                //     $allowance_program->is_active = 1;
                // }
                if ($request->is_active === "false" || $request->is_active === false)
                {
                    $allowance_program->is_active = 0;
                    }
                elseif ($request->is_active === "true" || $request->is_active === true)
                {
                $allowance_program->is_active = 1;
                    }
                if ($request->pmt_status === "false" || $request->pmt_status === false)
                {
                    $allowance_program->pmt_status = 0;
                    }
                elseif ($request->pmt_status === "true" || $request->pmt_status === true)
                {
                $allowance_program->pmt_status = 1;
                    }


                if ($request->is_age_limit == true)
                {
                    $allowance_program->is_age_limit = 1;
                }else{
                    $allowance_program->is_age_limit = 0;
                }

                if ($request->is_disable_class == true)
                {
                    $allowance_program->is_disable_class = 1;
                }else{
                    $allowance_program->is_disable_class = 0;
                }


                $allowance_program->save();


                if ($request->input('age_limit') != null)
                {
                    $prevAges = $allowance_program->ages()->delete();

                    foreach ($request->input('age_limit') as $al)
                    {
                        $new_amount = 0;
                        if ($al['amount'] == null)
                        {
                            $new_amount = null;
                        }else{
                            $new_amount = $al['amount'];
                        }

                        AllowanceProgramAge::Insert(
                    [
                                "allowance_program_id" => $allowance_program->id,
                                "gender_id" => $al['gender_id'],
                                "min_age" => $al['min_age'],
                                "max_age" => $al['max_age'],
                                "amount" => $new_amount,
                            ]
                        );
                    }
                }



                if ($request->input('amount') != null)
                {
                    $allowanceProgramId = $allowance_program->id; // Assuming $allowance_program->id holds the ID you're working with

                    $arrayOfIds = [/* Your array of IDs */]; // Populate this array with your list of IDs

                    // Get the IDs existing in the database for the specified allowance_program_id
                    $existingIdsInDatabase = AllowanceProgramAmount::where('allowance_program_id', $allowanceProgramId)
                        ->pluck('id')
                        ->toArray();

                    // Find the IDs that exist in the database but not in the provided array
                    $idsToDelete = array_diff($existingIdsInDatabase, $arrayOfIds);

                    // Delete the records that are in the database but not in the provided array
                    if (!empty($idsToDelete)) {
                        AllowanceProgramAmount::where('allowance_program_id', $allowanceProgramId)
                            ->whereIn('id', $idsToDelete)
                            ->delete();
                    } else {
                        //
                    }

                    foreach ($request->input('amount') as $a)
                    {
                        AllowanceProgramAmount::updateOrInsert(
                            ['id' => $a['id']],
                            [
                                "allowance_program_id" => $allowance_program->id,
                                "type_id" => $a['type_id'],
                                "amount" => $a['amount'],
                                "created_at" => Carbon::now(),
                                "updated_at" => Carbon::now()
                            ]
                        );
                    }
                }

                $result = [];

                $updateAddField = $request->input('add_field_id');

                // check $updateAddField ids are exists in AllowanceProgramAdditionalField table or not if not exists then insert

                foreach ($updateAddField as $up)
                {
                    $check = AllowanceProgramAdditionalField::where('allowance_program_id', $id)->where('field_id', $up)->first();

                    if ($check == null)
                    {
                        $result[] = array(
                            "allowance_program_id" => $id,
                            "field_id" => $up,
                            "created_at" => Carbon::now(),
                            "updated_at" => Carbon::now()
                        );
                    }
                }
                $fields = AllowanceProgramAdditionalField::where('allowance_program_id', $id)->pluck('field_id')->toArray();

                // check fields ids are exists in $updateAddField or not if not exists then delete
                foreach ($fields as $field)
                {
                    if (!in_array($field, $updateAddField))
                    {
                        AllowanceProgramAdditionalField::where('allowance_program_id', $id)->where('field_id', $field)->delete();
                    }
                }

                AllowanceProgramAdditionalField::insert($result);

                // foreach ($updateAddField as $up)
                // {
                //     $result[] = array(
                //         "field_id" => $up,
                //         "created_at" => Carbon::now(),
                //         "updated_at" => Carbon::now()
                //     );

                // }

                // $allowance_program->addtionalfield()->syncWithoutDetaching($result);
                // $allowance_program->addtionalfield()->sync($result);

                \DB::commit();

                activity("Allowance")
                    ->causedBy(auth()->user())
                    ->performedOn($allowance_program)
                    ->log('Allowance Updated !');

                return \response()->json([
                    'success' => true,
                    'message' => $this->updateSuccessMessage,
                ],Response::HTTP_OK);

            }catch (\Throwable $th){
                \DB::rollBack();

                return $this->sendError($th->getMessage(), [], 500);
            }
        }
    }


     /**
     * @OA\Delete (
     *      path="/admin/allowance/destroy/{id}",
     *      operationId="destroyAllowance",
     *     tags={"ALLOWANCE-PROGRAM-MANAGEMENT"},
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

        AllowanceProgramAdditionalField::where('allowance_program_id', $id)->delete();

        AllowanceProgramAge::where('allowance_program_id', $id)->delete();

        AllowanceProgramAmount::where('allowance_program_id', $id)->delete();


        if($allowance){
            $allowance->delete();
        }
        activity("Allowance")
        ->causedBy(auth()->user())
        ->log('Allowance Deleted!!');
         return $this->sendResponse($allowance, $this->deleteSuccessMessage, Response::HTTP_OK);
    }

    public function destroyGender(Request $request)
    {
        $gender_id = [];

        $allowance_age = json_decode($request->input('gender_age'), true);

        $allowance_program_id = $request->input('allowance_program_id');

        foreach ($allowance_age as $aa)
        {
            $gender_id[] = $aa['gender_id'];
        }

        AllowanceProgramAge::where('allowance_program_id', $allowance_program_id)->whereNotIn('gender_id', $gender_id)->delete();

        return \response()->json([
            'message' => 'Delete success'
        ],Response::HTTP_OK);
    }

    public function destroyDisable($id)
    {
        AllowanceProgramAmount::where('id', $id)->delete();

        return \response()->json([
            'message' => 'Delete success'
        ],Response::HTTP_OK);
    }
      /**
     * @OA\Delete (
     *      path="/admin/allowance/field/destroy/{id}",
     *      operationId="destroyField",
     *     tags={"ALLOWANCE-PROGRAM-MANAGEMENT"},
     *      summary=" destroy Allowance Field",
     *      description="Returns allowance field destroy by id",
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
    public function destroyField($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:additional_fields,id',
        ]);

        $validator->validated();



        $allowance=AllowanceAdditionalField::where('id', $id)->delete();





        activity("Allowance")
        ->causedBy(auth()->user())
        ->log('Allowance Deleted!!');
         return $this->sendResponse($allowance, $this->deleteSuccessMessage, Response::HTTP_OK);
    }
    /**
     *
     * @OA\Post(
     *      path="/admin/allowance/status",
     *      operationId="AllowanceStatusUpdate",
     *      tags={"DEVICE"},
     *      summary="update publish status of an allowance",
     *      description="update publish status of an allowance",
     *      security={{"bearer_token":{}}},
     *
     *
     *       @OA\RequestBody(
     *          required=true,
     *          description="update the Allowance",
     *
     *
     *            @OA\MediaType(
     *              mediaType="multipart/form-data",
     *           @OA\Schema(
     *
     *                    @OA\Property(
     *                      property="id",
     *                      description="id of the Allowance",
     *                      type="text",
     *
     *                   ),
     *                    @OA\Property(
     *                      property="status",
     *                      description="status or not.boolean 0 or 1",
     *                      type="text",
     *
     *                   ),
     *
     *                   ),
     *               ),
     *
     *         ),
     *
     *
     *
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation with no content",
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
       public function AllowanceStatusUpdate($id)
    {


        $device = AllowanceProgram::findOrFail($id);

        if($device->system_status == 0)
        {
            AllowanceProgram::where('id', $id)->update(['system_status'=> 1]);
            AllowanceProgram::where('id', $id)->update(['is_active'=> 1]);
            activity('Allowance')
            ->causedBy(auth()->user())
            ->performedOn($device)
            ->log('Allowance Status Updated!!');

            return response()->json([
                'message' => 'AllowanceProgram Activate Successful'
            ],Response::HTTP_OK);
        }else{
            AllowanceProgram::where('id', $id)->update(['system_status'=> 0]);
               AllowanceProgram::where('id', $id)->update(['is_active'=> 0]);
            activity('Allowance')
            ->causedBy(auth()->user())
            ->performedOn($device)
            ->log('Allowance Status Updated!!');

            return response()->json([
                'message' => 'AllowanceProgram Inactive Successful'
            ],Response::HTTP_OK);
        }
    }
}
