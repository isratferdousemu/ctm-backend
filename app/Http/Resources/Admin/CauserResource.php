<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CauserResource extends JsonResource
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
            'full_name'                 => $this->full_name,
            'user_type'                  => $this->user_type,
            'email'                  => $this->email,
            'branch_id'                  => $this->branch_id,
            'created_at'            => $this->created_at
        ];
    }
}
