<?php

namespace App\Http\Resources\Admin\PovertyScoreCutOff;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PovertyScoreCutOffResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
        return [

            'id'               =>      $this->id,
            'type'             =>      $this->type,
            // 'location_id'      =>      Location::find($this->location_id),
            'location_id'      =>      $this->location_id,
            'assign_location'      =>  Location::make($this->location_id),
            'score'          =>      $this->score
        ];
    }
}
