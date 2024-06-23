<?php

namespace App\Http\Requests\Admin\Emergency;

use App\Rules\UniqueEmergencyBeneficiaryNumber;
use App\Rules\UniqueEmergencyBeneficiaryVerificationNumber;
use Illuminate\Foundation\Http\FormRequest;

class EmergencyBeneficiaryRequest extends FormRequest
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
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'emergency_allotment_id' => 'required|exists:emergency_allotments,id',
                    'verification_type' => 'required|in:1,2',
                    'verification_number' => [
                        'required',
                        new UniqueEmergencyBeneficiaryNumber(),
                        new UniqueEmergencyBeneficiaryVerificationNumber(),

                    ],
                    'age' => 'required',
                    'date_of_birth' => 'required|date',
                    'name_en' => 'required',
                    'name_bn' => 'required',
                    'father_name_en' => 'required',
                    'father_name_bn' => 'required',
                    'mother_name_en' => 'required',
                    'mother_name_bn' => 'required',
                    // 'spouse_name_en'               =>'required',
                    // 'spouse_name_bn'               =>'required',
                    'identification_mark' => 'sometimes',
                    //'image'                        =>'sometimes|mimes:jpeg,jpg,png|max:2048',
                    //'signature'                        =>'sometimes|mimes:jpeg,jpg,png|max:2048',
                    //'nationality'                   =>'required',
                    'gender_id' => 'required|exists:lookups,id',
                    'education_status' => 'required',
                    'profession' => 'required',
                    'religion' => 'required',
                    'division_id' => 'required|exists:locations,id',
                    'district_id' => 'required|exists:locations,id',
                    'upazila' => 'sometimes|exists:locations,id',
                    'post_code' => 'required',
                    'address' => 'required',
                    'location_type' => 'required|exists:lookups,id',
                    'thana_id' => 'sometimes|exists:locations,id',
                    'union_id' => 'sometimes|exists:locations,id',
                    'city_id' => 'sometimes|exists:locations,id',
                    'city_thana_id' => 'sometimes|exists:locations,id',
                    'district_pouro_id' => 'sometimes|exists:locations,id',
                    'mobile' => 'required',
                    //     'mobile' => [
                    //     'required',
                    //     new UniqueMobileNumber(),
                    // ],
                    'permanent_division_id' => 'required|exists:locations,id',
                    'permanent_district_id' => 'required|exists:locations,id',
                    'permanent_upazila' => 'sometimes|exists:locations,id',
                    'permanent_post_code' => 'required',
                    'permanent_address' => 'required',
                    'permanent_location_type' => 'required|exists:lookups,id',
                    'permanent_thana_id' => 'sometimes|exists:locations,id',
                    'permanent_union_id' => 'sometimes|exists:locations,id',
                    'permanent_city_id' => 'sometimes|exists:locations,id',
                    'permanent_city_thana_id' => 'sometimes|exists:locations,id',
                    'permanent_district_pouro_id' => 'sometimes|exists:locations,id',
                    'nominee_en' => 'required',
                    'nominee_bn' => 'required',
                    'nominee_verification_number' => 'required',
                    'nominee_address' => 'required',
                    //'nominee_image'              =>'required|mimes:jpeg,jpg,png|max:2048',
                    //'nominee_signature'              =>'required|mimes:jpeg,jpg,png|max:2048',
                    'nominee_relation_with_beneficiary' => 'required',
                    'nominee_nationality' => 'required',
                    // 'account_name'              =>'required',
                    // 'account_owner'              =>'required',
                    // 'account_number'              =>'required',
                    'marital_status' => 'required',
                    'email' => 'email',
                ];
            }
            case 'PUT':
            {
                return [
                    'nominee_en' => 'required',
                    'nominee_bn' => 'required',
                    'nominee_verification_number' => 'required|unique:emergency_beneficiaries,verification_number,' . $this->route('id'),
                    'nominee_nationality' => 'required',
                    'nominee_relation_with_beneficiary' => 'required',
                    'nominee_date_of_birth' => 'required|date',
                    'nominee_address' => 'required',
                    'account_name' => 'required',
                    'account_number' => 'required',
                    'account_owner' => 'required',
                    'account_type' => 'required',
                    'bank_name' => 'required',
                    'branch_name' => 'required',
//                    'nominee_image' => 'nullable|image|mimes:jpeg,png,jpg',
//                    'nominee_signature' => 'nullable|image|mimes:jpeg,png,jpg',

                ];
            }
            default:
                break;
        }
        return [];

    }


    /**
     * Get the custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // Custom error messages
            'nominee_en.required' => 'The nominee English name is required.',
            'nominee_bn.required' => 'The nominee Bangla name is required.',
            'nominee_verification_number.required' => 'The nominee verification number is required.',
            'nominee_verification_number.unique' => 'The nominee verification number must be unique.',
            'nominee_nationality.required' => 'The nominee nationality is required.',
            'nominee_relation_with_beneficiary.required' => 'The relation with the beneficiary is required.',
            'nominee_date_of_birth.required' => 'The nominee date of birth is required.',
            'nominee_address.required' => 'The nominee address is required.',
            'account_name.required' => 'The account name is required.',
            'account_number.required' => 'The account number is required.',
            'account_owner.required' => 'The account owner is required.',
            'account_type.required' => 'The account type is required.',
            'bank_name.required' => 'The bank name is required.',
            'branch_name.required' => 'The branch name is required.',
            'nominee_image.image' => 'The nominee image must be an image file.',
            'nominee_signature.image' => 'The nominee signature must be an image file.'
        ];
    }
}
