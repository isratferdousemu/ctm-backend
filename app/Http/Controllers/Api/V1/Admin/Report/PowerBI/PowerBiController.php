<?php

namespace App\Http\Controllers\Api\V1\Admin\Report\PowerBI;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Report\PowerBiRequest;
use App\Http\Requests\Admin\Report\PowerBiUpdateRequest;
use App\Http\Resources\Admin\Device\DeviceResource;
use App\Http\Services\Admin\Report\PowerBiService;
use App\Models\PowerBiReport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class PowerBiController extends Controller
{
    private $powerBiService;

    public function __construct(PowerBiService $powerBiService)
    {
        $this->powerBiService = $powerBiService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = PowerBiReport::query();

        $query->when(request('search'), function ($q, $v) {
            $q->where('name_en', 'like', "%$v%")
                ->orWhere('name_bn', 'like', "%$v%");
        });

        $data = $this->sendResponse($query->paginate(request('perPage')));

       return $data;

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PowerBiRequest $request)
    {
        $data = $this->powerBiService->createinfo($request);

        return $this->sendResponse($data, 'Power BI Report created successfully Done');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = PowerBiReport::where('id', $id)->first();
        return \response()->json([
            'device' => $data
        ],Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PowerBiUpdateRequest $request, string $id)
    {
        $data = $this->powerBiService->updateInfo($request);
        return $this->sendResponse($data, 'Power BI Report updated successfully Done');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:power_bi_reports,id',
        ]);
        $validator->validated();

        $data = PowerBiReport::whereId($id)->first();
        $beforeupdate = $data->replicate();

        if($data){
            if ($data->image && Storage::exists($data->image)) {
                Storage::delete($data->image);
            }
            $data->delete();
        }
        Helper::activityLogDelete($beforeupdate, '','Power BI','Power BI Report Deleted !');

        return $this->sendResponse($data, 'Power BI Report deleted successfully');
    }
}
