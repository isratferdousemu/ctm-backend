<?php

namespace App\Http\Requests\Admin\Geographic\Division;

use Illuminate\Foundation\Http\FormRequest;

class DivisionRequest extends FormRequest
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
            'name_en'                              => 'required|string|max:50|unique:locations,name_en,NULL,id,deleted_at,NULL',
        'name_bn'                              => 'required|string|max:50|unique:locations,name_bn,NULL,id,deleted_at,NULL',
        'code'                               => 'required|string|max:6|unique:locations,code,NULL,id,deleted_at,NULL',
        ];
    }
}
