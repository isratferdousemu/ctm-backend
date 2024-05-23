<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('banks')->delete();

        $banks = [
            ['name_en' => 'Bangladesh Bank', 'name_bn' => 'বাংলাদেশ ব্যাংক', 'category' => 'Central Bank'],
            ['name_en' => 'Sonali Bank', 'name_bn' => 'সোনালী ব্যাংক', 'category' => 'State-owned Commercial'],
            ['name_en' => 'Agrani Bank', 'name_bn' => 'অগ্রণী ব্যাংক', 'category' => 'State-owned Commercial'],
            ['name_en' => 'Rupali Bank', 'name_bn' => 'রূপালী ব্যাংক', 'category' => 'State-owned Commercial'],
            ['name_en' => 'Janata Bank', 'name_bn' => 'জনতা ব্যাংক', 'category' => 'State-owned Commercial'],
            ['name_en' => 'BRAC Bank Limited', 'name_bn' => 'ব্র্যাক ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Dutch Bangla Bank Limited', 'name_bn' => 'ডাচ বাংলা ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Eastern Bank Limited', 'name_bn' => 'ইস্টার্ন ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'United Commercial Bank Limited', 'name_bn' => 'ইউনাইটেড কমার্শিয়াল ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Mutual Trust Bank Limited', 'name_bn' => 'মিউচ্যুয়াল ট্রাস্ট ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Dhaka Bank Limited', 'name_bn' => 'ঢাকা ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Islami Bank Bangladesh Ltd', 'name_bn' => 'ইসলামী ব্যাংক বাংলাদেশ লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Uttara Bank Limited', 'name_bn' => 'উত্তরা ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Pubali Bank Limited', 'name_bn' => 'পুবালী ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'IFIC Bank Limited', 'name_bn' => 'আইএফআইসি ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'National Bank Limited', 'name_bn' => 'ন্যাশনাল ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'The City Bank Limited', 'name_bn' => 'দ্য সিটি ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'NCC Bank Limited', 'name_bn' => 'এনসিসি ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Mercantile Bank Limited', 'name_bn' => 'মার্চেন্টাইল ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Southeast Bank Limited', 'name_bn' => 'সাউথইস্ট ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Prime Bank Limited', 'name_bn' => 'প্রাইম ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Social Islami Bank Limited', 'name_bn' => 'সোশ্যাল ইসলামী ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Standard Bank Limited', 'name_bn' => 'স্ট্যান্ডার্ড ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Al-Arafah Islami Bank Limited', 'name_bn' => 'আল-আরাফাহ ইসলামী ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'One Bank Limited', 'name_bn' => 'ওয়ান ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Exim Bank Limited', 'name_bn' => 'এক্সিম ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'First Security Islami Bank Limited', 'name_bn' => 'ফার্স্ট সিকিউরিটি ইসলামী ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Bank Asia Limited', 'name_bn' => 'ব্যাংক এশিয়া লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'The Premier Bank Limited', 'name_bn' => 'দ্য প্রিমিয়ার ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Bangladesh Commerce Bank Limited', 'name_bn' => 'বাংলাদেশ কমার্স ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Trust Bank Limited', 'name_bn' => 'ট্রাস্ট ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Jamuna Bank Limited', 'name_bn' => 'যমুনা ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Shahjalal Islami Bank Limited', 'name_bn' => 'শাহজালাল ইসলামী ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'ICB Islamic Bank', 'name_bn' => 'আইসিবি ইসলামিক ব্যাংক', 'category' => 'Private Commercial'],
            ['name_en' => 'AB Bank', 'name_bn' => 'এবি ব্যাংক', 'category' => 'Private Commercial'],
            ['name_en' => 'Jubilee Bank Limited', 'name_bn' => 'জুবিলি ব্যাংক লিমিটেড', 'category' => 'Private Commercial'],
            ['name_en' => 'Karmasangsthan Bank', 'name_bn' => 'কর্মসংস্থান ব্যাংক', 'category' => 'Specialized Development'],
            ['name_en' => 'Bangladesh Krishi Bank', 'name_bn' => 'বাংলাদেশ কৃষি ব্যাংক', 'category' => 'Specialized Development'],
            ['name_en' => 'Progoti Bank', 'name_bn' => 'প্রগতি ব্যাংক', 'category' => ''],
            ['name_en' => 'Rajshahi Krishi Unnayan Bank', 'name_bn' => 'রাজশাহী কৃষি উন্নয়ন ব্যাংক', 'category' => 'Specialized Development'],
            ['name_en' => 'Bangladesh Development Bank Ltd', 'name_bn' => 'বাংলাদেশ ডেভেলপমেন্ট ব্যাংক লিমিটেড', 'category' => 'Specialized Development'],
            ['name_en' => 'Bangladesh Somobay Bank Limited', 'name_bn' => 'বাংলাদেশ সমবায় ব্যাংক লিমিটেড', 'category' => 'Specialized Development'],
            ['name_en' => 'Grameen Bank', 'name_bn' => 'গ্রামীণ ব্যাংক', 'category' => 'Specialized Development'],
            ['name_en' => 'BASIC Bank Limited', 'name_bn' => 'বেসিক ব্যাংক লিমিটেড', 'category' => 'Specialized Development'],
            ['name_en' => 'Ansar VDP Unnyan Bank', 'name_bn' => 'আনসার ভিডিপি উন্নয়ন ব্যাংক', 'category' => 'Specialized Development'],
            ['name_en' => 'The Dhaka Mercantile Co-operative Bank Limited(DMCBL)', 'name_bn' => 'ঢাকা বাণিজ্যিক সমবায় ব্যাংক লিমিটেড (ডিএমসিবিএল)', 'category' => 'Specialized Development'],
            ['name_en' => 'Citibank', 'name_bn' => 'সিটিব্যাঙ্ক', 'category' => 'Foreign Commercial'],
            ['name_en' => 'HSBC', 'name_bn' => 'এইচএসবিসি', 'category' => 'Foreign Commercial'],
            ['name_en' => 'Standard Chartered Bank', 'name_bn' => 'স্ট্যান্ডার্ড চার্টার্ড ব্যাংক', 'category' => 'Foreign Commercial'],
            ['name_en' => 'Commercial Bank of Ceylon', 'name_bn' => 'সমার্শিয়াল ব্যাংক অব সিলন', 'category' => 'Foreign Commercial'],
            ['name_en' => 'State Bank of India', 'name_bn' => 'ভারতীয় রাষ্ট্র ব্যাংক', 'category' => 'Foreign Commercial'],
            ['name_en' => 'Woori Bank', 'name_bn' => 'ওয়ুরি ব্যাংক', 'category' => 'Foreign Commercial'],
            ['name_en' => 'Bank Alfalah', 'name_bn' => 'ব্যাংক আলফালা', 'category' => 'Foreign Commercial'],
            ['name_en' => 'National Bank of Pakistan', 'name_bn' => 'ন্যাশনাল ব্যাংক অব পাকিস্তান', 'category' => 'Foreign Commercial'],
            ['name_en' => 'ICICI Bank', 'name_bn' => 'আইসিআইসিআই ব্যাংক', 'category' => 'Foreign Commercial'],
            ['name_en' => 'Habib Bank Limited', 'name_bn' => 'হাবিব ব্যাংক লিমিটেড', 'category' => 'Foreign Commercial']
        ];

        DB::table('banks')->insert($banks);
    }
}
