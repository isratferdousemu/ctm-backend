<?php

namespace App\Http\Requests\Admin\Menu;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
            'page_link_id' => 'required|integer|exists:permissions,id,deleted_at,NULL',
            'parent_id' => 'required|integer|exists:menus,id,deleted_at,NULL',
            'label_name_en' => 'required|string|max:50|unique:menus,label_name_en,NULL,id,deleted_at,NULL',
            'label_name_bn' => 'required|string|max:50|unique:menus,label_name_bn,NULL,id,deleted_at,NULL',
            'order' => 'required|string|max:6|unique:menus,order,NULL,id,deleted_at,NULL'
        ];
    }
}