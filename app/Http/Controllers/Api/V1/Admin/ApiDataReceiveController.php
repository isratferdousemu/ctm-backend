<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\API\ApiDataReceiveRequest;
use App\Models\ApiDataReceive;
use Illuminate\Http\Request;

class ApiDataReceiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ApiDataReceiveRequest $request, ApiDataReceive $apiDataReceive)
    {

        return $request;
    }


    public function saveApiDataReceive($apiDataReceive, $request)
    {
        $apiDataReceive->organization_name = $request->organization_name;
        $apiDataReceive->organization_phone = $request->organization_phone;
        $apiDataReceive->organization_email = $request->organization_email;
        $apiDataReceive->responsible_person_email = $request->responsible_person_email;
        $apiDataReceive->responsible_person_nid = $request->responsible_person_nidphone;
        $apiDataReceive->username = $request->username;
        $apiDataReceive->whitelist_ip = $request->whitelist_ip;
        $apiDataReceive->start_date = $request->start_date;
        $apiDataReceive->end_date = $request->end_date;
        $apiDataReceive->save();

        $this->saveApiList($apiDataReceive, $request);

    }


    /**
     * @param ApiDataReceive $apiDataReceive
     * @param $request
     * @return void
     */
    public function saveApiList($apiDataReceive, $request)
    {
        $apiDataReceive->apiList()->sync($request->api_list);
    }

    /**
     * Display the specified resource.
     */
    public function show(ApiDataReceive $apiDataReceive)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
