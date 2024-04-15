<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\API\ApiListRequest;
use App\Http\Requests\Admin\API\StoreRequest;
use App\Models\API;
use App\Models\ApiList;
use App\Models\ApiModule;
use App\Models\APIUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class APIListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = ApiList::query();

        $query->when(request('search'), function ($q, $v) {
            $q->where('name', 'like', "%$v%")
            ;
        });

        return $this->sendResponse($query->paginate(
            request('perPage')
        ));
    }


    public function getModules()
    {
        $data = ApiModule::with('purposes')->get();

        return $this->sendResponse($data);

    }


    public function getTableList()
    {
        $data = [];

        $tables = Schema::getTableListing();

        foreach ($tables as $table) {
            $data[] = [
                'table' => $table,
                'columns' => Schema::getColumnListing($table)
            ];
        }

        return  $this->sendResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ApiListRequest $request, ApiList $apiList)
    {
        $apiList->api_purpose_id = $request->api_purpose_id;
        $apiList->api_unique_id = $request->api_unique_id;
        $apiList->name = $request->name;
        $apiList->selected_columns = $request->selected_columns;
        $apiList->save();

        return $this->sendResponse($apiList, 'API created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(ApiList $apiList)
    {
        return $this->sendResponse($apiList);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ApiListRequest $request, ApiList $apiList)
    {
        $apiList->api_purpose_id = $request->api_purpose_id;
        $apiList->api_unique_id = $request->api_unique_id;
        $apiList->name = $request->name;
        $apiList->selected_columns = $request->selected_columns;
        $apiList->save();

        return $this->sendResponse($apiList, 'API updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApiList $apiList)
    {
        $apiList->delete();

        return $this->sendResponse($apiList, 'API deleted successfully');
    }
}
