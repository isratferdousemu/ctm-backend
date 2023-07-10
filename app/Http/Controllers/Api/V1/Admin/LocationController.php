<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Geographic\DivisionResource;
use App\Http\Services\Admin\Location\LocationService;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\UserTrait;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    use MessageTrait,UserTrait;
    private $locationService;

    public function __construct(LocationService $locationService) {
        $this->locationService = $locationService;

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
    public function insertDivision(Request $request){
        $request->validate(Location::$divisionInsertRules);

        try {
            $division = $this->locationService->createDivision($request);
            activity()
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
}
