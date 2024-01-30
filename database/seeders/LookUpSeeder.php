<?php

namespace Database\Seeders;

use App\Models\Lookup;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LookUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lookups = [
            ['id'=>1,'type' => 1, 'value_en' => 'District Pouroshava','value_bn' => 'জেলা পৌরসভা','version'=>1,'default'=>1],
            ['id'=>2,'type' => 1, 'value_en' => 'Upazila','value_bn' => 'উপজেলা','version'=>2,'default'=>1],
            ['id'=>3,'type' => 1, 'value_en' => 'City Corporation','value_bn' => 'সিটি কর্পোরেশন','version'=>3,'default'=>1],
            ['id' => 4, 'type' => 3, 'value_en' => 'Ministry', 'value_bn' => 'Ministry','default'=>1],
            ['id' => 5, 'type' => 3, 'value_en' => 'Head Office', 'value_bn' => 'Head Office','default'=>1],
            ['id' => 6, 'type' => 3, 'value_en' => 'Division', 'value_bn' => 'Division','default'=>1],
            ['id' => 7, 'type' => 3, 'value_en' => 'District', 'value_bn' => 'District','default'=>1],
            ['id' => 8, 'type' => 3, 'value_en' => 'Upazila', 'value_bn' => 'Upazila','default'=>1],
            ['id' => 9, 'type' => 3, 'value_en' => 'UCD (City Corporation)', 'value_bn' => 'UCD (City Corporation)','default'=>1],
            ['id' => 10, 'type' => 3, 'value_en' => 'UCD (Upazila)', 'value_bn' => 'UCD (Upazila)','default'=>1],
            ['id' => 11, 'type' => 3, 'value_en' => 'Circle Social Service', 'value_bn' => 'Circle Social Service','default'=>1],
            ['id' => 12, 'type' => 17, 'value_en' => 'Union Committee', 'value_bn' => 'Union Committee','default'=>1],
            ['id' => 13, 'type' => 17, 'value_en' => 'Ward Committee', 'value_bn' => 'Ward Committee','default'=>1],
            ['id' => 14, 'type' => 17, 'value_en' => 'Upazila Committee', 'value_bn' => 'Upazila Committee','default'=>1],
            ['id' => 15, 'type' => 17, 'value_en' => 'City Corporation Committee', 'value_bn' => 'City Corporation Committee','default'=>1],
            ['id' => 16, 'type' => 17, 'value_en' => 'District Paurashava Committee', 'value_bn' => 'District Paurashava Committee','default'=>1],
            ['id' => 17, 'type' => 17, 'value_en' => 'District Committee', 'value_bn' => 'District Committee','default'=>1],
            ['id' => 18, 'type' => 17, 'value_en' => 'Coordination and Monitoring Committee', 'value_bn' => 'Coordination and Monitoring Committee','default'=>1],
            ['id' => 19, 'type' => 17, 'value_en' => 'National Steering Committee', 'value_bn' => 'National Steering Committee','default'=>1],
            ['id' => 20, 'type' => 18, 'value_en' => 'President', 'value_bn' => 'President','default'=>1],
            ['id' => 21, 'type' => 18, 'value_en' => 'Vice President', 'value_bn' => 'Vice President','default'=>1],
            ['id' => 22, 'type' => 18, 'value_en' => 'Member', 'value_bn' => 'Member','default'=>1],

            ///GENDER Seeder
            ['id' => 23, 'type' => 2, 'value_en' => 'Male', 'value_bn' => 'পুরুষ','default'=>1],
            ['id' => 24, 'type' => 2, 'value_en' => 'Female', 'value_bn' => 'মহিলা','default'=>1],

            ///CLASS Seeder
            ['id' => 25, 'type' => 20, 'value_en' => '1', 'value_bn' => 'এক','default'=>1],
            ['id' => 26, 'type' => 20, 'value_en' => '2', 'value_bn' => 'দুই','default'=>1],
            ['id' => 27, 'type' => 20, 'value_en' => '3', 'value_bn' => 'তিন','default'=>1],
            ['id' => 28, 'type' => 20, 'value_en' => '4', 'value_bn' => 'চার','default'=>1],
            ['id' => 29, 'type' => 20, 'value_en' => '5', 'value_bn' => 'পাঁচ','default'=>1],
            ['id' => 30, 'type' => 20, 'value_en' => '6', 'value_bn' => 'ছয়','default'=>1],
            ['id' => 31, 'type' => 20, 'value_en' => '7', 'value_bn' => 'সাত','default'=>1],
            ['id' => 32, 'type' => 20, 'value_en' => '8', 'value_bn' => 'আট','default'=>1],
            ['id' => 33, 'type' => 20, 'value_en' => '9', 'value_bn' => 'নয়','default'=>1],
            ['id' => 34, 'type' => 20, 'value_en' => '10', 'value_bn' => 'দশ','default'=>1],
            //office Type
            ['id' => 35, 'type' => 3, 'value_en' => 'UCD (Dist Paurashava)', 'value_bn' => 'UCD (Dist Paurashava)','default'=>1],

            // Beneficiary replace reasons
            ['id' => 36, 'type' => 21, 'value_en' => 'Death', 'value_bn' => 'মৃত্যু','default'=>1],
            ['id' => 37, 'type' => 21, 'value_en' => 'Program Switch', 'value_bn' => 'প্রোগ্রাম পরিবর্তন','default'=>1],
            ['id' => 38, 'type' => 21, 'value_en' => 'Missing', 'value_bn' => 'অনুপস্থিত','default'=>1],

            // Beneficiary shifting reasons
            ['id' => 39, 'type' => 22, 'value_en' => 'Death', 'value_bn' => 'মৃত্যু','default'=>1],
            ['id' => 40, 'type' => 22, 'value_en' => 'Financially Independent', 'value_bn' => 'আর্থিকভাবে স্বচ্ছল','default'=>1],
            ['id' => 41, 'type' => 22, 'value_en' => 'Others', 'value_bn' => 'অন্যান্য','default'=>1],

        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('lookups')->truncate();
        foreach ($lookups as $value) {
            $lookup = new Lookup;
            $lookup->id                   = $value['id'];
            $lookup->type                   = $value['type'];
            $lookup->value_en               = $value['value_en'];
            $lookup->value_bn               = $value['value_bn'];
            $lookup->default               = $value['default'];
            $lookup ->save();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
