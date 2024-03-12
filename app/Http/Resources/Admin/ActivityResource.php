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
            'properties'            => $this->properties,
            'created_at'            => $this->created_at->diffForHumans(),
        ];
    }

    public function withResponse($request, $response)
    {
        // Add pagination metadata
        $paginationData = [
            'current_page' => $this->resource->currentPage(),
            'per_page' => $this->resource->perPage(),
            'total' => $this->resource->total(),
            'last_page' => $this->resource->lastPage(),
            // You can add more pagination metadata here if needed
        ];

        $response->setData(array_merge(
            $response->getData(true),
            ['meta' => $paginationData]
        ));
    }
}
