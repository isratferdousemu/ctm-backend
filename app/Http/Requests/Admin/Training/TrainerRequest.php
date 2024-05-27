<?php

namespace App\Http\Requests\Admin\Training;

use App\Models\Lookup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TrainerRequest extends FormRequest
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
            'name' => 'required|string',
            'designation_id' => ['nullable', Rule::exists(Lookup::class, 'id')->where('type', 24)],
            'mobile_no' => 'nullable|numeric|regex:/^01[3-9]\d{8}$/',
            'username' => [
                Rule::excludeIf(!$this->is_external),
                'unique:users,username'
            ],
            'email' => [
                Rule::excludeIf(!$this->is_external),
                'email',
                'unique:users,email'
            ],
            'user_id' => [
                Rule::excludeIf($this->is_external == 1),
                'unique:trainers,user_id'
            ],
            'address' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'status' => 'sometimes|in:0,1',
            'is_external' => 'sometimes|boolean'
        ];
    }
}
