<?php

namespace App\Http\Requests\Admin\API;

use Illuminate\Foundation\Http\FormRequest;

class ApiListRequest extends FormRequest
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
            'table' => 'required',
            'name' => 'required|string|max:255',
            'selected_columns' => 'required|array',
        ];
    }
}
