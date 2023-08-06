<?php

namespace App\Http\Resources\Admin\systemconfig\Finanacial;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinancialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id'                  => $this->id,
            'financial_year'     => $this->finanacial_year,
            'start_date'          => $this->start_date,
            'end_date'            => $this->end_date,
            'status'              => $this->status,

        ];
    }
}
