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
