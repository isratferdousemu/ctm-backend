<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\Installment;
use App\Models\PayrollInstallmentSchedule;
use Illuminate\Support\Facades\DB;

class InstallmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PayrollInstallmentSchedule::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $installments = [
            [
                'id' => 1,
                'payment_cycle' => "Monthly",
                'installment_name' => '1st installment (July)',
                'installment_name_bn' => '১ম কিস্তি (জুলাই)',
            ],
            [
                'id' => 2,
                'payment_cycle' => "Monthly",
                'installment_name' => '2nd installment (August)',
                'installment_name_bn' => '২য় কিস্তি (আগস্ট)',
            ],
            [
                'id' => 3,
                'payment_cycle' => "Monthly",
                'installment_name' => '3rd installment (September)',
                'installment_name_bn' => '৩য় কিস্তি (সেপ্টেম্বর)',
            ],
            [
                'id' => 4,
                'payment_cycle' => "Monthly",
                'installment_name' => '4th installment (October)',
                'installment_name_bn' => '৪র্থ কিস্তি (অক্টোবর)',
            ],
            [
                'id' => 5,
                'payment_cycle' => "Monthly",
                'installment_name' => '5th installment (November)',
                'installment_name_bn' => '৫ম কিস্তি (নভেম্বর)',
            ],
            [
                'id' => 6,
                'payment_cycle' => "Monthly",
                'installment_name' => '6th installment (December)',
                'installment_name_bn' => '৬ষ্ঠ কিস্তি (ডিসেম্বর)',
            ],
            [
                'id' => 7,
                'payment_cycle' => "Monthly",
                'installment_name' => '7th installment (January)',
                'installment_name_bn' => '৭ম কিস্তি (জানুয়ারি)',
            ],
            [
                'id' => 8,
                'payment_cycle' => "Monthly",
                'installment_name' => '8th installment (February)',
                'installment_name_bn' => '৮ম কিস্তি (ফেব্রুয়ারি)',
            ],
            [
                'id' => 9,
                'payment_cycle' => "Monthly",
                'installment_name' => '9th installment (March)',
                'installment_name_bn' => '৯ম কিস্তি (মার্চ)',
            ],
            [
                'id' => 10,
                'payment_cycle' => "Monthly",
                'installment_name' => '10th installment (April)',
                'installment_name_bn' => '১০ম কিস্তি (এপ্রিল)',
            ],
            [
                'id' => 11,
                'payment_cycle' => "Monthly",
                'installment_name' => '11th installment (May)',
                'installment_name_bn' => '১১তম কিস্তি (মে)',
            ],
            [
                'id' => 12,
                'payment_cycle' => "Monthly",
                'installment_name' => '12th installment (June)',
                'installment_name_bn' => '১২তম কিস্তি (জুন)',
            ],
            // Quarterly
            [
                'id' => 13,
                'payment_cycle' => "Quarterly",
                'installment_name' => '1st installment (July - September)',
                'installment_name_bn' => '১ম কিস্তি (জুলাই - সেপ্টেম্বর)',
            ],
            [
                'id' => 14,
                'payment_cycle' => "Quarterly",
                'installment_name' => '2nd installment (October - December)',
                'installment_name_bn' => '২য় কিস্তি (অক্টোবর - ডিসেম্বর)',
            ],
            [
                'id' => 15,
                'payment_cycle' => "Quarterly",
                'installment_name' => '3rd installment (January - March)',
                'installment_name_bn' => '৩য় কিস্তি (জানুয়ারি - মার্চ)',
            ],
            [
                'id' => 16,
                'payment_cycle' => "Quarterly",
                'installment_name' => '4th installment (April - June)',
                'installment_name_bn' => '৪র্থ কিস্তি (এপ্রিল - জুন)',
            ],
            // Half Yearly
            [
                'id' => 17,
                'payment_cycle' => "Half Yearly",
                'installment_name' => '1st installment (July - December)',
                'installment_name_bn' => '১ম কিস্তি (জুলাই - ডিসেম্বর)',
            ],
            [
                'id' => 18,
                'payment_cycle' => "Half Yearly",
                'installment_name' => '2nd installment (January - June)',
                'installment_name_bn' => '২য় কিস্তি (জানুয়ারি - জুন)',
            ],
            // Yearly
            [
                'id' => 19,
                'payment_cycle' => "Yearly",
                'installment_name' => 'Installment (July - June)',
                'installment_name_bn' => 'কিস্তি (জুলাই - জুন)',
            ],
        ];

        foreach ($installments as $value) {
            PayrollInstallmentSchedule::create([
                'id' => $value['id'],
                'payment_cycle' => $value['payment_cycle'],
                'installment_name' => $value['installment_name'],
                'installment_name_bn' => $value['installment_name_bn'],
            ]);
        }
    }
}
