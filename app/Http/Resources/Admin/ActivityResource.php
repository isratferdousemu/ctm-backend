<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
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
            'description'                 => $this->description,
            'log_name'            => $this->log_name,
            'subject'            => $this->subject,
            'causer'                  => CauserResource::make($this->whenLoaded('causer')),
            'created_at'            => $this->created_at,
        ];
    }
}
