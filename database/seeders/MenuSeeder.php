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
    (1, NULL, NULL, 'Dashboard', 'Dashboard', 2, '/dashboard', 1, NULL, '2023-10-07 08:41:56', '2023-10-08 11:39:49'),
    (2, NULL, NULL, 'System Configuration', 'System Configuration', 1, NULL, 2, NULL, '2023-10-07 08:43:05', '2023-10-07 08:43:05'),
    (3, NULL, 2, 'Demographic Information', 'Demographic Information', NULL, NULL, 3, NULL, '2023-10-07 08:44:11', '2023-10-07 08:44:11'),
    (4, 2, 3, 'Division', 'Division', 1, NULL, 4, NULL, '2023-10-07 09:12:05', '2023-10-07 09:12:05'),
    (5, 6, 3, 'District', 'District', 1, NULL, 5, NULL, '2023-10-07 09:22:06', '2023-10-22 10:01:43'),
    (6, 12, 3, 'City Corporation /  District Pouroshava', 'City Corporation /  District Pouroshava', 1, NULL, 6, NULL, '2023-10-07 09:25:38', '2023-10-07 09:57:08'),
    (7, 14, 3, 'Thana / Upazila', 'Thana / Upazila', 1, NULL, 7, NULL, '2023-10-07 09:26:07', '2023-10-22 10:02:10'),
    (8, 18, 3, 'Union / Pourashava', 'Union / Pourashava', 1, NULL, 8, NULL, '2023-10-07 09:26:34', '2023-10-22 10:02:31'),
    (9, 22, 3, 'Ward', 'Ward', 1, NULL, 9, NULL, '2023-10-07 09:56:15', '2023-10-22 10:02:50'),
    (10, 26, 2, 'Allowance Program Management', 'Allowance Program Management', 1, NULL, 10, NULL, '2023-10-07 09:58:09', '2023-10-22 05:47:06'),
    (11, 30, 2, 'Office Management', 'Office Management', 1, NULL, 11, NULL, '2023-10-07 09:59:02', '2023-10-22 10:03:12'),
    (12, 34, 2, 'Financial Year Management', 'Financial Year Management', 1, NULL, 12, NULL, '2023-10-07 10:04:30', '2023-10-22 10:03:44'),
    (13, NULL, 2, 'User Management', 'User Management', NULL, NULL, 13, NULL, '2023-10-07 10:06:06', '2023-10-07 10:06:06'),
    (14, 42, 13, 'Role List', 'Role List', 1, NULL, 14, NULL, '2023-10-07 10:06:39', '2023-10-22 10:04:51'),
    (15, 45, 13, 'Role Permission', 'Role Permission', 1, NULL, 15, NULL, '2023-10-07 16:23:26', '2023-10-22 10:05:07'),
    (16, 38, 13, 'User List', 'User List', 1, NULL, 16, NULL, '2023-10-07 16:25:01', '2023-10-22 10:05:19'),
    (17, 59, 2, 'Device Registration', 'Device Registration', 1, NULL, 17, NULL, '2023-10-07 16:26:13', '2023-10-22 10:05:34'),
    (18, 55, 2, 'Menu', 'Menu', 1, NULL, 18, NULL, '2023-10-07 16:27:07', '2023-10-22 05:45:39'),
    (19, 47, NULL, 'Budget Management', 'Budget Management', 1, NULL, 19, NULL, '2023-10-07 22:37:03', '2023-10-22 10:06:05'),
    (20, NULL, NULL, 'Manage Allotment', 'Manage Allotment', NULL, NULL, 20, NULL, '2023-10-07 22:37:40', '2023-10-07 22:37:40'),
    (21, 50, 20, 'Allotment Entry', 'Allotment Entry', 1, NULL, 21, NULL, '2023-10-08 05:13:19', '2023-10-22 10:07:31'),
    (22, 51, 20, 'Allotment List', 'Allotment List', 1, NULL, 22, NULL, '2023-10-08 05:13:43', '2023-10-22 10:07:43'),
    (23, NULL, NULL, 'Application & Selection', 'Application & Selection', NULL, NULL, 23, NULL, '2023-10-08 05:14:10', '2023-10-08 05:14:10'),
    (24, 72, 23, 'Online Application', 'Online Application', 1, NULL, 24, '2023-10-22 10:08:09', '2023-10-08 05:14:40', '2023-10-22 10:08:09'),
    (25, 73, 23, 'Application List', 'Application List', 1, NULL, 25, NULL, '2023-10-08 05:15:06', '2023-10-08 05:15:06'),
    (26, NULL, NULL, 'Beneficiary Management', 'Beneficiary Management', NULL, NULL, 26, NULL, '2023-10-08 05:15:31', '2023-10-08 05:15:31'),
    (27, 98, 26, 'Beneficiary List', 'Beneficiary List', 1, NULL, 27, NULL, '2023-10-08 05:29:59', '2023-10-08 05:29:59'),
    (28, 118, 26, 'Digital ID Card', 'Digital ID Card', 1, NULL, 28, NULL, '2023-10-08 05:31:01', '2023-10-08 05:31:01'),
    (29, 113, 26, 'Beneficiary Exit', 'Beneficiary Exit', 1, NULL, 29, NULL, '2023-10-08 05:31:32', '2023-10-08 05:31:32'),
    (30, 95, 26, 'Committee List', 'Committee List', 1, NULL, 30, NULL, '2023-10-08 05:32:00', '2023-10-22 10:10:05'),
    (31, NULL, NULL, 'Payroll Management', 'Payroll Management', NULL, NULL, 31, '2023-10-08 05:50:52', '2023-10-08 05:32:29', '2023-10-08 05:50:52'),
    (32, NULL, NULL, 'check', 'check', NULL, NULL, 31, '2023-10-09 00:35:32', '2023-10-09 00:35:21', '2023-10-09 00:35:32'),
    (33, NULL, NULL, 'Settings', 'Settings', NULL, NULL, 30, NULL, '2023-10-22 10:09:14', '2023-10-22 10:09:14'),
    (34, 143, 33, 'General', 'General', 1, NULL, 31, NULL, '2023-10-22 10:09:35', '2023-10-22 10:09:35');";

        // Execute the SQL query
        \DB::statement($menus);

    }
}
