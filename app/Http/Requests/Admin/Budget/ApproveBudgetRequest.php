<?php

namespace App\Http\Requests\Admin\Budget;

use Illuminate\Foundation\Http\FormRequest;

class ApproveBudgetRequest extends FormRequest
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
//            'approved_by' => 'required|string|max:100',
            'approved_document' => 'nullable|mimes:jpeg,jpg,pdf|max:2048',
            'approved_remarks' => 'nullable',
        ];
    }
}
