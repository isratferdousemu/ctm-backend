<?php

namespace App\Http\Services\Client;

use App\Helpers\Helper;
use App\Models\ApiDataReceive;
use App\Models\ApiLog;

class ApiService
{
    public function hasPermission($request, $apiName)
    {
        $apiLog = ApiLog::create(['request_time' => now(), 'status' => 1]);

        try {
            $apiDataReceive = ApiDataReceive::where([
                'username' => $request->header('username'),
                'api_key' => $request->header('api_key')
            ])->first();

            abort_if(!$apiDataReceive,403, 'Unauthorized action');

            $apiDataReceive->increment('total_hit');

            $apiLog->update(['api_data_receive_id' => $apiDataReceive->id]);

            if ($apiDataReceive->whitelist_ip) {
                abort_if($apiDataReceive->whitelist_ip != Helper::clientIp(), 403, 'Access denied');
            }

            abort_if(now()->lt($apiDataReceive->start_date), 422, 'Access denied! Endpoint is not active yet.');

            if ($apiDataReceive->end_date) {
                abort_if(now()->gt($apiDataReceive->end_date), 422, 'Access denied! Endpoint is no longer active.');
            }

            $apiList = $apiDataReceive->apiList()->where('api_unique_id', $apiName)->first();

            abort_if(!$apiList,403, 'Unauthorized action');

            $apiLog->update(['api_list_id' => $apiList->id]);

            return $apiList->selected_columns;
        } catch (\Throwable $throwable) {
            $apiLog->update(['status' => 0]);

            throw $throwable;
        }
    }




}
