<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AdditionalFields;
use App\Models\AdditionalFieldValues;

class AditionalFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $addition_field = [
            ['id' => '1', 'name_en' => 'Yearly Income', 'name_bn' => 'বার্ষিক আয়','type' => 'dropdown'],
            ['id' => '2', 'name_en' => 'Govt/Private Beneficiary Details', 'name_bn' => 'সরকারি/বেসরকারি সুবিধাভোগীর বিবরণ','type' => 'dropdown'],
            ['id' => '3','name_en' => 'Total No. of Family Member', 'name_bn' => 'পরিবারের মোট সদস্য সংখ্যা','type' => 'number'],
            ['id' => '4','name_en' => 'No. of Male', 'name_bn' => 'পুরুষ সদস্য সংখ্যা','type' => 'number'],
            ['id' => '5','name_en' => 'No. of. Female', 'name_bn' => 'নারী সদস্য সংখ্যা','type' => 'number'],

            ['id' => '6','name_en' => 'No. of. Children', 'name_bn' => 'শিশু সদস্সের সংখ্যা ','type' => 'number'],
            ['id' => '7', 'name_en' => 'Health Status', 'name_bn' => 'স্বাস্থ্য অবস্থা','type' => 'checkbox'],
            ['id' => '8', 'name_en' => 'Financial Status', 'name_bn' => 'আর্থিক অবস্থা','type' => 'dropdown'],
            ['id' => '9','name_en' => 'Social Status', 'name_bn' => 'সামাজিক অবস্থা','type' => 'dropdown'],
            ['id' => '10','name_en' => 'Land Ownership', 'name_bn' => 'ভুমির মালিকানা','type' => 'text'],
            ['id' => '11','name_en' => 'DIS No.', 'name_bn' => 'ভুমির মালিকানা','type' => 'number'],
            ['id' => '12','name_en' => 'Disability type', 'name_bn' => 'অক্ষমতার ধরন','type' => 'Disabled'],
            ['id' => '13','name_en' => 'Disability Type According
to DIS', 'name_bn' => 'ডি আই এস অনুযায়ী   অক্ষমতার ধরণ','type' => 'dropdown'],
            ['id' => '14','name_en' => 'Disability Level According to DIS', 'name_bn' => 'ডিআইএস অনুযায়ী প্রতিবন্ধী স্তর','type' => 'dropdown'],
            ['id' => '15','name_en' => 'Upload', 'name_bn' => 'আপলোড','type' => 'File'],
            ['id' => '16','name_en' => 'Garden Worker ID', 'name_bn' => 'বাগান শ্রমিক আইডি','type' => 'number'],
            ['id' => '17','name_en' => 'Tea Garden Name', 'name_bn' => 'চা বাগানের নাম','type' => 'text'],
             ['id' => '18','name_en' => 'Date of Enrollment in Garden', 'name_bn' => 'বাগানে তালিকাভুক্তির তারিখ','type' => 'date'],
             ['id' => '19','name_en' => 'Guardian Name', 'name_bn' => 'অভিভাবকের নাম','type' => 'text'],
             ['id' => '20','name_en' => 'Name of the Institute', 'name_bn' => 'প্রতিষ্ঠানের নাম','type' => 'text'],
             ['id' => '21','name_en' => 'Class', 'name_bn' => 'শ্রেণী','type' => 'dropdown'],

        ];
        foreach ($addition_field as $value) {
            $addition_field = new AdditionalFields;
            $addition_field->id                                 = $value['id'];
            $addition_field->name_en                            = $value['name_en'];
            $addition_field->name_bn                            = $value['name_bn'];
            $addition_field->type                               = $value['type'];
            $addition_field ->save();
        }
         $addition_value = [
            ['additional_field_id' => 1, 'value' => 10000],
            ['additional_field_id' => 1, 'value' => 20000],
            ['additional_field_id' => 1, 'value' => 30000],
            ['additional_field_id' => 1, 'value' => 40000],
            ['additional_field_id' => 1, 'value' => 50000],
            ['additional_field_id' => 1, 'value' => 60000],
            ['additional_field_id' => 1, 'value' => 70000],
            ['additional_field_id' => 1, 'value' => 80000],
            ['additional_field_id' => 1, 'value' => 'above'],
            ['additional_field_id' => 2, 'value' => 'Old Age Allowance Program'],
            ['additional_field_id' => 2, 'value' => 'Disability Allowance Program'],
            ['additional_field_id' => 2, 'value' => 'Widow And Husband Adopted Allowance program'],
            ['additional_field_id' => 2, 'value' => 'Freedom Fighter Honorary Allowance'],
            ['additional_field_id' => 2, 'value' => 'No Allowance'],
            ['additional_field_id' => 2, 'value' => 'Other (specify)'],
            ['additional_field_id' => 7, 'value' => 'Totally Disabled'],
            ['additional_field_id' => 7, 'value' => 'Sick'],
            ['additional_field_id' => 7, 'value' => 'Insane'],
            ['additional_field_id' => 7, 'value' => 'Disabled'],
            ['additional_field_id' => 7, 'value' => 'Partially Powerless'],
            ['additional_field_id' => 8, 'value' => 'Poor'],
            ['additional_field_id' => 8, 'value' => 'Refugee'],
            ['additional_field_id' => 8, 'value' => 'Landless'],
            ['additional_field_id' => 9, 'value' => 'Widow'],
            ['additional_field_id' => 9, 'value' => 'Divorced'],
            ['additional_field_id' => 9, 'value' => 'Widower'],
            ['additional_field_id' => 9, 'value' => 'Seperated From Family'],
            ['additional_field_id' => 10, 'value' => ''],
            ['additional_field_id' => 11, 'value' => ''],
            ['additional_field_id' => 12, 'value' => ''],
            ['additional_field_id' =>13, 'value' => 'Autism'],
            ['additional_field_id' =>13, 'value' => 'physical Disability'],
            ['additional_field_id' =>13, 'value' => 'Mental Illnes Disability'],
            ['additional_field_id' =>13, 'value' => 'visual Disability'],
            ['additional_field_id' =>13, 'value' => 'Speech Disability'],
            ['additional_field_id' =>13, 'value' => 'Intelelctual Disability'],
            ['additional_field_id' =>13, 'value' => 'Hearing Disability'],
            ['additional_field_id' =>13, 'value' => 'Down Syndrom'],
            ['additional_field_id' =>14, 'value' => 'Mid'],
            ['additional_field_id' =>14, 'value' => 'Moderate'],
            ['additional_field_id' =>14, 'value' => 'Severe'],
            ['additional_field_id' =>15, 'value' => 'File'],
            ['additional_field_id' =>16, 'value' => ''],
            ['additional_field_id' =>17, 'value' => ''],
            ['additional_field_id' =>18, 'value' => ''],
            ['additional_field_id'=>19, 'value' => ''],
            ['additional_field_id'=>20, 'value' => ''],
            ['additional_field_id'=>21, 'value' => ''],







        ];
        foreach ($addition_value as $value) {
            $addition_value = new AdditionalFieldValues;

            $addition_value->additional_field_id                   = $value['additional_field_id'];
            $addition_value->value                                 = $value['value'];

            $addition_value ->save();
        }

    }
}
