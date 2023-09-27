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
        for ($main=0; $main < count($datas) ; $main++) {
            $groupPermissions=$datas[$main]['module_name'];
            $menu = Menu::create([
                'page_link_id' => $datas[$main]['page_link_id'],
                'label_name_en' => $datas[$main]['label_name_en'],
                'label_name_bn' => $datas[$main]['label_name_bn'],
                'link_type' => $datas[$main]['link_type'],
                'link' => $datas[$main]['link'],
                'order' => $datas[$main]['order'],
                'parent_id' => array_key_exists('parent_id',$datas[$main])? $datas[$main]['parent_id']:NULL,
                ]);
            for ($sub=0; $sub < count($datas[$main]['sub_menu']); $sub++) {
            //create permissions
            $permission = Menu::create([
                'name' => $datas[$i]['permissions'][$j]['name'],
                'module_name' => $groupPermissions,
                'sub_module_name' => $subModulePermissions,
                'guard_name' => $guardPermissions,
                'page_url' => $datas[$i]['permissions'][$j]['page_url'],
                'parent_page' => $datas[$i]['permissions'][$j]['parent_page'],
                ]);
                $sub = Menu::create([
                    'page_link_id' => $datas[$main]['sub_menu'][$sub]['page_link_id'],
                    'label_name_en' => $datas[$main]['sub_menu'][$sub]['label_name_en'],
                    'label_name_bn' => $datas[$main]['sub_menu'][$sub]['label_name_bn'],
                    'link_type' => $datas[$main]['sub_menu'][$sub]['link_type'],
                    'link' => $datas[$main]['sub_menu'][$sub]['link'],
                    'order' => $datas[$main]['sub_menu'][$sub]['order'],
                    'parent_id' => array_key_exists('parent_id',$datas[$main])? $datas[$main]['parent_id']:NULL,
                    ]);

            }

        }

        foreach ($datas as $data) {
            $menu = Menu::create([
                'page_link_id' => $data['page_link_id'],
                'label_name_en' => $data['label_name_en'],
                'label_name_bn' => $data['label_name_bn'],
                'link_type' => $data['link_type'],
                'link' => $data['link'],
                'order' => $data['order'],
                'parent_id' => array_key_exists('parent_id',$data)? $data['parent_id']:NULL,
                ]);
                if (isset($data['sub_menu'])) {
                    $sub_menus = $data['sub_menu'];
                    $sub_menus['parent_id'] = $menu->id;
         // sub_menu has multiple sub_menu
                    foreach ($sub_menus as $key => $sub_me) {
                        // dd($sub_menus);
                        // dd(array_key_exists('page_link_id',$sub_menus[$key]));
                        $sub_menu = Menu::create([
                            'page_link_id' => NULL,
                            'label_name_en' => $sub_menus[$key]['label_name_en'],
                            'label_name_bn' => $sub_me['label_name_bn'],
                            'link_type' => $sub_me['link_type'],
                            'link' => $sub_me['link'],
                            'order' => $sub_me['order'],
                            'parent_id' => $sub_menus['parent_id'],
                            ]);
                        // $sub_menu = new Menu;
                        // $sub_menu->parent_id = $sub_menus['parent_id'];
                        // $sub_menu->page_link_id = array_key_exists('page_link_id',$sub_me)? $sub_me['page_link_id']:NULL;

                        // $sub_menu->label_name_en = $sub_me['label_name_en'];
                        // $sub_menu->label_name_bn = $sub_me['label_name_bn'];
                        // $sub_menu->link_type = $sub_me['link_type'];
                        // $sub_menu->link = $sub_me['link'];
                        // $sub_menu->order = $sub_me['order'];
                        // $sub_menu->save();

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
