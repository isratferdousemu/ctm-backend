<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Beneficiary>
 */
class BeneficiaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();
        $mother_name = fake()->name('Female');
        $father_name = fake()->name('Male');
        $spouse_name = fake()->name();
        $age = fake()->numberBetween(45, 70);
        $date_of_birth = now()->subYears($age);
        $division_id = 6;
        $district_id = 55;
        $city_corp_id = null;
        $district_pourashava_id = null;
        $upazila_id = 469;
        $pourashava_id = null;
        $thana_id = null;
        $union_id = 3871;
        $ward_id = null;
        $post_code = fake()->postcode();
        $address = fake()->streetAddress();
        $mobile = '01816345678';
        $nominee = fake()->name();

        return [
            'program_id' => fake()->numberBetween(1, 4),
//            'application_table_id' => 1,
            'application_id' => fake()->unique()->numberBetween(1001,9999),
            'name_en' => $name,
            'name_bn' => $name,
            'mother_name_en' => $mother_name,
            'mother_name_bn' => $mother_name,
            'father_name_en' => $father_name,
            'father_name_bn' => $father_name,
            'spouse_name_en' => $spouse_name,
            'spouse_name_bn' => $spouse_name,
            'identification_mark' => 'beauty spot on left cheek',
            'age' => $age,
            'date_of_birth' => $date_of_birth,
            'nationality' => 'Bangladeshi',
            'gender_id' => rand(23, 24),
            'education_status' => 'Self educated',
            'profession' => 'Firming',
            'religion' => 'Islam',
            'marital_status' => 'Married',
            'email' => fake()->safeEmail(),
            'mobile' => $mobile,
            'verification_type' => rand(1,2),
            'verification_number' => fake()->isbn10(),
            'image' => fake()->imageUrl(300,300),
            'signature' => fake()->imageUrl(300,100),

            'current_division_id' => $division_id,
            'current_district_id' => $district_id,
            'current_city_corp_id' => $city_corp_id,
            'current_district_pourashava_id' => $district_pourashava_id,
            'current_upazila_id' => $upazila_id,
            'current_pourashava_id' => $pourashava_id,
            'current_thana_id' => $thana_id,
            'current_union_id' => $union_id,
            'current_ward_id' => $ward_id,
            'current_post_code' => $post_code,
            'current_address' => $address,

            'permanent_division_id' => $division_id,
            'permanent_district_id' => $district_id,
            'permanent_city_corp_id' => $city_corp_id,
            'permanent_district_pourashava_id' => $district_pourashava_id,
            'permanent_upazila_id' => $upazila_id,
            'permanent_pourashava_id' => $pourashava_id,
            'permanent_thana_id' => $thana_id,
            'permanent_union_id' => $union_id,
            'permanent_ward_id' => $ward_id,
            'permanent_post_code' => $post_code,
            'permanent_address' => $address,

            'nominee_en' => $nominee,
            'nominee_bn' => $nominee,
            'nominee_verification_number' => '1234',
            'nominee_address' => $address,
            'nominee_image' => fake()->imageUrl(300,300),
            'nominee_signature' => fake()->imageUrl(300,100),
            'nominee_relation_with_beneficiary' => 'Son',
            'nominee_nationality' => 'Bangladeshi',

            'account_name' => $name,
            'account_number' => fake()->isbn13(),
            'account_owner' => $name,
            'status' => 1,
            'score' => fake()->numberBetween(50, 100),
        ];
    }
}