<?php

namespace App\Http\Requests\Admin\System\Office;

use Illuminate\Foundation\Http\FormRequest;

class OfficeRequest extends FormRequest
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
            'division_id'         => 'required|integer|exists:locations,id',
            'district_id'         => 'required|integer|exists:locations,id',
            'thana_id'            => 'required|integer|exists:locations,id',
            'name_en'             => 'required|string|max:50',
            'name_bn'             => 'required|string|max:50',
            'office_type'         => 'required|integer',
            'office_address'      => 'required|string',
            'comment'             => 'required|string|max:120,Null',
            'status'              => 'required|boolean',
        ];
    }
}


