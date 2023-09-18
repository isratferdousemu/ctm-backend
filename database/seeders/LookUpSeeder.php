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
            ['type' => 1, 'value_en' => 'District Pouroshava','value_bn' => 'জেলা পৌরসভা'],
            ['type' => 1, 'value_en' => 'Upazila','value_bn' => 'উপজেলা'],
            ['type' => 1, 'value_en' => 'City Corporation','value_bn' => 'সিটি কর্পোরেশন'],
        ];
        foreach ($lookups as $value) {
            $lookup = new Lookup;
            $lookup->type                   = $value['type'];
            $lookup->value_en               = $value['value_en'];
            $lookup->value_bn               = $value['value_bn'];
            $lookup ->save();
        }

    }
}
