<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Traits\MessageTrait;
use App\Models\Bank;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GlobalController extends Controller
{
    use MessageTrait;

    /**
     *@OA\Post(
     *      path="/global/bank/all/filtered",
     *      operationId="getAllPublicBankPaginated",
     *      tags={"GLOBAL"},
     *      summary="get paginated bank from database",
     *      description="get paginated bank from database",
     *      @OA\RequestBody(
     *          required=false,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *
     *                  @OA\Property(
     *                      property="searchText",
     *                      description="search text for searching by bank name",
     *                      type="text",
     *                  ),
     *               ),
     *           ),
     *       ),
     *
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
     *       @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *
     *     )
     */
    public function getAllPublicBankPaginated(Request $request){
        $filterArrayName = [];
        if ($request->filled('searchText')) {
            $filteredText = $request->searchText;
            $filterArrayName[] = ['name', 'LIKE', '%' . $filteredText . '%'];
        }

        // $banks = Bank::query()
        //     ->where($filterArrayName)
        //     ->latest()->get();

        //     return $this->sendResponse($banks, $this->fetchSuccessMessage, Response::HTTP_OK);


    }

    public function insertLocation(Request $request){
        // $division = Location::create([
        //     'parent_id' => $request->division_id,
        //     'name_en' => 'district 1',
        //     'name_bn' => 'district',
        //     'code' => '44ddw4',
        // ]);
        // $division = Location::get();
        $division = Location::with('parent')->where('parent_id', 1)->get();
        return $division;

    }
}
