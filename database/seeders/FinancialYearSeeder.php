<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FinancialYear;

class FinancialYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $financial_years = [
            [
                'id' => 1,
                'financial_year' => '2019-2020',
                'start_date' => '2019-07-01',
                'end_date' => '2020-06-30',
                'status' => 0,
                'version' => 1,
                'created_at' => '2023-10-19 07:46:56',
                'updated_at' => '2023-10-19 07:46:56',
            ],
            [
                'id' => 2,
                'financial_year' => '2020-2021',
                'start_date' => '2020-07-01',
                'end_date' => '2021-06-30',
                'status' => 0,
                'version' => 1,
                'created_at' => '2023-10-19 07:48:33',
                'updated_at' => '2023-10-19 07:48:33',
            ],
        ];
        foreach ($financial_years as $value) {
            $financial_year = new FinancialYear;
            $financial_year->id                = $value['id'];
            $financial_year->financial_year    = $value['financial_year'];
            $financial_year->start_date        = $value['start_date'];
            $financial_year->end_date          = $value['end_date'];
            $financial_year->status            = $value['status'];
            $financial_year->version           = $value['version'];
            $financial_year->save();
        }
    }
}
