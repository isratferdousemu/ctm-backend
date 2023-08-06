<?php

namespace App\Http\Resources\Admin\Systemconfig\Allowance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Lookup\LookupResource;

class AllowanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
         [
         'id'                  => $this->id,
        'name_en'              => $this->name_en,
        'name_bn'              => $this->name_bn,
        'guideline'            => $this->guideline,
        'description'          => $this->description,
        'service_type'         =>LookupResource::make($this->whenLoaded('lookup')),

    ];


    }
}
