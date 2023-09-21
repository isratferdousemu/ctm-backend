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
        $per = ['create', 'list', 'edit', 'view','delete'];
        $permissions = [

        /* -------------------------------------------------------------------------- */
        /*                            system configuration                            */
        /* -------------------------------------------------------------------------- */
        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->subDemographicInformationManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"division-create", "page_url"=>"/system-configuration/division/create", "parent_page"=>1],
                ["name"=>"division-list", "page_url"=>"/system-configuration/division", "parent_page"=>1],
                ["name"=>"division-edit", "page_url"=>"/system-configuration/division/edit/:id", "parent_page"=>1],
                ["name"=>"division-view", "page_url"=>"/system-configuration/division/details/:id", "parent_page"=>1],
                ["name"=>"division-delete", "page_url"=>"/system-configuration/division", "parent_page"=>1],

                ["name"=>"district-create", "page_url"=>"/system-configuration/district/create", "parent_page"=>1],
                ["name"=>"district-list", "page_url"=>"/system-configuration/district", "parent_page"=>1],
                ["name"=>"district-edit", "page_url"=>"/system-configuration/district/edit/:id", "parent_page"=>1],
                ["name"=>"district-view", "page_url"=>"/system-configuration/district/details/:id", "parent_page"=>1],
                ["name"=>"district-delete", "page_url"=>"/system-configuration/district", "parent_page"=>1],

                ["name"=>"city-create", "page_url"=>"/system-configuration/city/create", "parent_page"=>1],
                ["name"=>"city-list", "page_url"=>"/system-configuration/city", "parent_page"=>1],
                ["name"=>"city-edit", "page_url"=>"/system-configuration/city/edit/:id", "parent_page"=>1],
                ["name"=>"city-view", "page_url"=>"/system-configuration/city/details/:id", "parent_page"=>1],
                ["name"=>"city-delete", "page_url"=>"/system-configuration/city", "parent_page"=>1],

                ["name"=>"thana-create", "page_url"=>"/system-configuration/thana/create", "parent_page"=>1],
                ["name"=>"thana-list", "page_url"=>"/system-configuration/thana", "parent_page"=>1],
                ["name"=>"thana-edit", "page_url"=>"/system-configuration/thana/edit/:id", "parent_page"=>1],
                ["name"=>"thana-view", "page_url"=>"/system-configuration/thana/details/:id", "parent_page"=>1],
                ["name"=>"thana-delete", "page_url"=>"/system-configuration/thana", "parent_page"=>1],

                ["name"=>"union-create", "page_url"=>"/system-configuration/union/create", "parent_page"=>1],
                ["name"=>"union-list", "page_url"=>"/system-configuration/union", "parent_page"=>1],
                ["name"=>"union-edit", "page_url"=>"/system-configuration/union/edit/:id", "parent_page"=>1],
                ["name"=>"union-view", "page_url"=>"/system-configuration/union/details/:id", "parent_page"=>1],
                ["name"=>"union-delete", "page_url"=>"/system-configuration/union", "parent_page"=>1],

                ["name"=>"ward-create", "page_url"=>"/system-configuration/ward/create", "parent_page"=>1],
                ["name"=>"ward-list", "page_url"=>"/system-configuration/ward", "parent_page"=>1],
                ["name"=>"ward-edit", "page_url"=>"/system-configuration/ward/edit/:id", "parent_page"=>1],
                ["name"=>"ward-view", "page_url"=>"/system-configuration/ward/details/:id", "parent_page"=>1],
                ["name"=>"ward-delete", "page_url"=>"/system-configuration/ward", "parent_page"=>1]
            ]
        ],
        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->subAllowanceProgramManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"allowance-create", "page_url"=>"/system-configuration/allowance/create", "parent_page"=>0],
                ["name"=>"allowance-list", "page_url"=>"/system-configuration/allowance", "parent_page"=>0],
                ["name"=>"allowance-edit", "page_url"=>"/system-configuration/allowance/edit/:id", "parent_page"=>0],
                ["name"=>"allowance-view", "page_url"=>"/system-configuration/allowance/details/:id", "parent_page"=>0],
                ["name"=>"allowance-delete", "page_url"=>"/system-configuration/allowance", "parent_page"=>0],
                ["name"=>"allowance-field-create", "page_url"=>"/system-configuration/allowance/create/field", "parent_page"=>0],
                'allowance-create',
                'allowance-list',
                'allowance-edit',
                'allowance-view',
                'allowance-delete',
                'allowance-field-create'
            ]
        ],
        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->subOfficeInformationManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"office-create", "page_url"=>"/system-configuration/office/create", "parent_page"=>0],
                ["name"=>"office-list", "page_url"=>"/system-configuration/office", "parent_page"=>0],
                ["name"=>"office-edit", "page_url"=>"/system-configuration/office/edit/:id", "parent_page"=>0],
                ["name"=>"office-view", "page_url"=>"/system-configuration/office/details/:id", "parent_page"=>0],
                ["name"=>"office-delete", "page_url"=>"/system-configuration/office", "parent_page"=>0]
            ]
        ],
        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->subFinancialInformationManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"financial-create", "page_url"=>"/system-configuration/financial/create", "parent_page"=>0],
                ["name"=>"financial-list", "page_url"=>"/system-configuration/financial", "parent_page"=>0],
                ["name"=>"financial-edit", "page_url"=>"/system-configuration/financial/edit/:id", "parent_page"=>0],
                ["name"=>"financial-view", "page_url"=>"/system-configuration/financial/details/:id", "parent_page"=>0],
                ["name"=>"financial-delete", "page_url"=>"/system-configuration/financial", "parent_page"=>0]
            ]
        ],
        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->subUserManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"role-create", "page_url"=>"/system-configuration/role/create", "parent_page"=>1],
                ["name"=>"role-list", "page_url"=>"/system-configuration/role", "parent_page"=>1],
                ["name"=>"role-edit", "page_url"=>"/system-configuration/role/edit/:id", "parent_page"=>1],
                ["name"=>"role-view", "page_url"=>"/system-configuration/role/details/:id", "parent_page"=>1],
                ["name"=>"role-delete", "page_url"=>"/system-configuration/role", "parent_page"=>1],

                ["name"=>"role-permission-create", "page_url"=>"/system-configuration/role-permission/create", "parent_page"=>1],

                ["name"=>"user-create", "page_url"=>"/system-configuration/user/create", "parent_page"=>1],
                ["name"=>"user-list", "page_url"=>"/system-configuration/user", "parent_page"=>1],
                ["name"=>"user-edit", "page_url"=>"/system-configuration/user/edit/:id", "parent_page"=>1],
                ["name"=>"user-view", "page_url"=>"/system-configuration/user/details/:id", "parent_page"=>1],
                ["name"=>"user-delete", "page_url"=>"/system-configuration/user", "parent_page"=>1]
            ]
        ],
        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->subDeviceRegistrationManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"device-create", "page_url"=>"/system-configuration/device/create", "parent_page"=>0],
                ["name"=>"device-list", "page_url"=>"/system-configuration/device", "parent_page"=>0],
                ["name"=>"device-edit", "page_url"=>"/system-configuration/device/edit/:id", "parent_page"=>0],
                ["name"=>"device-view", "page_url"=>"/system-configuration/device/details/:id", "parent_page"=>0],
                ["name"=>"device-delete", "page_url"=>"/system-configuration/device", "parent_page"=>0]
            ]
        ],
        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->menuManagement,
            'guard_name' => $this->guard,
            'permissions' => [
                ["name"=> "menu-create", "page_url"=>"/system-configuration/menu/create", "parent_page"=>0],
                ["name"=> "menu-list", "page_url"=>"/system-configuration/menu", "parent_page"=>0],
                ["name"=> "menu-edit", "page_url"=>"/system-configuration/menu/edit/:id", "parent_page"=>0],
                ["name"=> "menu-view", "page_url"=>"/system-configuration/menu/details/:id", "parent_page"=>0],
                ["name"=> "menu-delete", "page_url"=>"/system-configuration/menu", "parent_page"=>0]
            ]
        ],

        /* -------------------------------------------------------------------------- */
        /*                            Application Selection                           */
        /* -------------------------------------------------------------------------- */
        [
            'module_name' => $this->modulePermissionApplicationSelection,
            'sub_module_name' => $this->subOnlineApplicationManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                'application-entry-create',
                'application-entry-list',
                'application-entry-edit',
                'application-entry-view',
                'application-entry-delete',
                'primary-selection-union-list',
                'primary-selection-union-edit',
                'primary-selection-union-view',
                'primary-selection-union-delete',
                'primary-selection-upazila-list',
                'primary-selection-upazila-edit',
                'primary-selection-upazila-view',
                'primary-selection-upazila-delete',
                'final-list-list',
                'final-list-edit',
                'final-list-view',
                'final-list-delete',
                'approval-list-list',
                'approval-list-edit',
                'approval-list-view',
                'approval-list-delete',
                        ]
            ],


        /* -------------------------------------------------------------------------- */
        /*                           Beneficiary Management                           */
        /* -------------------------------------------------------------------------- */
        [
            'module_name' => $this->modulePermissionBeneficiaryManagement,
            'sub_module_name' => $this->subBeneficiaryInformationManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                'beneficiary-info-create',
                'beneficiary-info-list',
                'beneficiary-info-edit',
                'beneficiary-info-view',
                'beneficiary-info-delete',
                'beneficiary-info-active-list',
                'beneficiary-info-not-active-list',
                'beneficiary-info-waiting-active-list',
                        ]
        ],
        [
            'module_name' => $this->modulePermissionBeneficiaryManagement,
            'sub_module_name' => $this->subCommitteeInformation,
            'guard_name' => $this->guard,
            'permissions'=>[
                'committee-create',
                'committee-list',
                'committee-edit',
                'committee-view',
                'committee-delete'
                        ]
        ],
        [
            'module_name' => $this->modulePermissionBeneficiaryManagement,
            'sub_module_name' => $this->subAllocationInformation,
            'guard_name' => $this->guard,
            'permissions'=>[
                'allocation-create',
                'allocation-list',
                'allocation-edit',
                'allocation-view',
                'allocation-delete'
                        ]
        ],
        [
            'module_name' => $this->modulePermissionBeneficiaryManagement,
            'sub_module_name' => $this->subBeneficiaryReplacement,
            'guard_name' => $this->guard,
            'permissions'=>[
                'beneficiary-replacement-create',
                'beneficiary-replacement-list',
                'beneficiary-replacement-edit',
                'beneficiary-replacement-view',
                'beneficiary-replacement-delete'
                        ]
        ],
        [
            'module_name' => $this->modulePermissionBeneficiaryManagement,
            'sub_module_name' => $this->subBeneficiaryIDCard,
            'guard_name' => $this->guard,
            'permissions'=>[
                'beneficiary-card-create',
                'beneficiary-card-list',
                'beneficiary-card-edit',
                'beneficiary-card-view',
                'beneficiary-card-delete'
                        ]
        ],


        /* -------------------------------------------------------------------------- */
        /*                             Payroll Management                             */
        /* -------------------------------------------------------------------------- */
        [
            'module_name' => $this->modulePermissionPayrollManagement,
            'sub_module_name' => $this->subPaymentProcessorInformation,
            'guard_name' => $this->guard,
            'permissions'=>[
                'payment-process-create',
                'payment-process-list',
                'payment-process-edit',
                'payment-process-view',
                'payment-process-delete'
                        ]
        ],
        [
            'module_name' => $this->modulePermissionPayrollManagement,
            'sub_module_name' => $this->subAccountsInformation,
            'guard_name' => $this->guard,
            'permissions'=>[
                'account-information-create',
                'account-information-list',
                'account-information-edit',
                'account-information-view',
                'account-information-delete'
                        ]
        ],
        [
            'module_name' => $this->modulePermissionPayrollManagement,
            'sub_module_name' => $this->subPayrollGeneration,
            'guard_name' => $this->guard,
            'permissions'=>[
                'payroll-generation-create',
                'payroll-generation-list',
                'payroll-generation-edit',
                'payroll-generation-view',
                'payroll-generation-delete'
                        ]
        ],
        [
            'module_name' => $this->modulePermissionPayrollManagement,
            'sub_module_name' => $this->subEmergencyPayment,
            'guard_name' => $this->guard,
            'permissions'=>[
                'emergency-payment-create',
                'emergency-payment-list',
                'emergency-payment-edit',
                'emergency-payment-view',
                'emergency-payment-delete'
                        ]
        ],


        /* -------------------------------------------------------------------------- */
        /*                            Grievance Management                            */
        /* -------------------------------------------------------------------------- */
        [
            'module_name' => $this->modulePermissionGrievanceManagement,
            'sub_module_name' => $this->subGrievanceCategoryInformation,
            'guard_name' => $this->guard,
            'permissions'=>[
                'grievance-category-create',
                'grievance-category-list',
                'grievance-category-edit',
                'grievance-category-view',
                'grievance-category-delete'
                        ]
        ],
        [
            'module_name' => $this->modulePermissionGrievanceManagement,
            'sub_module_name' => $this->subGrievanceInformation,
            'guard_name' => $this->guard,
            'permissions'=>[
                'grievance-info-create',
                'grievance-info-list',
                'grievance-info-edit',
                'grievance-info-view',
                'grievance-info-delete'
                        ]
        ],

        /* -------------------------------------------------------------------------- */
        /*                              Reporting System                              */
        /* -------------------------------------------------------------------------- */
        [
            'module_name' => $this->modulePermissionReportingSystem,
            'sub_module_name' => $this->modulePermissionReportingSystem,
            'guard_name' => $this->guard,
            'permissions'=>[
                'reporting-system-create',
                'reporting-system-list',
                'reporting-system-edit',
                'reporting-system-view',
                'reporting-system-delete'
                        ]
            ],

        /* -------------------------------------------------------------------------- */
        /*                             Training Management                            */
        /* -------------------------------------------------------------------------- */
        [
            'module_name' => $this->modulePermissionTrainingManagement,
            'sub_module_name' => $this->modulePermissionTrainingManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                'training-list',
                'training-edit',
                'training-view',
                'training-delete'
            ],

            ],
                    ];

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            for ($i=0; $i < count($permissions) ; $i++) {
                $groupPermissions=$permissions[$i]['module_name'];
                $subModulePermissions=$permissions[$i]['sub_module_name'];
                $guardPermissions=$permissions[$i]['guard_name'];
                for ($j=0; $j < count($permissions[$i]['permissions']); $j++) {
                //create permissions
                $permission = Permission::create([
                    'name' => $permissions[$i]['permissions'][$j]['name'],
                    'module_name' => $groupPermissions,
                    'sub_module_name' => $subModulePermissions,
                    'guard_name' => $guardPermissions,
                    'page_url' => $permissions[$i]['permissions'][$j]['page_url'],
                    'parent_page' => $permissions[$i]['permissions'][$j]['parent_page'],
                    ]);
                }

            }
    }
}
