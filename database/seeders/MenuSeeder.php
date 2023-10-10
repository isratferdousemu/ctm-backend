<?php

namespace Database\Seeders;

use App\Models\Menu;
use Arr;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

    $menus = "INSERT INTO `menus` (`id`, `page_link_id`, `parent_id`, `label_name_en`, `label_name_bn`, `link_type`, `link`, `order`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'Dashboard', 'Dashboard', 2, '/dashboard', 1, NULL, '2023-10-07 14:41:56', '2023-10-08 17:39:49'),
(2, NULL, NULL, 'System Configuration', 'System Configuration', 1, NULL, 2, NULL, '2023-10-07 14:43:05', '2023-10-07 14:43:05'),
(3, NULL, 2, 'Demographic Information', 'Demographic Information', NULL, NULL, 3, NULL, '2023-10-07 14:44:11', '2023-10-07 14:44:11'),
(4, 2, 3, 'Division', 'Division', 1, NULL, 4, NULL, '2023-10-07 15:12:05', '2023-10-07 15:12:05'),
(5, 7, 3, 'District', 'District', 1, NULL, 5, NULL, '2023-10-07 15:22:06', '2023-10-07 15:22:06'),
(6, 12, 3, 'City Corporation /  District Pouroshava', 'City Corporation /  District Pouroshava', 1, NULL, 6, NULL, '2023-10-07 15:25:38', '2023-10-07 15:57:08'),
(7, 17, 3, 'Thana / Upazila', 'Thana / Upazila', 1, NULL, 7, NULL, '2023-10-07 15:26:07', '2023-10-07 15:56:57'),
(8, 22, 3, 'Union / Pourashava', 'Union / Pourashava', 1, NULL, 8, NULL, '2023-10-07 15:26:34', '2023-10-07 15:56:46'),
(9, 27, 3, 'Ward', 'Ward', 1, NULL, 9, NULL, '2023-10-07 15:56:15', '2023-10-07 15:56:30'),
(10, 32, 2, 'Allowance Program Management', 'Allowance Program Management', 1, NULL, 10, NULL, '2023-10-07 15:58:09', '2023-10-07 15:58:24'),
(11, 37, 2, 'Office Management', 'Office Management', 1, NULL, 11, NULL, '2023-10-07 15:59:02', '2023-10-07 16:03:39'),
(12, 42, 2, 'Financial Year Management', 'Financial Year Management', 1, NULL, 12, NULL, '2023-10-07 16:04:30', '2023-10-07 16:05:39'),
(13, NULL, 2, 'User Management', 'User Management', NULL, NULL, 13, NULL, '2023-10-07 16:06:06', '2023-10-07 16:06:06'),
(14, 47, 13, 'Role List', 'Role List', 1, NULL, 14, NULL, '2023-10-07 16:06:39', '2023-10-07 16:06:39'),
(15, 51, 13, 'Role Permission', 'Role Permission', 1, NULL, 15, NULL, '2023-10-07 22:23:26', '2023-10-07 22:23:26'),
(16, 53, 13, 'User List', 'User List', 1, NULL, 16, NULL, '2023-10-07 22:25:01', '2023-10-07 22:25:01'),
(17, 58, 2, 'Device Registration', 'Device Registration', 1, NULL, 17, NULL, '2023-10-07 22:26:13', '2023-10-07 22:26:13'),
(18, 68, 2, 'Menu', 'Menu', 1, NULL, 18, NULL, '2023-10-07 22:27:07', '2023-10-08 17:57:57'),
(19, 58, NULL, 'Budget Management', 'Budget Management', 1, NULL, 19, NULL, '2023-10-08 04:37:03', '2023-10-08 04:37:03'),
(20, NULL, NULL, 'Manage Allotment', 'Manage Allotment', NULL, NULL, 20, NULL, '2023-10-08 04:37:40', '2023-10-08 04:37:40'),
(21, 63, 20, 'Allotment Entry', 'Allotment Entry', 1, NULL, 21, NULL, '2023-10-08 11:13:19', '2023-10-08 11:13:19'),
(22, 62, 20, 'Allotment List', 'Allotment List', 1, NULL, 22, NULL, '2023-10-08 11:13:43', '2023-10-08 11:13:43'),
(23, NULL, NULL, 'Application & Selection', 'Application & Selection', NULL, NULL, 23, NULL, '2023-10-08 11:14:10', '2023-10-08 11:14:10'),
(24, 72, 23, 'Online Application', 'Online Application', 1, NULL, 24, NULL, '2023-10-08 11:14:40', '2023-10-08 11:14:40'),
(25, 73, 23, 'Application List', 'Application List', 1, NULL, 25, NULL, '2023-10-08 11:15:06', '2023-10-08 11:15:06'),
(26, NULL, NULL, 'Beneficiary Management', 'Beneficiary Management', NULL, NULL, 26, NULL, '2023-10-08 11:15:31', '2023-10-08 11:15:31'),
(27, 98, 26, 'Beneficiary List', 'Beneficiary List', 1, NULL, 27, NULL, '2023-10-08 11:29:59', '2023-10-08 11:29:59'),
(28, 118, 26, 'Digital ID Card', 'Digital ID Card', 1, NULL, 28, NULL, '2023-10-08 11:31:01', '2023-10-08 11:31:01'),
(29, 113, 26, 'Beneficiary Exit', 'Beneficiary Exit', 1, NULL, 29, NULL, '2023-10-08 11:31:32', '2023-10-08 11:31:32'),
(30, 103, 26, 'Committee List', 'Committee List', 1, NULL, 30, NULL, '2023-10-08 11:32:00', '2023-10-08 11:32:00'),
(31, NULL, NULL, 'Payroll Management', 'Payroll Management', NULL, NULL, 31, '2023-10-08 11:50:52', '2023-10-08 11:32:29', '2023-10-08 11:50:52'),
(32, NULL, NULL, 'check', 'check', NULL, NULL, 31, '2023-10-09 06:35:32', '2023-10-09 06:35:21', '2023-10-09 06:35:32');";

        // Execute the SQL query
        \DB::statement($menus);

    }
}
