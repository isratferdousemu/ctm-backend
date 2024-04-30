<?php

namespace App\Http\Requests\Client\Beneficiary;

use Illuminate\Foundation\Http\FormRequest;

class NomineeRequest extends FormRequest
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
            //Authentication key
            'auth_key' => 'required|string',
            'auth_secret' => 'required|string',
            'nominee_en' => 'nullable|string',
            'nominee_bn' => 'nullable|string',
            'nominee_verification_number' => 'nullable',
            'nominee_address' => 'nullable|string',
//            'nominee_image' => 'nullable|mimes:jpeg,jpg,png|max:2048',
//            'nominee_signature' => 'nullable|mimes:jpeg,jpg,png|max:2048',
            'nominee_relation_with_beneficiary' => 'nullable|string',
            'nominee_nationality' => 'nullable|string',
        ];
    }
}
