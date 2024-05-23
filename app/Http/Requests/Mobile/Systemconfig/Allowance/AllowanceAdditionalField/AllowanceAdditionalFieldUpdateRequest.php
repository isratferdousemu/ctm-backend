<?php

namespace App\Http\Requests\Mobile\Systemconfig\Allowance\AllowanceAdditionalField;

use Illuminate\Foundation\Http\FormRequest;

class AllowanceAdditionalFieldUpdateRequest extends FormRequest
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
            // ----- Field Value
            'name_en'          => 'required|string|max:50,deleted_at,NULL',
            'name_bn'          => 'required|string|max:50,deleted_at,NULL',
            'type'             => 'required',
            // ----- End Field Value

            // ----- Field Value Validation
            'field_value.*.additional_field_id'      => 'sometimes|string|max:50',
            'field_value.*.value'       => 'sometimes|string|max:50',
            // ----- EndField Value Validation
        ];
    }
}
