<?php

namespace Database\Seeders;

use App\Constants\TrainingLookUp;
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
            ['id' => 1, 'type' => 1, 'value_en' => 'District Pouroshava', 'value_bn' => 'জেলা পৌরসভা', 'version' => 1, 'default' => 1],
            ['id' => 2, 'type' => 1, 'value_en' => 'Upazila', 'value_bn' => 'উপজেলা', 'version' => 2, 'default' => 1],
            ['id' => 3, 'type' => 1, 'value_en' => 'City Corporation', 'value_bn' => 'সিটি কর্পোরেশন', 'version' => 3, 'default' => 1],
            ['id' => 4, 'type' => 3, 'value_en' => 'Ministry', 'value_bn' => 'Ministry', 'default' => 1],
            ['id' => 5, 'type' => 3, 'value_en' => 'Head Office', 'value_bn' => 'Head Office', 'default' => 1],
            ['id' => 6, 'type' => 3, 'value_en' => 'Division', 'value_bn' => 'Division', 'default' => 1],
            ['id' => 7, 'type' => 3, 'value_en' => 'District', 'value_bn' => 'District', 'default' => 1],
            ['id' => 8, 'type' => 3, 'value_en' => 'Upazila', 'value_bn' => 'Upazila', 'default' => 1],
            ['id' => 9, 'type' => 3, 'value_en' => 'UCD (City Corporation)', 'value_bn' => 'UCD (City Corporation)', 'default' => 1],
            ['id' => 10, 'type' => 3, 'value_en' => 'UCD (Upazila)', 'value_bn' => 'UCD (Upazila)', 'default' => 1],
            ['id' => 11, 'type' => 3, 'value_en' => 'Circle Social Service', 'value_bn' => 'Circle Social Service', 'default' => 1],
            ['id' => 12, 'type' => 17, 'value_en' => 'Union Committee', 'value_bn' => 'Union Committee', 'default' => 1],
            ['id' => 13, 'type' => 17, 'value_en' => 'Ward Committee', 'value_bn' => 'Ward Committee', 'default' => 1],
            ['id' => 14, 'type' => 17, 'value_en' => 'Upazila Committee', 'value_bn' => 'Upazila Committee', 'default' => 1],
            ['id' => 15, 'type' => 17, 'value_en' => 'City Corporation Committee', 'value_bn' => 'City Corporation Committee', 'default' => 1],
            ['id' => 16, 'type' => 17, 'value_en' => 'District Paurashava Committee', 'value_bn' => 'District Paurashava Committee', 'default' => 1],
            ['id' => 17, 'type' => 17, 'value_en' => 'District Committee', 'value_bn' => 'District Committee', 'default' => 1],
            ['id' => 18, 'type' => 17, 'value_en' => 'Coordination and Monitoring Committee', 'value_bn' => 'Coordination and Monitoring Committee', 'default' => 1],
            ['id' => 19, 'type' => 17, 'value_en' => 'National Steering Committee', 'value_bn' => 'National Steering Committee', 'default' => 1],
            ['id' => 20, 'type' => 18, 'value_en' => 'President', 'value_bn' => 'President', 'default' => 1],
            ['id' => 21, 'type' => 18, 'value_en' => 'Vice President', 'value_bn' => 'Vice President', 'default' => 1],
            ['id' => 22, 'type' => 18, 'value_en' => 'Member', 'value_bn' => 'Member', 'default' => 1],

            ///GENDER Seeder
            ['id' => 23, 'type' => 2, 'value_en' => 'Male', 'value_bn' => 'পুরুষ', 'default' => 1],
            ['id' => 24, 'type' => 2, 'value_en' => 'Female', 'value_bn' => 'মহিলা', 'default' => 1],

            ///CLASS Seeder
            ['id' => 25, 'type' => 20, 'value_en' => 'Class One', 'value_bn' => 'প্রথম শ্রেণী', 'default' => 1],
            ['id' => 26, 'type' => 20, 'value_en' => 'Class Two', 'value_bn' => 'দ্বিতীয় শ্রেণী', 'default' => 1],
            ['id' => 27, 'type' => 20, 'value_en' => 'Class Three', 'value_bn' => 'তৃতীয় শ্রেণী', 'default' => 1],
            ['id' => 28, 'type' => 20, 'value_en' => 'Class Four', 'value_bn' => 'চতুর্থ শ্রেণী', 'default' => 1],
            ['id' => 29, 'type' => 20, 'value_en' => 'Class Five', 'value_bn' => 'পঞ্চম শ্রেণী', 'default' => 1],
            ['id' => 30, 'type' => 20, 'value_en' => 'Class Six', 'value_bn' => 'ষষ্ঠ শ্রেণী', 'default' => 1],
            ['id' => 31, 'type' => 20, 'value_en' => 'Class Seven', 'value_bn' => 'সপ্তম শ্রেণী', 'default' => 1],
            ['id' => 32, 'type' => 20, 'value_en' => 'Class Eight', 'value_bn' => 'অষ্টম শ্রেণী', 'default' => 1],
            ['id' => 33, 'type' => 20, 'value_en' => 'Class Nine', 'value_bn' => 'নবম শ্রেণী', 'default' => 1],
            ['id' => 34, 'type' => 20, 'value_en' => 'Class Ten', 'value_bn' => 'দশম শ্রেণী', 'default' => 1],
            //office Type
            ['id' => 35, 'type' => 3, 'value_en' => 'UCD (Dist Paurashava)', 'value_bn' => 'UCD (Dist Paurashava)', 'default' => 1],

            // Beneficiary replace reasons
            ['id' => 36, 'type' => 21, 'value_en' => 'Death', 'value_bn' => 'মৃত্যু', 'default' => 1],
            ['id' => 37, 'type' => 21, 'value_en' => 'Program Switch', 'value_bn' => 'প্রোগ্রাম পরিবর্তন', 'default' => 1],
            ['id' => 38, 'type' => 21, 'value_en' => 'Missing', 'value_bn' => 'অনুপস্থিত', 'default' => 1],

            // Beneficiary exit reasons
            ['id' => 39, 'type' => 22, 'value_en' => 'Death', 'value_bn' => 'মৃত্যু', 'default' => 1],
            ['id' => 40, 'type' => 22, 'value_en' => 'Financially Independent', 'value_bn' => 'আর্থিকভাবে স্বচ্ছল', 'default' => 1],
            ['id' => 41, 'type' => 22, 'value_en' => 'Others', 'value_bn' => 'অন্যান্য', 'default' => 1],


            ['id' => 42, 'type' => 20, 'value_en' => 'Others', 'value_bn' => 'অনন্য', 'default' => 1],
            ['id' => 43, 'type' => 18, 'value_en' => 'Secretary', 'value_bn' => 'সচিব', 'default' => 1],

            // Calculation Type
            ['id' => 44, 'type' => 23, 'value_en' => 'Percentage of Amount', 'value_bn' => 'শতকরা পরিমাণ অনুসারে', 'keyword' => 'PERCENTAGE_OF_AMOUNT', 'default' => 1],
            ['id' => 45, 'type' => 23, 'value_en' => 'Fixed Amount', 'value_bn' => 'নির্দিষ্ট পরিমাণ অনুসারে', 'keyword' => 'FIXED_AMOUNT', 'default' => 1],
            ['id' => 46, 'type' => 23, 'value_en' => 'Percentage of Beneficiary', 'value_bn' => 'উপকারভোগীর শতাংশ অনুসারে', 'keyword' => 'PERCENTAGE_OF_BENEFICIARY', 'default' => 1],
            ['id' => 47, 'type' => 23, 'value_en' => 'Fixed Beneficiary', 'value_bn' => 'স্থায়ী উপকারভোগী অনুসারে', 'keyword' => 'FIXED_BENEFICIARY', 'default' => 1],
            ['id' => 48, 'type' => 23, 'value_en' => 'By Application', 'value_bn' => 'আবেদন অনুসারে', 'keyword' => 'BY_APPLICATION', 'default' => 1],
            ['id' => 49, 'type' => 23, 'value_en' => 'By Poverty Score', 'value_bn' => 'দারিদ্র্য সূচক অনুসারে', 'keyword' => 'BY_POVERTY_SCORE', 'default' => 1],
            ['id' => 50, 'type' => 23, 'value_en' => 'By Population', 'value_bn' => 'জনসংখ্যা অনুসারে', 'keyword' => 'BY_POPULATION', 'default' => 1],

            //Trainer Designation
            ['id' => 51, 'type' => 24, 'value_en' => 'Senior trainer', 'value_bn' => 'ঊর্ধ্বতন প্রশিক্ষক', 'default' => 1],
            ['id' => 52, 'type' => 24, 'value_en' => 'Trainer', 'value_bn' => 'প্রশিক্ষক', 'default' => 1],


            // Grievance Solution type
            ['id' => 53, 'type' => 25, 'value_en' => 'Allegation have no prove or Truth', 'value_bn' => 'অভিযোগের কোনো প্রমাণ বা সত্যতা নেই', 'default' => 1],
            ['id' => 54, 'type' => 25, 'value_en' => 'Dispatch to relative office', 'value_bn' => 'আত্মীয় অফিসে প্রেরণ', 'default' => 1],
            ['id' => 55, 'type' => 25, 'value_en' => 'Solution Type -3', 'value_bn' => 'সমাধানের ধরন-3', 'default' => 1],
            ['id' => 56, 'type' => 25, 'value_en' => 'Solution Type -4', 'value_bn' => 'সমাধানের ধরন-4', 'default' => 1],
            ['id' => 57, 'type' => 25, 'value_en' => 'Solution Type -5', 'value_bn' => 'সমাধানের ধরন-5', 'default' => 1],
            ['id' => 58, 'type' => 25, 'value_en' => 'Solution Type -6', 'value_bn' => 'সমাধানের ধরন-6', 'default' => 1],
            //Training Circular Type
            ['id' => 59, 'type' => 26, 'value_en' => 'Open', 'value_bn' => 'উন্মুক্ত', 'default' => 1],
            ['id' => 60, 'type' => 26, 'value_en' => 'Selected', 'value_bn' => 'বাছাইকৃত', 'default' => 1],

            //Training Type
            ['id' => 61, 'type' => 27, 'value_en' => 'Online', 'value_bn' => 'অনলাইন', 'default' => 1],
            ['id' => 62, 'type' => 27, 'value_en' => 'Physical', 'value_bn' => 'ফিজিক্যাল', 'default' => 1],


            //Training Circular Status
            ['id' => 63, 'type' => 28, 'value_en' => 'Active', 'value_bn' => 'সক্রিয়', 'default' => 1],
            ['id' => 64, 'type' => 28, 'value_en' => 'Inactive', 'value_bn' => 'নিষ্ক্রিয়', 'default' => 1],
            ['id' => 65, 'type' => 28, 'value_en' => 'In-Progress', 'value_bn' => 'চলমান', 'default' => 1],
            ['id' => 66, 'type' => 28, 'value_en' => 'Completed', 'value_bn' => 'সম্পন্ন', 'default' => 1],

            //Training Module
            ['id' => 67, 'type' => 29, 'value_en' => 'System Configuration', 'value_bn' => 'সিস্টেম কনফিগারেশন', 'default' => 1],
            ['id' => 68, 'type' => 29, 'value_en' => 'Budget & Allotment', 'value_bn' => 'বাজেট ও বরাদ্দ', 'default' => 1],
            ['id' => 69, 'type' => 29, 'value_en' => 'Application & Selection', 'value_bn' => 'আবেদন ও নির্বাচন ব্যবস্থাপনা', 'default' => 1],
            ['id' => 70, 'type' => 29, 'value_en' => 'Beneficiary Management', 'value_bn' => 'উপকারভোগী ব্যবস্থাপনা', 'default' => 1],
            ['id' => 71, 'type' => 29, 'value_en' => 'Payroll Management', 'value_bn' => 'বেতন ব্যবস্থাপনা', 'default' => 1],
            ['id' => 72, 'type' => 29, 'value_en' => 'Emergency Payment', 'value_bn' => 'জরুরী পেমেন্ট', 'default' => 1],
            ['id' => 73, 'type' => 29, 'value_en' => 'Grievance Management', 'value_bn' => 'অভিযোগ ব্যবস্থাপনা', 'default' => 1],
            ['id' => 74, 'type' => 29, 'value_en' => 'M&E Reporting', 'value_bn' => 'এমএন্ডই এবং রিপোর্টিং', 'default' => 1],
            ['id' => 75, 'type' => 29, 'value_en' => 'API Manager', 'value_bn' => 'এপিআই ম্যানেজার', 'default' => 1],
            ['id' => 76, 'type' => 29, 'value_en' => 'System Audit', 'value_bn' => 'সিস্টেম অডিট', 'default' => 1],


            //Training Participant Organization
            ['id' => 77, 'type' => 30, 'value_en' => 'Ministry user', 'value_bn' => 'মিনিস্ট্রি ইউজার', 'default' => 1],
            ['id' => 78, 'type' => 30, 'value_en' => 'CTM', 'value_bn' => 'সিটিমএম', 'default' => 1],
            ['id' => 79, 'type' => 30, 'value_en' => 'USSO', 'value_bn' => 'ইউএসএসও', 'default' => 1],
            ['id' => 80, 'type' => 30, 'value_en' => 'UCD', 'value_bn' => 'ইউসিডি', 'default' => 1]


        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('lookups')->truncate();
        foreach ($lookups as $value) {
            $lookup = new Lookup;
            $lookup->id = $value['id'];
            $lookup->type = $value['type'];
            $lookup->value_en = $value['value_en'];
            $lookup->value_bn = $value['value_bn'];
            $lookup->keyword = $value['keyword'] ?? null;
            $lookup->default = $value['default'];
            $lookup->save();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
