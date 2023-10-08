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
        $per = ['create', 'list', 'edit','delete'];
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
                ["name"=>"division-view", "page_url"=>"/system-configuration/division", "parent_page"=>1],
                ["name"=>"division-edit", "page_url"=>"/system-configuration/division/edit/:id", "parent_page"=>1],
                ["name"=>"division-delete", "page_url"=>"/system-configuration/division", "parent_page"=>1],

                ["name"=>"district-create", "page_url"=>"/system-configuration/district/create", "parent_page"=>1],
                ["name"=>"district-view", "page_url"=>"/system-configuration/district", "parent_page"=>1],
                ["name"=>"district-edit", "page_url"=>"/system-configuration/district/edit/:id", "parent_page"=>1],
                ["name"=>"district-delete", "page_url"=>"/system-configuration/district", "parent_page"=>1],

                ["name"=>"city-create", "page_url"=>"/system-configuration/city/create", "parent_page"=>1],
                ["name"=>"city-view", "page_url"=>"/system-configuration/city", "parent_page"=>1],
                ["name"=>"city-edit", "page_url"=>"/system-configuration/city/edit/:id", "parent_page"=>1],
                ["name"=>"city-delete", "page_url"=>"/system-configuration/city", "parent_page"=>1],

                ["name"=>"thana-create", "page_url"=>"/system-configuration/thana/create", "parent_page"=>1],
                ["name"=>"thana-view", "page_url"=>"/system-configuration/thana", "parent_page"=>1],
                ["name"=>"thana-edit", "page_url"=>"/system-configuration/thana/edit/:id", "parent_page"=>1],
                ["name"=>"thana-delete", "page_url"=>"/system-configuration/thana", "parent_page"=>1],

                ["name"=>"union-create", "page_url"=>"/system-configuration/union/create", "parent_page"=>1],
                ["name"=>"union-view", "page_url"=>"/system-configuration/union", "parent_page"=>1],
                ["name"=>"union-edit", "page_url"=>"/system-configuration/union/edit/:id", "parent_page"=>1],
                ["name"=>"union-delete", "page_url"=>"/system-configuration/union", "parent_page"=>1],

                ["name"=>"ward-create", "page_url"=>"/system-configuration/ward/create", "parent_page"=>1],
                ["name"=>"ward-view", "page_url"=>"/system-configuration/ward", "parent_page"=>1],
                ["name"=>"ward-edit", "page_url"=>"/system-configuration/ward/edit/:id", "parent_page"=>1],
                ["name"=>"ward-delete", "page_url"=>"/system-configuration/ward", "parent_page"=>1]
            ]
        ],
        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->subAllowanceProgramManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"allowance-create", "page_url"=>"/system-configuration/allowance/create", "parent_page"=>0],
                ["name"=>"allowance-view", "page_url"=>"/system-configuration/allowance", "parent_page"=>0],
                ["name"=>"allowance-edit", "page_url"=>"/system-configuration/allowance/edit/:id", "parent_page"=>0],
                ["name"=>"allowance-delete", "page_url"=>"/system-configuration/allowance", "parent_page"=>0]
            ]
        ],
        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->subOfficeInformationManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"office-create", "page_url"=>"/system-configuration/office/create", "parent_page"=>0],
                ["name"=>"office-view", "page_url"=>"/system-configuration/office", "parent_page"=>0],
                ["name"=>"office-edit", "page_url"=>"/system-configuration/office/edit/:id", "parent_page"=>0],
                ["name"=>"office-delete", "page_url"=>"/system-configuration/office", "parent_page"=>0]
            ]
        ],
        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->subFinancialInformationManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"financial-create", "page_url"=>"/system-configuration/financial/create", "parent_page"=>0],
                ["name"=>"financial-view", "page_url"=>"/system-configuration/financial", "parent_page"=>0],
                ["name"=>"financial-edit", "page_url"=>"/system-configuration/financial/edit/:id", "parent_page"=>0],
                ["name"=>"financial-delete", "page_url"=>"/system-configuration/financial", "parent_page"=>0]
            ]
        ],
        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->subUserManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"user-create", "page_url"=>"/system-configuration/user/create", "parent_page"=>1],
                ["name"=>"user-view", "page_url"=>"/system-configuration/user", "parent_page"=>1],
                ["name"=>"user-edit", "page_url"=>"/system-configuration/user/edit/:id", "parent_page"=>1],
                ["name"=>"user-delete", "page_url"=>"/system-configuration/user", "parent_page"=>1]
            ]
        ],

        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->subRoleManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"role-create", "page_url"=>"/system-configuration/role/create", "parent_page"=>1],
                ["name"=>"role-view", "page_url"=>"/system-configuration/role", "parent_page"=>1],
                ["name"=>"role-edit", "page_url"=>"/system-configuration/role/edit/:id", "parent_page"=>1],
                ["name"=>"role-delete", "page_url"=>"/system-configuration/role", "parent_page"=>1],
                ]
        ],

        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->subDeviceRegistrationManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"device-create", "page_url"=>"/system-configuration/device/create", "parent_page"=>0],
                ["name"=>"device-view", "page_url"=>"/system-configuration/device", "parent_page"=>0],
                ["name"=>"device-edit", "page_url"=>"/system-configuration/device/edit/:id", "parent_page"=>0],
                ["name"=>"device-delete", "page_url"=>"/system-configuration/device", "parent_page"=>0]
            ]
        ],
        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->menuManagement,
            'guard_name' => $this->guard,
            'permissions' => [
                ["name"=> "menu-create", "page_url"=>"/system-configuration/menu/create", "parent_page"=>0],
                ["name"=> "menu-view", "page_url"=>"/system-configuration/menu", "parent_page"=>0],
                ["name"=> "menu-edit", "page_url"=>"/system-configuration/menu/edit/:id", "parent_page"=>0],
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
                ["name"=>"application-entry-create", "page_url"=>"/application-management/application/create", "parent_page"=>1],
                ["name"=>"application-entry-view", "page_url"=>"/application-management/application", "parent_page"=>1],
                ["name"=>"application-entry-edit", "page_url"=>"/application-management/application/edit/:id", "parent_page"=>1],
                ["name"=>"application-entry-delete", "page_url"=>"/application-management/application", "parent_page"=>1],

                ["name"=>"primaryUnion-create", "page_url"=>"/application-management/primary-selection-union/create", "parent_page"=>1],
                ["name"=>"primaryUnion-view", "page_url"=>"/application-management/primary-selection-union", "parent_page"=>1],
                ["name"=>"primaryUnion-edit", "page_url"=>"/application-management/primary-selection-union/edit/:id", "parent_page"=>1],
                ["name"=>"primaryUnion-delete", "page_url"=>"/application-management/primary-selection-union", "parent_page"=>1],

                ["name"=>"primaryUpazila-create", "page_url"=>"/application-management/primary-selection-upazila/create", "parent_page"=>1],
                ["name"=>"primaryUpazila-view", "page_url"=>"/application-management/primary-selection-upazila", "parent_page"=>1],
                ["name"=>"primaryUpazila-edit", "page_url"=>"/application-management/primary-selection-upazila/edit/:id", "parent_page"=>1],
                ["name"=>"primaryUpazila-delete", "page_url"=>"/application-management/primary-selection-upazila", "parent_page"=>1],

                ["name"=>"final-view-create", "page_url"=>"/application-management/final/create", "parent_page"=>1],
                ["name"=>"final-view-view", "page_url"=>"/application-management/final", "parent_page"=>1],
                ["name"=>"final-view-edit", "page_url"=>"/application-management/final/edit/:id", "parent_page"=>1],
                ["name"=>"final-view-delete", "page_url"=>"/application-management/final", "parent_page"=>1],

                ["name"=>"approval-view-create", "page_url"=>"/application-management/approval/create", "parent_page"=>1],
                ["name"=>"approval-view-view", "page_url"=>"/application-management/approval", "parent_page"=>1],
                ["name"=>"approval-view-edit", "page_url"=>"/application-management/approval/edit/:id", "parent_page"=>1],
                ["name"=>"approval-view-delete", "page_url"=>"/application-management/approval", "parent_page"=>1]

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
                ["name"=>"beneficiaryInfo-create", "page_url"=>"/beneficiary-management/beneficiary-info/create", "parent_page"=>1],
                ["name"=>"beneficiaryInfo-view", "page_url"=>"/beneficiary-management/beneficiary-info", "parent_page"=>1],
                ["name"=>"beneficiaryInfo-edit", "page_url"=>"/beneficiary-management/beneficiary-info/edit/:id", "parent_page"=>1],
                ["name"=>"beneficiaryInfo-delete", "page_url"=>"/beneficiary-management/beneficiary-info", "parent_page"=>1]

            ]
        ],
        [
            'module_name' => $this->modulePermissionBeneficiaryManagement,
            'sub_module_name' => $this->subCommitteeInformation,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"committee-create", "page_url"=>"/beneficiary-management/committe/create", "parent_page"=>1],
                ["name"=>"committee-view", "page_url"=>"/beneficiary-management/committe", "parent_page"=>1],
                ["name"=>"committee-edit", "page_url"=>"/beneficiary-management/committe/edit/:id", "parent_page"=>1],
                ["name"=>"committee-delete", "page_url"=>"/beneficiary-management/committe", "parent_page"=>1]
            ]
        ],
        [
            'module_name' => $this->modulePermissionBeneficiaryManagement,
            'sub_module_name' => $this->subAllocationInformation,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"allocation-create", "page_url"=>"/beneficiary-management/allocation/create", "parent_page"=>1],
                ["name"=>"allocation-view", "page_url"=>"/beneficiary-management/allocation", "parent_page"=>1],
                ["name"=>"allocation-edit", "page_url"=>"/beneficiary-management/allocation/edit/:id", "parent_page"=>1],
                ["name"=>"allocation-delete", "page_url"=>"/beneficiary-management/allocation", "parent_page"=>1]
            ]
        ],
        [
            'module_name' => $this->modulePermissionBeneficiaryManagement,
            'sub_module_name' => $this->subBeneficiaryReplacement,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"beneficiaryReplacement-create", "page_url"=>"/beneficiary-management/beneficiary-replacement/create", "parent_page"=>1],
                ["name"=>"beneficiaryReplacement-view", "page_url"=>"/beneficiary-management/beneficiary-replacement", "parent_page"=>1],
                ["name"=>"beneficiaryReplacement-edit", "page_url"=>"/beneficiary-management/beneficiary-replacement/edit/:id", "parent_page"=>1],
                ["name"=>"beneficiaryReplacement-delete", "page_url"=>"/beneficiary-management/beneficiary-replacement", "parent_page"=>1]

            ]
        ],
        [
            'module_name' => $this->modulePermissionBeneficiaryManagement,
            'sub_module_name' => $this->subBeneficiaryIDCard,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"beneficiaryCard-create", "page_url"=>"/beneficiary-management/beneficiary-card/create", "parent_page"=>1],
                ["name"=>"beneficiaryCard-view", "page_url"=>"/beneficiary-management/beneficiary-card", "parent_page"=>1],
                ["name"=>"beneficiaryCard-edit", "page_url"=>"/beneficiary-management/beneficiary-card/edit/:id", "parent_page"=>1],
                ["name"=>"beneficiaryCard-delete", "page_url"=>"/beneficiary-management/beneficiary-card", "parent_page"=>1]

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
                ["name"=>"payment-process-create", "page_url"=>"/payroll-management/payment-process/create", "parent_page"=>1],
                ["name"=>"payment-process-view", "page_url"=>"/payroll-management/payment-process", "parent_page"=>1],
                ["name"=>"payment-process-edit", "page_url"=>"/payroll-management/payment-process/edit/:id", "parent_page"=>1],
                ["name"=>"payment-process-delete", "page_url"=>"/payroll-management/payment-process", "parent_page"=>1]
            ]
        ],
        [
            'module_name' => $this->modulePermissionPayrollManagement,
            'sub_module_name' => $this->subAccountsInformation,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"account-information-create", "page_url"=>"/payroll-management/account-information/create", "parent_page"=>1],
                ["name"=>"account-information-view", "page_url"=>"/payroll-management/account-information", "parent_page"=>1],
                ["name"=>"account-information-edit", "page_url"=>"/payroll-management/account-information/edit/:id", "parent_page"=>1],
                ["name"=>"account-information-delete", "page_url"=>"/payroll-management/account-information", "parent_page"=>1]
            ]
        ],
        [
            'module_name' => $this->modulePermissionPayrollManagement,
            'sub_module_name' => $this->subPayrollGeneration,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"payroll-generation-create", "page_url"=>"/payroll-management/payroll-generation/create", "parent_page"=>1],
                ["name"=>"payroll-generation-view", "page_url"=>"/payroll-management/payroll-generation", "parent_page"=>1],
                ["name"=>"payroll-generation-edit", "page_url"=>"/payroll-management/payroll-generation/edit/:id", "parent_page"=>1],
                ["name"=>"payroll-generation-delete", "page_url"=>"/payroll-management/payroll-generation", "parent_page"=>1]
            ]
        ],
        [
            'module_name' => $this->modulePermissionPayrollManagement,
            'sub_module_name' => $this->subEmergencyPayment,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"emergency-payment-create", "page_url"=>"/payroll-management/emergency-payment/create", "parent_page"=>1],
                ["name"=>"emergency-payment-view", "page_url"=>"/payroll-management/emergency-payment", "parent_page"=>1],
                ["name"=>"emergency-payment-edit", "page_url"=>"/payroll-management/emergency-payment/edit/:id", "parent_page"=>1],
                ["name"=>"emergency-payment-delete", "page_url"=>"/payroll-management/emergency-payment", "parent_page"=>1]
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
                ["name"=>"grievanceCategory-create", "page_url"=>"/grievance-management/grievance-category/create", "parent_page"=>1],
                ["name"=>"grievanceCategory-view", "page_url"=>"/grievance-management/grievance-category", "parent_page"=>1],
                ["name"=>"grievanceCategory-edit", "page_url"=>"/grievance-management/grievance-category/edit/:id", "parent_page"=>1],
                ["name"=>"grievanceCategory-delete", "page_url"=>"/grievance-management/grievance-category", "parent_page"=>1]
            ]
        ],
        [
            'module_name' => $this->modulePermissionGrievanceManagement,
            'sub_module_name' => $this->subGrievanceInformation,
            'guard_name' => $this->guard,
            'permissions'=>[
                ["name"=>"grievanceInfo-create", "page_url"=>"/grievance-management/grievance-info/create", "parent_page"=>1],
                ["name"=>"grievanceInfo-view", "page_url"=>"/grievance-management/grievance-info", "parent_page"=>1],
                ["name"=>"grievanceInfo-edit", "page_url"=>"/grievance-management/grievance-info/edit/:id", "parent_page"=>1],
                ["name"=>"grievanceInfo-delete", "page_url"=>"/grievance-management/grievance-info", "parent_page"=>1]
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
                ["name"=>"reporting-system-create", "page_url"=>"/reporting-management/reporting-system/create", "parent_page"=>1],
                ["name"=>"reporting-system-view", "page_url"=>"/reporting-management/reporting-system", "parent_page"=>1],
                ["name"=>"reporting-system-edit", "page_url"=>"/reporting-management/reporting-system/edit/:id", "parent_page"=>1],
                ["name"=>"reporting-system-delete", "page_url"=>"/reporting-management/reporting-system", "parent_page"=>1]

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
                ["name"=>"training-create", "page_url"=>"training-management/training/create", "parent_page"=>1],
                ["name"=>"training-view", "page_url"=>"training-management/training", "parent_page"=>1],
                ["name"=>"training-edit", "page_url"=>"training-management/training/edit/:id", "parent_page"=>1],
                ["name"=>"training-delete", "page_url"=>"training-management/training", "parent_page"=>1]
            ]

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
