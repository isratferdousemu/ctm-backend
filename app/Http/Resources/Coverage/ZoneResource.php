<?php

namespace App\Http\Resources\Coverage;

use Illuminate\Http\Resources\Json\JsonResource;

class ZoneResource extends JsonResource
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
            'home_delivery'                 => $this->home_delivery,
            'charge_one_kg'                 => $this->charge_one_kg,
            'charge_two_kg'                 => $this->charge_two_kg,
            'charge_three_kg'                 => $this->charge_three_kg,
            'charge_extra_per_kg'                 => $this->charge_extra_per_kg,
            'cod_charge'                 => $this->cod_charge,
            'area'                  => AreaResource::make($this->whenLoaded('area')),
            'status'                 => $this->status,
            'created_at'            => $this->created_at
        ];
    }
}
