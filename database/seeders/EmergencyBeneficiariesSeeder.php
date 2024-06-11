<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmergencyBeneficiariesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('emergency_beneficiaries')->insert([
            'id' => 3,
            'program_id' => 1,
            'application_id' => Str::random(10),
            'beneficiary_id' => Str::random(10),
            'name_en' => 'John Doe',
            'name_bn' => 'জন ডো',
            'mother_name_en' => 'Jane Doe',
            'mother_name_bn' => 'জেন ডো',
            'father_name_en' => 'Richard Roe',
            'father_name_bn' => 'রিচার্ড রো',
            'spouse_name_en' => 'Mary Roe',
            'spouse_name_bn' => 'মেরি রো',
            'identification_mark' => 'Scar on left cheek',
            'age' => '30',
            'date_of_birth' => '1994-01-01',
            'nationality' => 'Bangladeshi',
            'gender_id' => 1,
            'education_status' => 'Graduate',
            'profession' => 'Engineer',
            'religion' => 'Islam',
            'marital_status' => 'Married',
            'email' => 'johndoe@example.com',
            'verification_type' => '1',
            'verification_number' => Str::random(10),
            'image' => 'path/to/image.jpg',
            'signature' => 'path/to/signature.jpg',
            'division_id' => 1,
            'district_id' => 1,
            'location_type' => 1,
            'city_corp_id' => 1,
            'district_pourashava_id' => 1,
            'upazila_id' => 1,
            'pourashava_id' => 1,
            'thana_id' => 1,
            'union_id' => 1,
            'ward_id' => 1,
            'post_code' => '1234',
            'address' => '123 Main St',
            'mobile' => '0123456789',
            'p_division_id' => 1,
            'p_district_id' => 1,
            'p_location_type' => 1,
            'p_city_corp_id' => 1,
            'p_district_pourashava_id' => 1,
            'p_upazila_id' => 1,
            'p_pourashava_id' => 1,
            'p_thana_id' => 1,
            'p_union_id' => 1,
            'p_ward_id' => 1,
            'p_post_code' => '5678',
            'p_address' => '456 Another St',
            'p_mobile' => '0987654321',
            'nominee_en' => 'Jane Roe',
            'nominee_bn' => 'জেন রো',
            'nominee_verification_number' => Str::random(10),
            'nominee_address' => '789 Different St',
            'nominee_image' => 'path/to/nominee_image.jpg',
            'nominee_signature' => 'path/to/nominee_signature.jpg',
            'nominee_relation_with_beneficiary' => 'Sister',
            'nominee_nationality' => 'Bangladeshi',
            'nominee_date_of_birth' => '1990-01-01',
            'account_name' => 'John Doe',
            'account_number' => '0123456789',
            'account_owner' => 'John Doe',
            'current_location_type_id' => 1,
            'permanent_location_type_id' => 1,
            'account_type' => 1,
            'bank_name' => 'Some Bank',
            'branch_name' => 'Main Branch',
            'delete_cause' => null,
            'monthly_allowance' => 5000.00,
            'status' => '1',
            'score' => 85,
            'remarks' => 'Sample data',
            'deleted_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
