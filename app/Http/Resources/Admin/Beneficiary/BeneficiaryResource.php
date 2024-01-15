<?php

namespace App\Http\Resources\Admin\Beneficiary;

use App\Http\Resources\Admin\Lookup\LookupResource;
use App\Http\Resources\Admin\Systemconfig\Allowance\AllowanceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BeneficiaryResource extends JsonResource
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
            'program' => AllowanceResource::make($this->whenLoaded('program')),
            "application_id" => $this->application_id,
            "name_en" => $this->name_en,
            "name_bn" => $this->name_bn,
            "mother_name_en" => $this->mother_name_en,
            "mother_name_bn" => $this->mother_name_bn,
            "father_name_en" => $this->father_name_en,
            "father_name_bn" => $this->father_name_bn,
            "spouse_name_en" => $this->spouse_name_en,
            "spouse_name_bn" => $this->spouse_name_bn,
            "identification_mark" => $this->identification_mark,
            "age" => $this->age,
            "date_of_birth" => $this->date_of_birth,
            "nationality" => $this->nationality,
            "gender" => LookupResource::make($this->whenLoaded('gender')),
            "education_status" => $this->education_status,
            "profession" => $this->profession,
            "religion" => $this->religion,
            "marital_status" => $this->marital_status,
            "email" => $this->email,
            "image" => $this->image,
            "signature" => $this->signature,
            "current_location_id" => $this->current_location_id,
            "current_post_code" => $this->current_post_code,
            "current_address" => $this->current_address,
            "mobile" => $this->mobile,
            "permanent_location_id" => $this->permanent_location_id,
            "permanent_post_code" => $this->permanent_post_code,
            "permanent_address" => $this->permanent_address,
            "permanent_mobile" => $this->permanent_mobile,
            "nominee_en" => $this->nominee_en,
            "nominee_bn" => $this->nominee_bn,
            "nominee_verification_number" => $this->nominee_verification_number,
            "nominee_address" => $this->nominee_address,
            "nominee_image" => $this->nominee_image,
            "nominee_signature" => $this->nominee_signature,
            "nominee_relation_with_beneficiary" => $this->nominee_relation_with_beneficiary,
            "nominee_nationality" => $this->nominee_nationality,
            "account_name" => $this->account_name,
            "account_number" => $this->account_number,
            "account_owner" => $this->account_owner,
            "status" => $this->status,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "score" => $this->score
        ];
    }
}
