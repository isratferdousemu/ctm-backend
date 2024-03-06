<?php

namespace Database\Seeders;

use App\Models\Menu;
use Arr;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = "INSERT INTO `menus` (`id`, `page_link_id`, `parent_id`, `label_name_en`, `label_name_bn`, `link_type`, `link`, `order`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'Dashboard', 'ড্যাশবোর্ড', 2, '/dashboard', 1, NULL, '2023-10-06 20:41:56', '2024-01-29 11:52:17'),
(2, NULL, NULL, 'System Configuration', 'সিস্টেম কনফিগারেশন', 1, NULL, 2, NULL, '2023-10-06 20:43:05', '2024-01-28 16:03:42'),
(3, NULL, 2, 'Demographic Information', 'জনসংখ্যা সংক্রান্ত তথ্য', NULL, NULL, 3, NULL, '2023-10-06 20:44:11', '2024-01-28 16:04:16'),
(4, 2, 3, 'Division', 'বিভাগ', 1, NULL, 4, NULL, '2023-10-06 21:12:05', '2024-01-28 16:04:39'),
(5, 6, 3, 'District', 'জেলা', 1, NULL, 5, NULL, '2023-10-06 21:22:06', '2024-01-28 16:05:11'),
(6, 12, 3, 'Upazila / City Corporation /  District Pouroshava', 'উপজেলা/সিটি কর্পোরেশন/জেলা পৌরসভা', 1, NULL, 6, NULL, '2023-10-06 21:25:38', '2024-01-28 16:05:42'),
(7, 14, 3, 'Thana / Upazila', 'Thana / Upazila', 1, NULL, 7, '2024-01-30 17:50:49', '2023-10-06 21:26:07', '2024-01-30 17:50:49'),
(8, 18, 3, 'Thana / Union / Pourashava', 'থানা/ইউনিয়ন/পৌরসভা', 1, NULL, 8, NULL, '2023-10-06 21:26:34', '2024-01-28 16:06:14'),
(9, 22, 3, 'Ward', 'ওয়ার্ড', 1, NULL, 9, NULL, '2023-10-06 21:56:15', '2024-01-28 16:06:41'),
(10, NULL, 2, 'Allowance Program Management', 'ভাতা প্রোগ্রাম ব্যবস্থাপনা', 1, NULL, 10, NULL, '2023-10-06 21:58:09', '2024-01-28 16:14:20'),
(12, 180, 10, 'Allowance Program  Field', 'ভাতা প্রোগ্রাম ফিল্ড', 1, NULL, 12, NULL, '2023-10-06 21:58:09', '2024-01-28 17:05:24'),
(13, 26, 10, 'Allowance Program', 'ভাতা প্রোগ্রাম', 1, NULL, 13, NULL, '2023-10-06 21:58:09', '2024-01-28 17:06:00'),
(14, 30, 2, 'Office Management', 'অফিস ব্যবস্থাপনা', 1, NULL, 14, NULL, '2023-10-06 21:59:02', '2024-01-28 17:06:31'),
(15, 34, 2, 'Financial Year Management', 'আর্থিক বছর ব্যবস্থাপনা', 1, NULL, 15, NULL, '2023-10-06 22:04:30', '2024-01-28 17:06:51'),
(16, NULL, 2, 'User Management', 'ইউজার ম্যানেজমেন্ট', NULL, NULL, 16, NULL, '2023-10-06 22:06:06', '2024-01-28 17:07:20'),
(17, 42, 16, 'Role List', 'ভূমিকা তালিকা', 1, NULL, 17, NULL, '2023-10-06 22:06:39', '2024-01-28 17:07:40'),
(18, 45, 16, 'Role Permission', 'ভূমিকা অনুমতি', 1, NULL, 18, NULL, '2023-10-07 04:23:26', '2024-01-28 17:08:27'),
(19, 38, 16, 'User List', 'ব্যবহারকারীর তালিকা', 1, NULL, 19, NULL, '2023-10-07 04:25:01', '2024-01-28 17:08:44'),
(20, 59, 2, 'Device Registration', 'ডিভাইস নিবন্ধন', 1, NULL, 20, NULL, '2023-10-07 04:26:13', '2024-01-28 17:09:00'),
(21, 55, 2, 'Menu', 'তালিকা', 1, NULL, 21, NULL, '2023-10-07 04:27:07', '2024-01-28 17:09:22'),
(22, 47, 75, 'Budget Management', 'বাজেট ব্যবস্থাপনা', 1, NULL, 22, NULL, '2023-10-07 10:37:03', '2024-01-28 17:10:03'),
(23, NULL, 75, 'Manage Allotment', 'বরাদ্দ পরিচালনা', NULL, NULL, 23, NULL, '2023-10-07 10:37:40', '2024-01-28 17:34:26'),
(24, 50, 23, 'Allotment Entry', 'বরাদ্দ এন্ট্রি', 1, NULL, 24, NULL, '2023-10-07 17:13:19', '2024-01-28 17:34:58'),
(25, 51, 23, 'Allotment List', 'বরাদ্দ তালিকা', 1, NULL, 25, NULL, '2023-10-07 17:13:43', '2024-01-28 17:35:39'),
(26, NULL, NULL, 'Application & Selection', 'আবেদন ও নির্বাচন', NULL, NULL, 26, NULL, '2023-10-07 17:14:10', '2024-01-28 17:36:46'),
(27, 64, 26, 'Online Application', 'অনলাইন আবেদন', 1, NULL, 27, NULL, '2023-10-07 17:14:40', '2024-01-30 17:40:57'),
(28, 64, 26, 'Application List', 'আবেদনের তালিকা', 1, NULL, 28, NULL, '2023-10-07 17:15:06', '2024-01-28 17:37:17'),
(29, NULL, NULL, 'Beneficiary Management', 'সুবিধাভোগী ব্যবস্থাপনা', NULL, NULL, 29, NULL, '2023-10-07 17:15:31', '2024-01-29 16:23:39'),
(30, 98, 87, 'Beneficiary Information', 'সুবিধাভোগী তালিকা', 1, NULL, 1, NULL, '2023-10-07 17:29:59', '2024-01-29 13:27:55'),
(31, 114, 29, 'Digital ID Card', 'ডিজিটাল আইডি কার্ড', 1, NULL, 31, NULL, '2023-10-07 17:31:01', '2024-01-29 15:49:18'),
(32, 183, 29, 'Beneficiary Exit', 'সুবিধাভোগী প্রস্থান', 1, NULL, 32, NULL, '2023-10-07 17:31:32', '2024-01-29 16:20:29'),
(33, 102, 29, 'Committee List', 'কমিটির তালিকা', 1, NULL, 33, NULL, '2023-10-07 17:32:00', '2024-01-29 13:29:53'),
(36, NULL, 26, 'PMT Score', 'পিএমটি স্কোর', 1, NULL, 36, NULL, '2023-10-30 02:09:04', '2024-01-29 13:31:09'),
(37, 190, 29, 'Beneficiary Shifting', 'সুবিধাভোগী স্থানান্তর', 1, NULL, 34, NULL, '2023-10-30 02:10:30', '2024-02-25 13:30:36'),
(38, NULL, NULL, 'Payroll Management', 'বেতন ব্যবস্থাপনা', NULL, NULL, 30, NULL, '2023-10-30 02:12:16', '2024-01-29 16:22:51'),
(39, NULL, NULL, 'Emergency Payment', 'জরুরী পেমেন্ট', NULL, NULL, 29, NULL, '2023-10-30 02:32:42', '2024-01-28 17:39:25'),
(40, NULL, NULL, 'Grievance Management', 'অভিযোগ ব্যবস্থাপনা', NULL, NULL, 29, NULL, '2023-10-30 02:35:11', '2024-01-29 13:24:38'),
(41, NULL, NULL, 'M&E and Reporting', 'এমএন্ডই এবং রিপোর্টিং', NULL, NULL, 29, NULL, '2023-10-30 02:42:16', '2024-01-29 13:26:04'),
(42, NULL, 42, 'Training Managment', 'প্রশিক্ষণ ব্যবস্থাপনা', NULL, NULL, 29, NULL, '2023-10-30 02:43:11', '2024-01-29 16:24:07'),
(43, NULL, NULL, 'API Manager', 'এপিআই ম্যানেজার', NULL, NULL, 29, NULL, '2023-10-30 02:44:05', '2024-01-29 13:27:23'),
(44, 30, 38, 'Approval Selection', 'অনুমোদন নির্বাচন', 1, NULL, 41, NULL, '2023-10-30 02:53:14', '2024-01-29 13:31:29'),
(45, 2, 38, 'Payment Processor', 'পেমেন্ট প্রসেসর', 1, NULL, 42, NULL, '2023-10-30 03:03:22', '2024-01-29 13:31:52'),
(46, 2, 38, 'Payroll Create', 'পেরোল তৈরি', 1, NULL, 43, NULL, '2023-10-30 03:04:08', '2024-01-29 13:32:39'),
(47, 4, 38, 'Payroll Approval', 'পেরোল অনুমোদন', 1, NULL, 44, NULL, '2023-10-30 03:04:42', '2024-01-29 13:33:20'),
(48, 2, 38, 'Beneficiary wise Tracking', 'সুবিধাভোগী ভিত্তিক ট্র্যাকিং', 1, NULL, 45, NULL, '2023-10-30 03:05:15', '2024-01-29 13:33:45'),
(50, 4, 39, 'Emergency Beneficiary List', 'জরুরী সুবিধাভোগী তালিকা', 1, NULL, 47, NULL, '2023-10-30 04:04:03', '2024-01-29 13:34:09'),
(51, 4, 39, 'Emergency Payroll List', 'জরুরী বেতনের তালিকা', 1, NULL, 48, NULL, '2023-10-30 04:04:44', '2024-01-29 13:34:36'),
(52, 4, 40, 'Grievance Setting', 'অভিযোগ সেটিং', 1, NULL, 49, NULL, '2023-10-30 04:59:44', '2024-01-29 13:35:01'),
(53, 149, 40, 'Grievance List', 'অভিযোগের তালিকা', 1, NULL, 50, NULL, '2023-10-30 05:02:53', '2024-01-29 16:16:30'),
(54, 3, 41, 'Survey List', 'সার্ভে তালিকা', 1, NULL, 51, NULL, '2023-10-30 05:04:40', '2024-01-29 13:35:51'),
(55, 157, 41, 'Survey', 'সার্ভে', 1, NULL, 52, NULL, '2023-10-30 05:06:06', '2024-01-29 16:21:53'),
(56, 3, 41, 'Report', 'রিপোর্ট', 1, NULL, 53, NULL, '2023-10-30 05:06:39', '2024-01-29 13:37:06'),
(57, 2, 41, 'BI Report', 'বিআই রিপোর্ট', 1, NULL, 54, NULL, '2023-10-30 05:07:13', '2024-01-29 13:39:11'),
(58, 2, 42, 'Trainer Information', 'প্রশিক্ষক তথ্য', 1, NULL, 55, NULL, '2023-10-30 05:10:43', '2024-01-29 13:40:17'),
(59, 3, 42, 'Training Circular', 'প্রশিক্ষণ সার্কুলার', 1, NULL, 56, NULL, '2023-10-30 05:12:46', '2024-01-29 13:40:40'),
(60, 2, 42, 'Trainer	Session', 'প্রশিক্ষক অধিবেশন', 1, NULL, 57, NULL, '2023-10-30 05:15:47', '2024-01-29 13:41:03'),
(61, 4, 42, 'Participant Info', 'অংশগ্রহণকারীর তথ্য', 1, NULL, 58, NULL, '2023-10-30 05:16:10', '2024-01-29 13:41:26'),
(62, 3, 42, 'Training Registration', 'প্রশিক্ষণ নিবন্ধন', 1, NULL, 59, NULL, '2023-10-30 05:17:05', '2024-01-29 13:41:52'),
(63, NULL, 42, 'Assessment', 'Assesment', 2, NULL, 60, NULL, '2023-10-30 05:17:22', '2023-10-30 05:17:51'),
(64, 2, 63, 'Exam Paper', 'প্রশ্নপত্র', 1, NULL, 61, NULL, '2023-10-30 05:18:54', '2024-01-29 13:43:12'),
(65, 4, 63, 'Exam Paper List', 'পরীক্ষার প্রশ্নপত্রের তালিকা', 1, NULL, 62, NULL, '2023-10-30 05:19:18', '2024-01-29 13:44:55'),
(66, 169, 42, 'Exam', 'পরীক্ষা', 1, NULL, 63, NULL, '2023-10-30 05:19:48', '2024-01-29 16:18:06'),
(67, 4, 63, 'Assessment List', 'মূল্যায়ন তালিকা', 1, NULL, 64, NULL, '2023-10-30 05:20:16', '2024-01-29 13:45:52'),
(68, 2, 43, 'List Of API', 'এপিআই -এর তালিকা', 1, NULL, 65, NULL, '2023-10-30 05:21:46', '2024-01-29 13:46:56'),
(69, 2, 43, 'API Create', 'এপিআই ক্রিয়েট', 1, NULL, 66, NULL, '2023-10-30 05:22:55', '2024-01-29 13:48:42'),
(70, 2, 43, 'API Data Receiver', 'এপিআই ডেটা রিসিভার', 1, NULL, 67, NULL, '2023-10-30 21:45:29', '2024-01-29 13:49:37'),
(71, 82, 36, 'Cut-Off Score', 'কাট -অফ স্কোর', 1, NULL, 68, NULL, '2023-10-30 23:15:16', '2024-01-29 13:50:27'),
(72, 86, 36, 'District Fixed Effect', 'District Fixed Effect', 1, NULL, 69, '2024-01-30 15:16:06', '2023-10-30 23:19:11', '2024-01-30 15:16:06'),
(73, 90, 36, 'Variable', 'ভ্যারিয়েবল', 1, NULL, 70, NULL, '2023-10-30 23:25:56', '2024-01-29 13:36:22'),
(74, 94, 36, 'Sub-Variable', 'Sub-Variable', 1, NULL, 71, '2024-01-30 15:16:50', '2023-10-30 23:28:12', '2024-01-30 15:16:50'),
(75, NULL, NULL, 'Budget & Allotment', 'বাজেট ও বরাদ্দ', NULL, NULL, 22, NULL, '2023-11-02 06:53:50', '2024-01-28 17:10:44'),
(76, 56, 2, 'IBCS-PRIMAX', 'ড্যাশবোর্ড', 2, 'https://www.ibcs-primax.com/', 7, '2024-01-30 17:37:58', '2023-11-02 08:57:48', '2024-01-30 17:37:58'),
(77, 179, 29, 'Committee Permission List', 'কমিটির অনুমতি তালিকা', 1, NULL, 34, NULL, '2023-10-07 17:32:00', '2024-01-30 18:00:19'),
(78, 174, NULL, 'General Setting', 'সেটিং', 1, NULL, 8, '2024-01-31 09:27:26', '2024-01-25 13:15:27', '2024-01-31 09:27:26'),
(79, NULL, NULL, 'Settings', 'সেটিংস', 1, NULL, 102, NULL, '2024-01-25 14:49:14', '2024-01-30 17:43:41'),
(80, 186, 79, 'Mobile Operator', 'মোবাইল অপারেটর', 1, NULL, 0, NULL, '2024-01-30 15:36:01', '2024-01-30 15:45:01'),
(81, 186, 87, 'Beneficiary Information (Active)', 'সুবিধাভোগী তথ্য (সক্রিয়)', 1, NULL, 5, NULL, '2024-02-18 12:30:48', '2024-02-27 18:01:31'),
(82, 188, 87, 'Beneficiary Information (Waiting List)', 'সুবিধাভোগী তথ্য (অপেক্ষার তালিকা)', 1, NULL, 10, NULL, '2024-02-18 12:33:52', '2024-02-18 12:33:52'),
(83, 187, 87, 'Beneficiary Information (Dead & Inactive)', 'সুবিধাভোগী তথ্য (মৃত ও নিষ্ক্রিয়)', 1, NULL, 15, NULL, '2024-02-18 12:34:48', '2024-02-18 12:34:48'),
(84, 189, 87, 'Beneficiary Information Delete Log', 'সুবিধাভোগী তথ্য লগ মুছুন', 1, NULL, 25, NULL, '2024-02-18 12:36:38', '2024-02-18 12:36:38'),
(85, 110, 87, 'Beneficiary Information Replacement Log', 'সুবিধাভোগী তথ্য প্রতিস্থাপন লগ', 1, NULL, 20, NULL, '2024-02-18 12:41:01', '2024-02-18 12:41:01'),
(86, NULL, 10, 'Allowance Program Submenu', 'Allowance Program Submenu', 2, 'https://ibcs-primax.com', 0, '2024-02-20 15:02:25', '2024-02-20 15:01:40', '2024-02-20 15:02:25'),
(87, NULL, 29, 'Beneficiary Information', 'উপকরভোগীর তথ্য', NULL, NULL, 5, NULL, '2024-02-27 18:00:52', '2024-02-27 18:03:36'),
(88, 196, 29, 'Beneficiary Dashboard', 'উপকরভোগীর ড্যাশবোর্ড', 1, NULL, 1, NULL, '2023-10-30 02:10:30', '2024-02-25 13:30:36');";


        // forgain key validation off
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Menu::truncate();
        // Execute the SQL query
        \DB::statement($menus);

        // $menus = [
        //     // ********************************
        //     // Dashboard
        //     // ********************************
        //     [
        //         'id' => 1,
        //         'page_link_id' => null,
        //         'parent_id' => null,
        //         'label_name_en' => 'Dashboard',
        //         'label_name_bn' => 'Dashboard',
        //         'link_type' => 2,
        //         'link' => '/dashboard',
        //         'order' => 1,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 02:41:56',
        //         'updated_at' => '2023-10-08 05:39:49',
        //     ],
        //     // ********************************
        //     // System Configuration
        //     // ********************************
        //     [
        //         'id' => 2,
        //         'page_link_id' => null,
        //         'parent_id' => null,
        //         'label_name_en' => 'System Configuration',
        //         'label_name_bn' => 'System Configuration',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 2,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 02:43:05',
        //         'updated_at' => '2023-10-07 02:43:05',
        //     ],

        //     // ********************************
        //     // System Configuration---> Demograpgics
        //     // ********************************
        //     [
        //         'id' => 3,
        //         'page_link_id' => null,
        //         'parent_id' => 2,
        //         'label_name_en' => 'Demographic Information',
        //         'label_name_bn' => 'Demographic Information',
        //         'link_type' => null,
        //         'link' => null,
        //         'order' => 3,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 02:44:11',
        //         'updated_at' => '2023-10-07 02:44:11',
        //     ],
        //     [
        //         'id' => 4,
        //         'page_link_id' => 2,
        //         'parent_id' => 3,
        //         'label_name_en' => 'Division',
        //         'label_name_bn' => 'Division',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 4,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 03:12:05',
        //         'updated_at' => '2023-10-07 03:12:05',
        //     ],
        //     [
        //         'id' => 5,
        //         'page_link_id' => 6,
        //         'parent_id' => 3,
        //         'label_name_en' => 'District',
        //         'label_name_bn' => 'District',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 5,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 03:22:06',
        //         'updated_at' => '2023-10-22 04:01:43',
        //     ],
        //     [
        //         'id' => 6,
        //         'page_link_id' => 12,
        //         'parent_id' => 3,
        //         'label_name_en' => 'City Corporation /  District Pouroshava',
        //         'label_name_bn' => 'City Corporation /  District Pouroshava',
        //         'link_type' => 1,
        //         'link' => '6',
        //         'order' => 6,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 03:25:38',
        //         'updated_at' => '2023-10-07 03:57:08',
        //     ],
        //     [
        //         'id' => 7,
        //         'page_link_id' => 14,
        //         'parent_id' => 3,
        //         'label_name_en' => 'Thana / Upazila',
        //         'label_name_bn' => 'Thana / Upazila',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 7,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 03:26:07',
        //         'updated_at' => '2023-10-22 04:02:10',
        //     ],
        //     [
        //         'id' => 8,
        //         'page_link_id' => 18,
        //         'parent_id' => 3,
        //         'label_name_en' => 'Union / Pourashava',
        //         'label_name_bn' => 'Union / Pourashava',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 8,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 03:26:34',
        //         'updated_at' => '2023-10-22 04:02:31',
        //     ],
        //     [
        //         'id' => 9,
        //         'page_link_id' => 22,
        //         'parent_id' => 3,
        //         'label_name_en' => 'Ward',
        //         'label_name_bn' => 'Ward',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 9,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 03:56:15',
        //         'updated_at' => '2023-10-22 04:02:50',
        //     ],
        //     [
        //         'id' => 10,
        //         'page_link_id' => 26,
        //         'parent_id' => 2,
        //         'label_name_en' => 'Allowance Program Management',
        //         'label_name_bn' => 'Allowance Program Management',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 10,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 03:58:09',
        //         'updated_at' => '2023-10-21 23:47:06',
        //     ],
        //     [
        //         'id' => 11,
        //         'page_link_id' => 30,
        //         'parent_id' => 2,
        //         'label_name_en' => 'Office Management',
        //         'label_name_bn' => 'Office Management',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 11,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 03:59:02',
        //         'updated_at' => '2023-10-22 04:03:12',
        //     ],
        //     [
        //         'id' => 12,
        //         'page_link_id' => 34,
        //         'parent_id' => 2,
        //         'label_name_en' => 'Financial Year Management',
        //         'label_name_bn' => 'Financial Year Management',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 12,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 04:04:30',
        //         'updated_at' => '2023-10-22 04:03:44',
        //     ],

        //     // ********************************
        //     // System Configuration---> User Management
        //     // ********************************
        //     [
        //         'id' => 13,
        //         'page_link_id' => null,
        //         'parent_id' => 2,
        //         'label_name_en' => 'User Management',
        //         'label_name_bn' => 'User Management',
        //         'link_type' => null,
        //         'link' => null,
        //         'order' => 13,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 04:06:06',
        //         'updated_at' => '2023-10-07 04:06:06',
        //     ],
        //     [
        //         'id' => 14,
        //         'page_link_id' => 42,
        //         'parent_id' => 13,
        //         'label_name_en' => 'Role List',
        //         'label_name_bn' => 'Role List',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 14,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 04:06:39',
        //         'updated_at' => '2023-10-22 04:04:51',
        //     ],
        //     [
        //         'id' => 15,
        //         'page_link_id' => 45,
        //         'parent_id' => 13,
        //         'label_name_en' => 'Role Permission',
        //         'label_name_bn' => 'Role Permission',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 15,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 10:23:26',
        //         'updated_at' => '2023-10-22 04:05:07',
        //     ],
        //     [
        //         'id' => 16,
        //         'page_link_id' => 38,
        //         'parent_id' => 13,
        //         'label_name_en' => 'User List',
        //         'label_name_bn' => 'User List',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 16,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 10:25:01',
        //         'updated_at' => '2023-10-22 04:05:19',
        //     ],
        //     [
        //         'id' => 17,
        //         'page_link_id' => 59,
        //         'parent_id' => 2,
        //         'label_name_en' => 'Device Registration',
        //         'label_name_bn' => 'Device Registration',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 17,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 10:26:13',
        //         'updated_at' => '2023-10-22 04:05:34',
        //     ],
        //     [
        //         'id' => 18,
        //         'page_link_id' => 55,
        //         'parent_id' => 2,
        //         'label_name_en' => 'Menu',
        //         'label_name_bn' => 'Menu',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 18,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 10:27:07',
        //         'updated_at' => '2023-10-21 23:45:39',
        //     ],

        //     // // ********************************
        //     // // Budget Management---> User Management
        //     // // ********************************
        //     [
        //         'id' => 19,
        //         'page_link_id' => null,
        //         'parent_id' => null,
        //         'label_name_en' => 'Budget & Management Allotment',
        //         'label_name_bn' => 'Budget & Management Allotment',
        //         'link_type' => 2,
        //         'link' => null,
        //         'order' => 19,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 16:37:03',
        //         'updated_at' => '2023-10-22 04:06:05',
        //     ],
        //     [
        //         'id' => 20,
        //         'page_link_id' => 47,
        //         'parent_id' => 19,
        //         'label_name_en' => 'Budget Management',
        //         'label_name_bn' => 'Budget Management',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 20,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 16:37:03',
        //         'updated_at' => '2023-10-22 04:06:05',
        //     ],
        //     // // ********************************
        //     // // Manage Allotment
        //     // // ********************************
        //     [
        //         'id' => 21,
        //         'page_link_id' => null,
        //         'parent_id' => 19,
        //         'label_name_en' => 'Manage Allotment',
        //         'label_name_bn' => 'Manage Allotment',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 21,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 16:37:40',
        //         'updated_at' => '2023-10-07 16:37:40',
        //     ],

        //     [
        //         'id' => 23,
        //         'page_link_id' => 50,
        //         'parent_id' => 21,
        //         'label_name_en' => 'Allotment Entry',
        //         'label_name_bn' => 'Allotment Entry',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 23,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 23:13:19',
        //         'updated_at' => '2023-10-22 04:07:31',
        //     ],
        //     [
        //         'id' => 24,
        //         'page_link_id' => 51,
        //         'parent_id' => 21,
        //         'label_name_en' => 'Allotment List',
        //         'label_name_bn' => 'Allotment List',
        //         'link_type' => 1,
        //         'link' => null,
        //         'order' => 24,
        //         'deleted_at' => null,
        //         'created_at' => '2023-10-07 23:13:43',
        //         'updated_at' => '2023-10-22 04:07:43',
        //     ],
        // ********************************
        // Manage Allotment
        // ********************************
        // [
        //     'id' => 20,
        //     'page_link_id' => null,
        //     'parent_id' => null,
        //     'label_name_en' => 'Manage Allotment',
        //     'label_name_bn' => 'Manage Allotment',
        //     'link_type' => null,
        //     'link' => null,
        //     'order' => 20,
        //     'deleted_at' => null,
        //     'created_at' => '2023-10-07 16:37:40',
        //     'updated_at' => '2023-10-07 16:37:40',
        // ],
        // [
        //     'id' => 21,
        //     'page_link_id' => 50,
        //     'parent_id' => 20,
        //     'label_name_en' => 'Allotment Entry',
        //     'label_name_bn' => 'Allotment Entry',
        //     'link_type' => 1,
        //     'link' => null,
        //     'order' => 21,
        //     'deleted_at' => '2023-10-22 04:08:09',
        //     'created_at' => '2023-10-07 23:13:19',
        //     'updated_at' => '2023-10-22 04:07:31',
        // ],
        // [
        //     'id' => 22,
        //     'page_link_id' => 51,
        //     'parent_id' => 20,
        //     'label_name_en' => 'Allotment List',
        //     'label_name_bn' => 'Allotment List',
        //     'link_type' => 1,
        //     'link' => null,
        //     'order' => 22,
        //     'deleted_at' => null,
        //     'created_at' => '2023-10-07 23:13:43',
        //     'updated_at' => '2023-10-22 04:07:43',
        // ],
        // ];

        // DB::table('menus')->insert($menus);
    }
}
