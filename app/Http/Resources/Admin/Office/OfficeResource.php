<?php

namespace App\Http\Resources\Admin\Office;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Geographic\CityResource;
use App\Http\Resources\Admin\Geographic\DistrictResource;
use App\Http\Resources\Admin\Geographic\DivisionResource;

class OfficeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

        'id'               =>      $this->id,
        'division'         =>      DivisionResource::make($this->whenLoaded('division')),
        'district'         =>      DistrictResource::make($this->whenLoaded('district')),
        'thana'            =>      CityResource::make($this->whenLoaded('thana')),
        'name_en'          =>      $this->name_en,
        'name_bn'          =>      $this->name_bn,
        'office_type'      =>      $this->office_type,
        'office_address'   =>      $this->office_address,
        'comment'          =>      $this->comment,
        'status'           =>      $this->status,
        ];
    }
}