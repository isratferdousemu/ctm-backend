<?php

namespace App\Http\Resources\Admin\Beneficiary\Committee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Beneficiary\Committee\CommitteeResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'commitee'        =>CommitteeResource::make($this->whenLoaded('committee')),
            'member_name'     =>$this->member_name,
            'designation'     =>$this->designation,
            'email'           =>$this->email,
            'address'         =>$this->address,
            'details'         =>$this->details,
            'phone'           =>$this->phone
        ];
    }
}
