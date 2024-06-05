<?php

namespace App\Http\Resources\Admin\Payroll;

use App\Http\Resources\Admin\Beneficiary\LocationResource;
use App\Http\Resources\Admin\Lookup\LookupResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class ActiveBeneficiaryResource extends JsonResource
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
            "beneficiary_id" => $this->beneficiary_id,
            "name_en" => $this->name_en,
            "name_bn" => $this->name_bn,
            "mother_name_en" => $this->mother_name_en,
            "mother_name_bn" => $this->mother_name_bn,
            "father_name_en" => $this->father_name_en,
            "father_name_bn" => $this->father_name_bn,
            "spouse_name_en" => $this->spouse_name_en,
            "spouse_name_bn" => $this->spouse_name_bn,
            "beneficiary_address" => $this->beneficiary_address(),
            "age" => $this->age,
            "date_of_birth" => $this->date_of_birth,
            "nationality" => $this->nationality,
            "gender" => LookupResource::make($this->whenLoaded('gender')),
            "profession" => $this->profession,
            "religion" => $this->religion,
            "marital_status" => $this->marital_status,
            "email" => $this->email,
            "mobile" => $this->mobile,
            "permanent_district_id" => $this->permanent_district_id,
            "permanentDistrict" => LocationResource::make($this->whenLoaded('permanentDistrict')),
            "upazilaCityDistPourosova" => $this->upazilaCityDistPourosova(),
            "unionWardPourosova" => $this->unionWardPourosova(),
            "union_or_pourashava" => ($this->permanentUnion?->name_en ?: $this->permanentPourashava?->name_en),
            "account_name" => $this->account_name,
            "account_number" => $this->account_number,
            "account_owner" => $this->account_owner,
            "bank_name" => $this->bank_name,
            "branch_name" => $this->branch_name,
            "monthly_allowance" => $this->monthly_allowance,
            "status" => $this->status,
            "score" => $this->score
        ];
    }

    /**
     * @return \App\Http\Resources\Admin\Location\LocationResource|null
     */
    public function upazilaCityDistPourosova(): ?LocationResource
    {
        $location = null;
        if ($this->permanentUpazila)
            $location = LocationResource::make($this->whenLoaded('permanentUpazila'));
        if ($this->permanentCityCorporation)
            $location = LocationResource::make($this->whenLoaded('permanentCityCorporation'));
        if ($this->permanentDistrictPourashava)
            $location = LocationResource::make($this->whenLoaded('permanentDistrictPourashava'));
        return $location;
    }

    public function unionWardPourosova(): ?LocationResource
    {
        $location = null;
        if ($this->permanentUnion)
            $location = LocationResource::make($this->whenLoaded('permanentUnion'));
        if ($this->permanentPourashava)
            $location = LocationResource::make($this->whenLoaded('permanentPourashava'));
        if ($this->permanentWard)
            $location = LocationResource::make($this->whenLoaded('permanentWard'));
        return $location;
    }

    private function beneficiary_address()
    {
        $beneficiary_address = $this->permanent_address;
        if ($this->permanentUnion)
            $beneficiary_address .= ', ' . $this->permanentUnion?->name_en;
        elseif ($this->permanentPourashava)
            $beneficiary_address .= ', ' . $this->permanentPourashava?->name_en;
        elseif ($this->permanentThana)
            $beneficiary_address .= ', ' . $this->permanentThana?->name_en;

        if ($this->permanentUpazila)
            $beneficiary_address .= ', ' . $this->permanentUpazila?->name_en;
        elseif ($this->permanentCityCorporation)
            $beneficiary_address .= ', ' . $this->permanentCityCorporation?->name_en;
        elseif ($this->permanentDistrictPourashava)
            $beneficiary_address .= ', ' . $this->permanentDistrictPourashava?->name_en;

        if ($this->permanentDistrict)
            $beneficiary_address .= ', ' . $this->permanentDistrict?->name_en;

        return $beneficiary_address;
    }
}
