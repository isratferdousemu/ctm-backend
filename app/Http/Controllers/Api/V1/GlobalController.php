<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Systemconfig\Allowance\AllowanceResource;
use App\Http\Services\Global\GlobalService;
use App\Http\Traits\MessageTrait;
use App\Models\AllowanceProgram;
use App\Models\Bank;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GlobalController extends Controller
{
    use MessageTrait;
    private $globalService;

    public function __construct(GlobalService $globalService) {
        $this->globalService= $globalService;
    }

    /**
    * @OA\Get(
    *     path="/global/program",
    *      operationId="getAllProgram",
    *     tags={"GLOBAL"},
    *      summary="get all program",
    *      description="get all program",
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
    public function getAllProgram(){
        $data = AllowanceProgram::with('lookup','addtionalfield.additional_field_value')->get();
        return AllowanceResource::collection($data)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }
}
