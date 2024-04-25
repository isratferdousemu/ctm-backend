<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\GrievanceManagement\GrievanceSettingResource;
use App\Http\Services\Admin\GrievanceManagement\GrievanceSettingService;
use App\Http\Traits\MessageTrait;
use App\Models\GrievanceSetting;
use App\Models\GrievanceType;
use Illuminate\Http\Request;
use App\Helpers\Helper;


class GrievanceSettingController extends Controller
{
    use MessageTrait;
    private $grievanceSetting;
    public function __construct(GrievanceSettingService $GrievanceSettingService)
    {
        $this->grievanceSetting = $GrievanceSettingService;

    }
    /**
     * Display a listing of the resource.
     */
    public function getAll(Request $request)
    {
        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage');
        $page = $request->query('page');
        $status = $request->query('status');

        $grievanceSetting = GrievanceSetting::query()
            ->with(['grievanceType', 'grievanceSubject', 'firstOfficer', 'secoundOfficer', 'thirdOfficer'])
            ->where(function ($query) use ($searchText) {
                $query->where('first_tire_solution_time', 'LIKE', '%' . $searchText . '%')
                    ->orWhere('secound_tire_solution_time', 'LIKE', '%' . $searchText . '%')
                    ->orWhere('third_tire_solution_time', 'LIKE', '%' . $searchText . '%');
            })
            ->orWhereHas('grievanceType', function ($query) use ($searchText) {
                $query->where('title_en', 'LIKE', '%' . $searchText . '%');
            })
            ->orWhereHas('grievanceSubject', function ($query) use ($searchText) {
                $query->where('title_en', 'LIKE', '%' . $searchText . '%');
            })  
             ->orWhereHas('firstOfficer', function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            })
            // ->orWhereHas('secoundOfficer', function ($query) use ($searchText) {
            //     $query->where('name', 'LIKE', '%' . $searchText . '%');
            // }) 
            // ->orWhereHas('thirdOfficer', function ($query) use ($searchText) {
            //     $query->where('name', 'LIKE', '%' . $searchText . '%');
            // })
            
            ->orderBy('id', 'asc')
            ->latest()
            ->paginate($perPage, ['*'], 'page');

        return GrievanceSettingResource::collection($grievanceSetting)->additional([
            'success' => true,
            'message' => $this->fetchDataSuccessMessage,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //    $grievanceSetting = $this->grievanceSetting->store($request);
        //    return $grievanceSetting;
        try {
            $grievanceSetting = $this->grievanceSetting->store($request);
            Helper::activityLogInsert($grievanceSetting, '', 'Grievance Setting', 'Grievance Setting Created !');

            return GrievanceSettingResource::make($grievanceSetting)->additional([
                'success' => true,
                'message' => $this->insertSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GrievanceType $grievanceType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $grievanceSetting = $this->grievanceSetting->edit($id);
            return GrievanceSettingResource::make($grievanceSetting)->additional([
                'sucess' => true,
                'message' => $this->fetchDataSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // return  $request->id;
        try {
            $beforeUpdate = $request;
            $grievanceSetting = $this->grievanceSetting->update($request);
            Helper::activityLogInsert($grievanceSetting, $beforeUpdate, 'Grievance Setting', 'Grievance Setting Updated !');

            return $grievanceSetting;
            // return GrievanceSettingResource::make($grievanceSetting)->additional([
            //     'sucess' => true,
            //     'message' => $this->fetchDataSuccessMessage,
            // ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $grievanceSetting = $this->grievanceSetting->destroy($id);
            Helper::activityLogDelete($grievanceSetting, '', 'Grievance Setting', 'Grievance Setting Deleted !');
            return GrievanceSettingResource::make($grievanceSetting)->additional([
                'success' => true,
                'message' => $this->deleteSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}