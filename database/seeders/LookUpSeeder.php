<?php

namespace Database\Seeders;

use App\Models\Lookup;
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
            ['id' => 9, 'type' => 3, 'value_en' => 'UCD', 'value_bn' => 'UCD','default'=>1],
            ['id' => 10, 'type' => 3, 'value_en' => 'Upazila UCD', 'value_bn' => 'Upazila UCD','default'=>1],
            ['id' => 11, 'type' => 3, 'value_en' => 'Circle Social Service', 'value_bn' => 'Circle Social Service','default'=>1]
        ];
        foreach ($lookups as $value) {
            $lookup = new Lookup;
            $lookup->id                   = $value['id'];
            $lookup->type                   = $value['type'];
            $lookup->value_en               = $value['value_en'];
            $lookup->value_bn               = $value['value_bn'];
            $lookup->default               = $value['default'];
            $lookup ->save();
        }

    }
}
