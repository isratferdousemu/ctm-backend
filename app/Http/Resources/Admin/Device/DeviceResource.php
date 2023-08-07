<?php

namespace App\Http\Resources\Admin\Device;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'user_id'              => $this->user_id,
            'name'              => $this->name,
            'device_name'                 => $this->device_name,
            'device_id'                 => $this->device_id,
            'device_details'                 => $this->device_details,
            'ip_address'                 => $this->ip_address,
            'device_type'                 => $this->device_type,
            'status'                 => $this->status==1?true:false,
        ];
    }
}