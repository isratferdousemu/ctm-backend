<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictFixedEffectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $sql = "INSERT INTO `poverty_score_cut_offs` (`location_id`, `score`,`default`) VALUES 
(36, '0', 1), 
(19, '-0.17', 1),	
(43, '0.02', 1),	
(41, '-0.01', 1),	
(42, '0.32', 1),	
(22, '0.06', 1),	
(11, '0.43', 1),	
(14, '0', 1),	
(26, '-0.07', 1),	
(16, '0.11', 1),	
(32, '-0.16', 1),	
(9, '0.15', 1),	
(17, '0.24', 1),	
(55, '0.18', 1),	
(62, '-0.26', 1),	
(60, '0.28', 1),	
(10, '0.29', 1),	
(65, '0.02', 1),	
(49, '0.24', 1),	
(59, '0.06', 1),	
(46, '0.34', 1),	
(71, '-0.09', 1),	
(28, '-0.11', 1),	
(38, '0.05', 1),	
(37, '-0.09', 1),	
(25, '0.06', 1),	
(18, '-0.12', 1),	
(35, '-0.03', 1),	
(53, '-0.07', 1),	
(68, '-0.28', 1),	
(33, '0.09', 1),	
(15, '0.02', 1),	
(63, '0', 1),	
(58, '0.34', 1),	
(34, '-0.25', 1),	
(54, '0', 1),	
(45, '0.43', 1),	
(30, '-0.16', 1),	
(56, '0.28', 1),	
(70, '0.05', 1),	
(27, '-0.01', 1),	
(31, '0.03', 1),	
(51, '0.35', 1),	
(48, '0.38', 1),	
(24, '0.08', 1),	
(72, '0.16', 1),	
(64, '0.05', 1),	
(13, '0.22', 1),	
(21, '-0.01', 1),	
(61, '0.03', 1),	
(39, '0.04', 1),	
(40, '-0.07', 1),	
(57, '-0.01', 1),	
(23, '0.1', 1),	
(12, '0', 1),	
(67, '-0.09', 1),	
(29, '0.11', 1),	
(50, '0.15', 1),	
(69, '-0.01', 1),	
(20, '0.13', 1),	
(47, '0.3', 1),	
(44, '0.35', 1),	
(52, '0.11', 1),	
(66, '0.11', 1);	
";

        // Execute the SQL query
        \DB::statement($sql);
    }
}
