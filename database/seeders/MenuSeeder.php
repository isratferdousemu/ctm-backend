<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // link_type 1=internal, 2=external
        // order for menu order
        // page_link_id = null for main menu
        // link_type == null for main menu
        $datas = [
            [
                'page_link_id' => null,
                'parent_id' => null,
                'label_name_en' => 'Dashboard',
                'label_name_bn' => 'ড্যাশবোর্ড',
                "link_type"=>null,
                "link"=>'',
                "order"=>1,
            ],
            [
                'parent_id' => null,
                'page_link_id' => null,
                'label_name_en' => 'System Configuration',
                'label_name_bn' => 'সিস্টেম কনফিগারেশন',
                "link_type"=>null,
                "link"=>null,
                "order"=>2,
                "sub_menu"=>[
                    [
                'page_link_id' => null,
                'label_name_en' => 'Demographic Info',
                'label_name_bn' => 'Demographic Info',
                "link_type"=>null,
                "link"=>null,
                "order"=>1,
                    "sub_sub_menu"=>[
                        [
                        'page_link_id' => 2,
                        'label_name_en' => 'Division',
                        'label_name_bn' => 'বিভাগ',
                        "link_type"=>1,
                        "link"=>null,
                        "order"=>1,
                        ]
                        ]
                        ],
                    [
                'page_link_id' => null,
                'label_name_en' => 'User Management',
                'label_name_bn' => 'User Management',
                "link_type"=>null,
                "link"=>null,
                "order"=>2,
                    "sub_sub_menu"=>[
                        [
                        'page_link_id' => 3,
                        'label_name_en' => 'User Role',
                        'label_name_bn' => 'User Role',
                        "link_type"=>1,
                        "link"=>null,
                        "order"=>1,
                        ]
                        ]
                        ],

                    ]
            ],
            [
                'parent_id' => null,
                'page_link_id' => null,
                'label_name_en' => 'Budget Management',
                'label_name_bn' => 'বাজেট ব্যবস্থাপনা',
                "link_type"=>null,
                "link"=>'',
                "order"=>1,
            ],
            [
                'parent_id' => null,
                'page_link_id' => null,
                'label_name_en' => 'Allotment Management',
                'label_name_bn' => 'বরাদ্দ ব্যবস্থাপনা',
                "link_type"=>null,
                "link"=>'',
                "order"=>1,
            ],
            [
                'parent_id' => null,
                'page_link_id' => null,
                'label_name_en' => 'Application & Selection',
                'label_name_bn' => 'বরাদ্দ ব্যবস্থাপনা',
                "link_type"=>null,
                "link"=>'',
                "order"=>1,
            ],
            [
                'parent_id' => null,
                'page_link_id' => null,
                'label_name_en' => 'Beneficiary Management',
                'label_name_bn' => 'বরাদ্দ ব্যবস্থাপনা',
                "link_type"=>null,
                "link"=>'',
                "order"=>1,
            ],
            [
                'parent_id' => null,
                'page_link_id' => null,
                'label_name_en' => 'Payroll Management',
                'label_name_bn' => 'বরাদ্দ ব্যবস্থাপনা',
                "link_type"=>null,
                "link"=>'',
                "order"=>1,
            ],
            [
                'parent_id' => null,
                'page_link_id' => null,
                'label_name_en' => 'Emergency Payment',
                'label_name_bn' => 'বরাদ্দ ব্যবস্থাপনা',
                "link_type"=>null,
                "link"=>'',
                "order"=>1,
            ],
            [
                'parent_id' => null,
                'page_link_id' => null,
                'label_name_en' => 'Grivance Management',
                'label_name_bn' => 'বরাদ্দ ব্যবস্থাপনা',
                "link_type"=>null,
                "link"=>'',
                "order"=>1,
            ],
            [
                'parent_id' => null,
                'page_link_id' => null,
                'label_name_en' => 'M&E Reporting',
                'label_name_bn' => 'বরাদ্দ ব্যবস্থাপনা',
                "link_type"=>null,
                "link"=>'',
                "order"=>1,
            ],
            [
                'parent_id' => null,
                'page_link_id' => null,
                'label_name_en' => 'Training Management',
                'label_name_bn' => 'বরাদ্দ ব্যবস্থাপনা',
                "link_type"=>null,
                "link"=>'',
                "order"=>1,
            ],
            [
                'parent_id' => null,
                'page_link_id' => null,
                'label_name_en' => 'General Setting',
                'label_name_bn' => 'বরাদ্দ ব্যবস্থাপনা',
                "link_type"=>null,
                "link"=>'',
                "order"=>1,
            ],
            [
                'parent_id' => null,
                'page_link_id' => null,
                'label_name_en' => 'Information Tracking',
                'label_name_bn' => 'বরাদ্দ ব্যবস্থাপনা',
                "link_type"=>null,
                "link"=>'',
                "order"=>1,
            ],
            [
                'parent_id' => null,
                'page_link_id' => null,
                'label_name_en' => 'Activity Tracking',
                'label_name_bn' => 'বরাদ্দ ব্যবস্থাপনা',
                "link_type"=>null,
                "link"=>'',
                "order"=>1,
            ],

        ];

        //  menu array list using this keys page_link_id,label_name_en,label_name_bn,link_type,link,order,paren_id and insert into menus table using Menu model ORM if you want to add sub menu then add sub_menu key and insert sub menu data using Menu model ORM if you want to add sub sub menu then add sub_sub_menu key and insert sub sub menu data using Menu model ORM

        foreach ($datas as $data) {
            $menu = Menu::create([
                'page_link_id' => $data['page_link_id'],
                'label_name_en' => $data['label_name_en'],
                'label_name_bn' => $data['label_name_bn'],
                'link_type' => $data['link_type'],
                'link' => $data['link'],
                'order' => $data['order'],
                'parent_id' => $data['parent_id'],
                ]);

            if (isset($data['sub_menu'])) {
                $sub_menus = $data['sub_menu'];
                $sub_menus['parent_id'] = $menu->id;
     // sub_menu has multiple sub_menu
                foreach ($sub_menus as $sub_me) {
                     dd(type_of($sub_me['label_name_en']));
                    $sub_menu = new Menu;
                    $sub_menu->page_link_id = $sub_me['page_link_id'];
                    $sub_menu->label_name_en = $sub_me['label_name_en'];
                    $sub_menu->label_name_bn = $sub_me['label_name_bn'];
                    $sub_menu->link_type = $sub_me['link_type'];
                    $sub_menu->link = $sub_me['link'];
                    $sub_menu->order = $sub_me['order'];
                    $sub_menu->parent_id = $sub_menus['parent_id'];
                    $sub_menu->save();

                    if (isset($sub_menu['sub_sub_menu'])) {
                        $sub_sub_menus = $sub_menu['sub_sub_menu'];
                        $sub_sub_menus['parent_id'] = $sub_menu->id;
                        foreach ($sub_sub_menus as $sub_sub_menu) {
                            $sub_sub_menu = Menu::create([
                                'page_link_id' => $sub_sub_menu['page_link_id'],
                                'label_name_en' => $sub_sub_menu['label_name_en'],
                                'label_name_bn' => $sub_sub_menu['label_name_bn'],
                                'link_type' => $sub_sub_menu['link_type'],
                                'link' => $sub_sub_menu['link'],
                                'order' => $sub_sub_menu['order'],
                                'parent_id' => $sub_sub_menus['parent_id'],
                                ]);
                        }
                    }
                }



            }
        }







    }
}
