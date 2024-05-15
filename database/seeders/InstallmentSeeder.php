<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\Installment;

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
                'type' => "monthly",
                'name' => '1st installment (July)',
                'status' => 1,
            ],
            [
                'id' => 2,
                'type' => "monthly",
                'name' => '2nd installment (August)',
                'status' => 1,
            ],
            [
                'id' => 3,
                'type' => "monthly",
                'name' => '3rd installment (September)',
                'status' => 1,
            ],
            [
                'id' => 4,
                'type' => "monthly",
                'name' => '4th installment (October)',
                'status' => 1,
            ],
            [
                'id' => 5,
                'type' => "monthly",
                'name' => '5th installment (November)',
                'status' => 1,
            ],
            [
                'id' => 6,
                'type' => "monthly",
                'name' => '6th installment (December)',
                'status' => 1,
            ],
            [
                'id' => 7,
                'type' => "monthly",
                'name' => '7th installment (January)',
                'status' => 1,
            ],
            [
                'id' => 8,
                'type' => "monthly",
                'name' => '8th installment (February)',
                'status' => 1,
            ],
            [
                'id' => 9,
                'type' => "monthly",
                'name' => '8th installment (March)',
                'status' => 1,
            ],
            [
                'id' => 10,
                'type' => "monthly",
                'name' => '8th installment (April)',
                'status' => 1,
            ],
            [
                'id' => 11,
                'type' => "monthly",
                'name' => '8th installment (May)',
                'status' => 1,
            ],
            [
                'id' => 12,
                'type' => "monthly",
                'name' => '8th installment (June)',
                'status' => 1,
            ],

            //quotarly
            // [
            //     'id' => 13,
            //     'type' => "quarterly",
            //     'name'=>'1st installment (July - September)',
            //     'status' => 1,
            // ],
            // [
            //     'id' => 14,
            //     'type' => "quarterly",
            //     'start' => 'October',
            //     'end' => 'December',
            //     'status' => 1,
            // ],
            // [
            //     'id' => 15,
            //     'type' => "quarterly",
            //     'start' => 'January',
            //     'end' => 'March',
            //     'status' => 1,
            // ],
            // [
            //     'id' => 16,
            //     'type' => "quarterly",
            //     'start' => 'April',
            //     'end' => 'June',
            //     'status' => 1,
            // ],

            // //
            // [
            //     'id' => 17,
            //     'type' => "half-yearly",
            //     'start' => 'July',
            //     'end' => 'December',
            //     'status' => 1,
            // ],
            // [
            //     'id' => 18,
            //     'type' => "half-yearly",
            //     'start' => 'January',
            //     'end' => 'June',
            //     'status' => 1,
            // ],
            // //
            // [
            //     'id' => 19,
            //     'type' => "yearly",
            //     'start' => 'fullyear',
            //     'end' => 'fullyear',
            //     'status' => 1,
            // ],
        ];


        foreach ($installments as $value) {
            Installment::create([
                'id' => $value['id'],
                'type' => $value['type'],
                'start' => $value['start'],
                'end' => $value['end'] ?? null, // Use null if 'end' key is not present
                'status' => $value['status'],
            ]);
        }
    }
}
