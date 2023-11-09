<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $offices = [
            [
                'id'=>1,
                'name_en' => 1,
                'name_bn' => 'District Pouroshava',
                'office_type' => 'জেলা পৌরসভা',
                'office_address'=>1,
                'status'=>1,
                'assign_location_id'=>1
            ],

        ];

    }
}
