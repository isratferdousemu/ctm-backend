<?php

namespace App\Http\Requests\Client\Beneficiary;

use Illuminate\Foundation\Http\FormRequest;

class GetListRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'auth_key' => 'required',
            'auth_secret' => 'required',
            //Get id from Program list API
            'program_id' => 'nullable|integer',
            'nominee_name' => 'nullable',
            'account_number' => 'nullable',
            'nid' => 'nullable',
            'status' => 'nullable',
            'division_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'city_corp_id' => 'nullable|integer',
            'district_pourashava_id' => 'nullable|integer',
            'upazila_id' => 'nullable|integer',
            'pourashava_id' => 'nullable|integer',
            'thana_id' => 'nullable|integer',
            'union_id' => 'nullable|integer',
            'ward_id' => 'nullable|integer',
            'perPage' => 'nullable|integer',
            'page' => 'nullable|integer',
//            'sortBy' => 'nullable',
//            'orderBy' => 'nullable|integer',

        ];
    }
}
