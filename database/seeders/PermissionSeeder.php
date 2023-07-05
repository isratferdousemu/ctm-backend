<?php

namespace Database\Seeders;

use App\Http\Traits\PermissionTrait;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    use PermissionTrait;


    private $guard = 'sanctum';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [

            //dashboard
            [
            'group_name' => $this->permissionGroupAdminDashboard,
            'guard_name' => $this->guard,
'permissions'=>[
                'main-dashboard-filter',
                'main-dashboard-total-revenue',
            ]
            ],

                //expense managment
                [
                'group_name' => $this->permissionGroupAdminExpense,
                'guard_name' => $this->guard,
'permissions'=>[
                    'main-expense-category-list',
                    'main-expense-category-create',
                    'main-expense-category-edit',
                    'main-expense-category-delete',
                    'main-expense-list',
                    'main-expense-create',
                    'main-expense-edit',
                    'main-expense-delete',
                    'main-expense-show',
                    'main-expense-status',
                ]
            ],

            //Support ticket managment
                [
                'group_name' => $this->permissionGroupAdminSupport,
                'guard_name' => $this->guard,
'permissions'=>[
                    'main-support-category-list',
                    'main-support-category-create',
                    'main-support-category-edit',
                    'main-support-category-delete',
                    'main-support-category-status',
                    'main-support-sub-category-list',
                    'main-support-sub-category-create',
                    'main-support-sub-category-edit',
                    'main-support-sub-category-delete',
                    'main-support-request-list',
                    'main-support-request-list-priority',
                    'main-support-request-list-assign',
                    'main-support-list',
                    'main-support-list-reassign',

                ]
            ],

            //Setting managment
            [
            'group_name' => $this->permissionGroupAdminSetting,
            'guard_name' => $this->guard,
'permissions'=>[
                'main-setting-activity-log',
                'main-setting-system',
                'main-setting-system-update',
                'main-setting-vat',
                'main-setting-mail',
                'main-setting-api',
                'main-setting-staff-permission',
                'main-setting-staff-permission-role-list',
                'main-setting-staff-permission-role-create',
                'main-setting-staff-permission-role-edit',
                'main-setting-staff-permission-role-delete',

            ]
            ],


                            // admin permissions end
                            // admin permissions end




                    ];
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

                    for ($i=0; $i < count($permissions) ; $i++) {
                        $groupPermissions=$permissions[$i]['group_name'];
                        $guardPermissions=$permissions[$i]['guard_name'];
                        for ($j=0; $j < count($permissions[$i]['permissions']); $j++) {
                        //create permissions
                        $permission = Permission::create([
                            'name' => $permissions[$i]['permissions'][$j],
                            'group_name' => $groupPermissions,
                            'guard_name' => $guardPermissions,
                            ]);

                        }

                        }
    }
}
