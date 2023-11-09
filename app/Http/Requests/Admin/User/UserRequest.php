<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'full_name'                     => 'required|string|max:50',
            'username'                     => 'required|string|unique:users,username,deleted_at',
            'mobile'                     => 'required|numeric|unique:users,mobile|regex:/^01[3-9]\d{8}$/',
            'email'                     => 'required|email|unique:users,email,deleted_at',
            'status' => 'sometimes|integer|in:0,1',
            'role_id' => 'required|array|exists:roles,id',
            'office_type'                     => 'required|integer|exists:lookups,id',
            'office_id'                     => 'required|integer|exists:offices,id',
            'division_id' => 'sometimes|required_unless:division_id,null,id,deleted_at,NULL',
            'district_id' => 'sometimes|required_unless:district_id,exists:locations,id,deleted_at,NULL',
            'thana_id' => 'sometimes|required_unless:thana_id,exists:locations,id,deleted_at,NULL',
            'city_corpo_id' => 'sometimes|required_unless:city_corpo_id,exists:locations,id,deleted_at,NULL',
        ];
    }
}
