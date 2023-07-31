<?php

namespace App\Http\Resources\Admin\Office;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        'division_id'      =>      $this->division_id,
        'district_id'      =>      $this->district_id,
        'thana_id'         =>      $this->thana_id,
        'name_en'          =>      $this->name_en,
        'name_bn'          =>      $this->name_bn,
        'office_type'      =>      $this->office_type,
        'office_address'   =>      $this->office_address,
        'comment'          =>      $this->comment,
        'status'           =>      $this->status,
        ];
    }
}
