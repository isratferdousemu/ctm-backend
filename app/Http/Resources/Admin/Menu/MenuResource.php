<?php

namespace App\Http\Resources\Admin\Menu;

use App\Http\Resources\Admin\PermissionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "label_name_en" => $this->label_name_en,
            "label_name_bn" => $this->label_name_bn,
            "order" => $this->order,
            "page_link" =>PermissionResource::make($this->whenLoaded('pageLink')),
            "link_type" => $this->link_type,
            "link" => $this->link,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "children" =>MenuResource::collection($this->whenLoaded('children')),
        ];
    }
}
