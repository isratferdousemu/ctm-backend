<?php

namespace App\Http\Requests\Admin\Systemconfig\Allowance;

use Illuminate\Foundation\Http\FormRequest;

class AllowanceUpdateRequest extends FormRequest
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
            'name_en'        => 'required|string|max:50',
            'name_bn'        => 'required|string|max:50',
            'is_active'      => 'sometimes',
            'age_limit'      => 'required|array',
            'age_limit.*.gender_id'      => 'required|exists:lookups,id',
            'age_limit.*.min_age'      => 'required|numeric|min:0',
            'age_limit.*.max_age'      => 'required|numeric|min:0',
            'amount.*.type_id'        => 'required|exists:lookups,id',
            'amount.*.amount'        => 'required|numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'age_limit.*.min_age' => 'Minimum age will not use any negative number or character',
            'age_limit.*.max_age' => 'Maximum age will not use any negative number or character',
        ];
    }
}
