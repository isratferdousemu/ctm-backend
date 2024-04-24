<?php

namespace App\Http\Requests\Admin\Budget;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBudgetRequest extends FormRequest
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
            'program_id' => [
                'required',
                'exists:allowance_programs,id',
                Rule::unique('budgets')->ignore($this->id)->where(fn(Builder $query) => $query->where('program_id', $this->input('program_id'))
                    ->where('financial_year_id', $this->input('financial_year_id'))
                )
            ],
            'financial_year_id' => 'required|integer|exists:financial_years,id',
            'calculation_type' => 'required|integer|exists:lookups,id',
            'previous_year_value' => 'nullable|numeric',
            'calculation_value' => 'required|numeric',
            'remarks' => 'nullable|max:255'
        ];
    }
}
