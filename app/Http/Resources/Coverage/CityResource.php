<?php

namespace App\Http\Resources\Coverage;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'name'                 => $this->name,
            'post_code'              => $this->post_code,
            'division'                  => DivisionResource::make($this->whenLoaded('divisions')),
            'created_at'            => $this->created_at
        ];
    }
}
