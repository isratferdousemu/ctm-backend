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
            'division_id'      =>'required|integer|exists:locations,id',
            'district_id'      =>'required|integer|exists:locations,id',
            'office_id'        =>'required|integer|exists:offices,id',

            'members.*.member_name'      =>'required|string|max:50',
            'members.*.designation'       =>'required|string|max:50',
            'members.*.email'             =>'required|email|max:50',
            'members.*.address'           =>'required|string|max:120',

            'members.*.phone'            =>'required|regex:/^8801[3-9]\d{8}$/',

        ];
    }
}
