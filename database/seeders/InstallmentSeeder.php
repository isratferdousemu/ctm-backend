<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\Installment;
use App\Models\PayrollInstallmentSchedule;

class InstallmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $installments = [
            [
                'id' => 1,
                'payment_cycle' => "Monthly",
                'installment_name' => '1st installment (July)',
            ],
            [
                'id' => 2,
                'payment_cycle' => "Monthly",
                'installment_name' => '2nd installment (August)',
            ],
            [
                'id' => 3,
                'payment_cycle' => "Monthly",
                'installment_name' => '3rd installment (September)',
            ],
            [
                'id' => 4,
                'payment_cycle' => "Monthly",
                'installment_name' => '4th installment (October)',
            ],
            [
                'id' => 5,
                'payment_cycle' => "Monthly",
                'installment_name' => '5th installment (November)',
            ],
            [
                'id' => 6,
                'payment_cycle' => "Monthly",
                'installment_name' => '6th installment (December)',
            ],
            [
                'id' => 7,
                'payment_cycle' => "Monthly",
                'installment_name' => '7th installment (January)',
            ],
            [
                'id' => 8,
                'payment_cycle' => "Monthly",
                'installment_name' => '8th installment (February)',
            ],
            [
                'id' => 9,
                'payment_cycle' => "Monthly",
                'installment_name' => '9th installment (March)',
            ],
            [
                'id' => 10,
                'payment_cycle' => "Monthly",
                'installment_name' => '10th installment (April)',
            ],
            [
                'id' => 11,
                'payment_cycle' => "Monthly",
                'installment_name' => '11th installment (May)',
            ],
            [
                'id' => 12,
                'payment_cycle' => "Monthly",
                'installment_name' => '12th installment (June)',
            ],

            //quatarly
            [
                'id' => 13,
                'payment_cycle' => "Quarterly",
                'installment_name'=>'1st installment (July - September)',
            ],
            [
                'id' => 14,
                'payment_cycle' => "Quarterly",
                'installment_name'=>'2nd installment (October - December)',
            ],
            [
                'id' => 15,
                'payment_cycle' => "Quarterly",
                'installment_name'=>'3rd installment (January - March)',
            ],
            [
                'id' => 16,
                'payment_cycle' => "Quarterly",
                'installment_name'=>'4th installment (April - June)',
            ],

            //
            [
                'id' => 17,
                'payment_cycle' => "Half Yearly",
                'installment_name'=>'1st installment (July - December)',
            ],
            [
                'id' => 18,
                'payment_cycle' => "Half Yearly",
                'installment_name'=>'2nd installment (January - June)',
            ],
            //
            [
                'id' => 19,
                'payment_cycle' => "Yearly",
                'installment_name'=>'Installment (July - June)',
            ],
        ];


        foreach ($installments as $value) {
            PayrollInstallmentSchedule::create([
                'id' => $value['id'],
                'payment_cycle' => $value['payment_cycle'],
                'installment_name' => $value['installment_name'],
            ]);
        }
    }
}
