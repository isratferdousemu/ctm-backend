<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Device\DeviceRequest;
use App\Http\Resources\Admin\Device\DeviceResource;
use App\Http\Services\Admin\Device\DeviceService;
use App\Http\Traits\MessageTrait;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    use MessageTrait;
    private $DeviceService;

    public function __construct(DeviceService $DeviceService) {
        $this->DeviceService = $DeviceService;
    }

    /**
    * @OA\Get(
    *     path="/admin/device/get",
    *      operationId="getAllDevicePaginated",
    *      tags={"DEVICE"},
    *      summary="get paginated device",
    *      description="get paginated device",
    *      security={{"bearer_token":{}}},
    *     @OA\Parameter(
    *         name="searchText",
    *         in="query",
    *         description="search by user ID",
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="perPage",
    *         in="query",
    *         description="number of device per page",
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

 public function getAllDevicePaginated(Request $request){
    // Retrieve the query parameters
    $searchText = $request->query('searchText');
    $perPage = $request->query('perPage');
    $page = $request->query('page');

    $filterArrayUserId=[];
    $filterArrayName=[];
    $filterArrayIpAddress=[];

    if ($searchText) {
        $filterArrayUserId[] = ['user_id', 'LIKE', '%' . $searchText . '%'];
        $filterArrayName[] = ['name', 'LIKE', '%' . $searchText . '%'];
        $filterArrayIpAddress[] = ['ip_address', 'LIKE', '%' . $searchText . '%'];
    }
    $device = Device::query()
    ->where(function ($query) use ($filterArrayUserId,$filterArrayName,$filterArrayIpAddress) {
        $query->where($filterArrayUserId)
              ->orWhere($filterArrayName)
              ->orWhere($filterArrayIpAddress);
    })
    ->latest()
    ->paginate($perPage, ['*'], 'page');

    return DeviceResource::collection($device)->additional([
        'success' => true,
        'message' => $this->fetchSuccessMessage,
    ]);
}

    /**
     *
     * @OA\Post(
     *      path="/admin/device/insert",
     *      operationId="insertDevice",
     *      tags={"DEVICE"},
     *      summary="insert a device",
     *      description="insert a device",
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
     *                      property="user_id",
     *                      description="user id of the device user",
     *                      type="integer",
     *
     *                   ),
     *                   @OA\Property(
     *                      property="name",
     *                      description="device name",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="device_id",
     *                      description="browser fingerprint of the user",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="ip_address",
     *                      description="IP address of the user",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="device_type",
     *                      description="Device type of the user",
     *                      type="text",
     *                   ),
     *                   @OA\Property(
     *                      property="purpose_use",
     *                      description="purpose of the user device",
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
    public function insertDevice(DeviceRequest $request){
        // $count=Device::whereUserId($request->user_id)->count();
        // if($count>=5){
        //     return $this->sendError("maximum Device Registered", [], 422);
        // }
        try {
            $device = $this->DeviceService->createDevice($request);
            activity("Device")
            ->causedBy(auth()->user())
            ->performedOn($device)
            ->log('Device Created !');
            return DeviceResource::make($device)->additional([
                'success' => true,
                'message' => $this->insertSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }


}
