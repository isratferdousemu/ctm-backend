<?php

namespace App\Http\Requests\Admin\Beneficiary\Committee;

use Illuminate\Foundation\Http\FormRequest;

class CommitteeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'code'             =>'required|integer|unique:committees,deleted_at,NULL',
            'name'             =>'required|string|max:50,deleted_at,NULL',
            'details'          =>'required|string|max:120,deleted_at,NULL',
            'program_id'       =>'required|integer|exists:allowance_programs,id',
            'division_id'      =>'sometimes|integer|exists:locations,id',
            'district_id'      =>'sometimes|integer|exists:locations,id',
            'upazila_id'      =>'sometimes|integer|exists:locations,id',
            'union_id'      =>'sometimes|integer|exists:locations,id',
            'city_corpo_id'      =>'sometimes|integer|exists:locations,id',
            'thana_id'      =>'sometimes|integer|exists:locations,id',
            'ward_id'      =>'sometimes|integer|exists:locations,id',
            'paurashava_id'      =>'sometimes|integer|exists:locations,id',
            'committee_type'      =>'required|integer|exists:lookups,id',

            'members.*.member_name'      =>'required|string|max:50',
            'members.*.designation_id'       =>'required|integer|exists:lookups,id',
            'members.*.email'             =>'required|email|max:50',
            'members.*.address'           =>'required|string|max:120',
            // phone number must be 11 or 13 digits and start with 01 or 8801
            'members.*.phone'             =>'required|regex:/^(01|8801)[3-9]{1}(\d){8}$/',
            // 'members.*.phone'            =>'required|regex:/^01[3-9]\d{8}$/',



        ];
    }
}
