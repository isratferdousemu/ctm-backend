<?php

namespace App\Http\Services\Admin\Device;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeviceService
{
    public function createDevice(Request $request){

        DB::beginTransaction();
        try {

            $device                       = new Device;
            $device->user_id                = $request->user_id;
            $device->name                = $request->name;
            $device->device_id                = $request->device_id;
            $device->ip_address                   = $request->ip();
            $device->device_type                   = $request->device_type;
            $device->purpose_use                   = $request->purpose_use;
            $device->createdBy                   = Auth()->user()->id;
            $device->save();
            DB::commit();
            return $device;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
