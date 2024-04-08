<?php

namespace App\Http\Resources\Admin\GrievanceManagement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GrievanceSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
     return [
    'id' => $this->id,
    'grievance_type_id' => $this->grievance_type_id,
    'grievanceTypeEn' => $this->grievanceType->title_en,
    'grievanceTypeBn' => $this->grievanceType->title_bn,
    'grievance_subject_id' => $this->grievance_subject_id,
    'grievanceSubjectEn' => $this->grievanceSubject->title_en,
    'grievanceSubjectBn' => $this->grievanceSubject->title_bn,

];

    }
}