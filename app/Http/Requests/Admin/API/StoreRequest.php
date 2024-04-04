<?php

namespace App\Http\Requests\Admin\API;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'api_url_id' => 'required|exists:api_urls,id',
            'api_name' => 'required|string|max:255',
            'selected_columns' => 'required|array',
        ];
    }
}