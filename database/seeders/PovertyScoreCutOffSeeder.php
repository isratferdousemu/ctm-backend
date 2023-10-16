<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PovertyScoreCutOff;

class PovertyScoreCutOffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $poverty_score_cut_offs = [
            [
                'id' => 1,
                'type' => 1,
                'location_id' => 4, //Barishal
                'score' => '795',
            ],

            [
                'id' => 2,
                'type' => 1,
                'location_id' => 1, //Chattagram
                'score' => '800',
            ],

            [
                'id' => 3,
                'type' => 1,
                'location_id' => 6, //Dhaka
                'score' => '810',
            ],

            [
                'id' => 4,
                'type' => 1,
                'location_id' => 3, //Khulna
                'score' => '785',
            ],

            [
                'id' => 5,
                'type' => 1,
                'location_id' => 8, //Mymensignh
                'score' => '780',
            ],

            [
                'id' => 6,
                'type' => 1,
                'location_id' => 2, //Rajshahi
                'score' => '790',
            ],

            [
                'id' => 7,
                'type' => 1,
                'location_id' => 7, //Rangpur
                'score' => '775',
            ],

        ];
        foreach ($poverty_score_cut_offs as $value) {
            $poverty_score_cut_offs = new PovertyScoreCutOff;
            $poverty_score_cut_offs->id           = $value['id'];
            $poverty_score_cut_offs->type         = $value['type'];
            $poverty_score_cut_offs->location_id  = $value['location_id'];
            $poverty_score_cut_offs->score        = $value['score'];
            $poverty_score_cut_offs->save();
        }
    }
}
