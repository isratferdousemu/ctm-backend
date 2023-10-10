<?php

namespace App\Http\Requests\Admin\Systemconfig\Allowance;

use Illuminate\Foundation\Http\FormRequest;

class AllowanceRequest extends FormRequest
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
            'name_bn'        => 'required|string|max:50'
        ];
    }
}
