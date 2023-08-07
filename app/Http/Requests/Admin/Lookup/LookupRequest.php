<?php

namespace App\Http\Requests\Admin\Lookup;

use Illuminate\Foundation\Http\FormRequest;

class LookupRequest extends FormRequest
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
            'type'             => 'required|integer|unique:lookups',
            'value_en'         => 'required|string|max:50',
            'value_bn'         => 'required|string|max:50',
            'keyword'          => 'required|string|max:120,Null',
        ];
    }
}
