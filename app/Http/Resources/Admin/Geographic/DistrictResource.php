<?php

namespace App\Http\Resources\Admin\Geographic;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistrictResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        // if load districtParent then return this array otherwise return another array
        if ($this->districtParent) {
            return [
                'id'                   => $this->district_id,
                'name_en'              => $this->district_name_en,
                'name_bn'              => $this->district_name_bn,
                'code'                 => $this->district_code,
                'type'                 => $this->district_type,
                'location_type'                 => $this->district_location_type,
                'parent_id'                 => $this->district_parent_id,
                'parent'  => DivisionResource::make($this->whenLoaded('districtParent')),
            ];
        }


        return [
                'id'                   => $this->id,
            'name_en'              => $this->name_en,
            'name_bn'              => $this->name_bn,
            'code'                 => $this->code,
            'type'                 => $this->type,
            'division'  => DivisionResource::make($this->whenLoaded('parent')),
        ];

    }


}
