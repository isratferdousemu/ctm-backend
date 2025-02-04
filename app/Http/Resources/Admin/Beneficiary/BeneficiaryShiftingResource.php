<?php

namespace App\Http\Resources\Admin\Beneficiary;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BeneficiaryShiftingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            'from_program_name_en' => $this->from_program_name_en,
            'from_program_name_bn' => $this->from_program_name_bn,
            'to_program_name_en' => $this->to_program_name_en,
            'to_program_name_bn' => $this->to_program_name_bn,
            "beneficiary_id" => $this->beneficiary_id,
            "application_id" => $this->application_id,
            "name_en" => $this->name_en,
            "name_bn" => $this->name_bn,
            "mother_name_en" => $this->mother_name_en,
            "mother_name_bn" => $this->mother_name_bn,
            "father_name_en" => $this->father_name_en,
            "father_name_bn" => $this->father_name_bn,
            "division_name_en" => $this->division_name_en,
            "division_name_bn" => $this->division_name_bn,
            "district_name_en" => $this->district_name_en,
            "district_name_bn" => $this->district_name_bn,
            "city_corporation_name_en" => $this->city_corporation_name_en,
            "city_corporation_name_bn" => $this->city_corporation_name_bn,
            "district_pourashava_name_en" => $this->district_pourashava_name_en,
            "district_pourashava_name_bn" => $this->district_pourashava_name_bn,
            "upazila_name_en" => $this->upazila_name_en,
            "upazila_name_bn" => $this->upazila_name_bn,
            "pourashava_name_en" => $this->pourashava_name_en,
            "pourashava_name_bn" => $this->pourashava_name_bn,
            "thana_en" => $this->thana_en,
            "thana_bn" => $this->thana_bn,
            "union_en" => $this->union_en,
            "union_bn" => $this->union_bn,
            "ward_en" => $this->ward_en,
            "ward_bn" => $this->ward_bn,
            "shifting_cause" => $this->shifting_cause,
            "activation_date" => Carbon::parse($this->activation_date)->format('d/m/Y'),
        ];
    }
}
