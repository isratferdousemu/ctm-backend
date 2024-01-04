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


    ///LATEST MENU ID = 160
    //  IF YOU ADD A NEW Permission the ID will start from one greater than the LAST MENU ID

    public function run()
    {
        $per = ['create', 'list', 'edit', 'delete'];
        $permissions = [

            /* -------------------------------------------------------------------------- */
            /*                            system configuration                            */
            /* -------------------------------------------------------------------------- */
            [
                'module_name' => $this->modulePermissionSystemConfiguration,
                'sub_module_name' => $this->subDemographicInformationManagement,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 1, "name" => "division-create", "page_url" => "/system-configuration/division/create", "parent_page" => 1],
                    ["id" => 2, "name" => "division-view", "page_url" => "/system-configuration/division", "parent_page" => 1],
                    ["id" => 3, "name" => "division-edit", "page_url" => "/system-configuration/division/edit/:id", "parent_page" => 1],
                    ["id" => 4, "name" => "division-delete", "page_url" => "/system-configuration/division", "parent_page" => 1],

                    ["id" => 5, "name" => "district-create", "page_url" => "/system-configuration/district/create", "parent_page" => 1],
                    ["id" => 6, "name" => "district-view", "page_url" => "/system-configuration/district", "parent_page" => 1],
                    ["id" => 7, "name" => "district-edit", "page_url" => "/system-configuration/district/edit/:id", "parent_page" => 1],
                    ["id" => 8, "name" => "district-delete", "page_url" => "/system-configuration/district", "parent_page" => 1],

                    ["id" => 9, "name" => "city-create", "page_url" => "/system-configuration/city/create", "parent_page" => 1],
                    ["id" => 10, "name" => "city-view", "page_url" => "/system-configuration/city", "parent_page" => 1],
                    ["id" => 11, "name" => "city-edit", "page_url" => "/system-configuration/city/edit/:id", "parent_page" => 1],
                    ["id" => 12, "name" => "city-delete", "page_url" => "/system-configuration/city", "parent_page" => 1],

                    ["id" => 13, "name" => "thana-create", "page_url" => "/system-configuration/thana/create", "parent_page" => 1],
                    ["id" => 14, "name" => "thana-view", "page_url" => "/system-configuration/thana", "parent_page" => 1],
                    ["id" => 15, "name" => "thana-edit", "page_url" => "/system-configuration/thana/edit/:id", "parent_page" => 1],
                    ["id" => 16, "name" => "thana-delete", "page_url" => "/system-configuration/thana", "parent_page" => 1],

                    ["id" => 17, "name" => "union-create", "page_url" => "/system-configuration/union/create", "parent_page" => 1],
                    ["id" => 18, "name" => "union-view", "page_url" => "/system-configuration/union", "parent_page" => 1],
                    ["id" => 19, "name" => "union-edit", "page_url" => "/system-configuration/union/edit/:id", "parent_page" => 1],
                    ["id" => 20, "name" => "union-delete", "page_url" => "/system-configuration/union", "parent_page" => 1],

                    ["id" => 21, "name" => "ward-create", "page_url" => "/system-configuration/ward/create", "parent_page" => 1],
                    ["id" => 22, "name" => "ward-view", "page_url" => "/system-configuration/ward", "parent_page" => 1],
                    ["id" => 23, "name" => "ward-edit", "page_url" => "/system-configuration/ward/edit/:id", "parent_page" => 1],
                    ["id" => 24, "name" => "ward-delete", "page_url" => "/system-configuration/ward", "parent_page" => 1]
                ]
            ],
            [
                'module_name' => $this->modulePermissionSystemConfiguration,
                'sub_module_name' => $this->subAllowanceProgramManagement,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 25, "name" => "allowance-create", "page_url" => "/system-configuration/allowance-program/create", "parent_page" => 0],
                    ["id" => 26, "name" => "allowance-view", "page_url" => "/system-configuration/allowance-program", "parent_page" => 0],
                    ["id" => 27, "name" => "allowance-edit", "page_url" => "/system-configuration/allowance-program/edit/:id", "parent_page" => 0],
                    ["id" => 28, "name" => "allowance-delete", "page_url" => "/system-configuration/allowance-program", "parent_page" => 0]
                ]
            ],
            [
                'module_name' => $this->modulePermissionSystemConfiguration,
                'sub_module_name' => $this->subOfficeInformationManagement,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 29, "name" => "office-create", "page_url" => "/system-configuration/office/create", "parent_page" => 0],
                    ["id" => 30, "name" => "office-view", "page_url" => "/system-configuration/office", "parent_page" => 0],
                    ["id" => 31, "name" => "office-edit", "page_url" => "/system-configuration/office/edit/:id", "parent_page" => 0],
                    ["id" => 32, "name" => "office-delete", "page_url" => "/system-configuration/office", "parent_page" => 0]
                ]
            ],
            [
                'module_name' => $this->modulePermissionSystemConfiguration,
                'sub_module_name' => $this->subFinancialInformationManagement,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 33, "name" => "financial-create", "page_url" => "/system-configuration/financial/create", "parent_page" => 0],
                    ["id" => 34, "name" => "financial-view", "page_url" => "/system-configuration/financial", "parent_page" => 0],
                    ["id" => 35, "name" => "financial-edit", "page_url" => "/system-configuration/financial/edit/:id", "parent_page" => 0],
                    ["id" => 36, "name" => "financial-delete", "page_url" => "/system-configuration/financial", "parent_page" => 0]
                ]
            ],
            [
                'module_name' => $this->modulePermissionSystemConfiguration,
                'sub_module_name' => $this->subUserManagement,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 37, "name" => "user-create", "page_url" => "/system-configuration/users/create", "parent_page" => 1],
                    ["id" => 38, "name" => "user-view", "page_url" => "/system-configuration/users", "parent_page" => 1],
                    ["id" => 39, "name" => "user-edit", "page_url" => "/system-configuration/users/edit/:id", "parent_page" => 1],
                    ["id" => 40, "name" => "user-delete", "page_url" => "/system-configuration/users", "parent_page" => 1]
                ]
            ],

            [
                'module_name' => $this->modulePermissionSystemConfiguration,
                'sub_module_name' => $this->subRoleManagement,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 41, "name" => "role-create", "page_url" => "/system-configuration/role/create", "parent_page" => 1],
                    ["id" => 42, "name" => "role-view", "page_url" => "/system-configuration/role", "parent_page" => 1],
                    ["id" => 43, "name" => "role-edit", "page_url" => "/system-configuration/role/edit/:id", "parent_page" => 1],
                    ["id" => 44, "name" => "role-delete", "page_url" => "/system-configuration/role", "parent_page" => 1],

                    ["id" => 45, "name" => "role-permission", "page_url" => "/system-configuration/role-permission", "parent_page" => 1],

                ]
            ],

            [
                'module_name' => $this->modulePermissionSystemConfiguration,
                'sub_module_name' => $this->subRolePermissionManagement,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 177, "name" => "role-permission-create", "page_url" => "/system-configuration/role-permission", "parent_page" => 1],
                ]
            ],

            [
                'module_name' => $this->modulePermissionBudgetManagement,
                'sub_module_name' => $this->budgetManagement,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 46, "name" => "budget-create", "page_url" => "/budget/create", "parent_page" => 1],
                    ["id" => 47, "name" => "budget-view", "page_url" => "/budget", "parent_page" => 1],
                    ["id" => 48, "name" => "budget-edit", "page_url" => "/budget/edit/:id", "parent_page" => 1],
                    ["id" => 49, "name" => "budget-delete", "page_url" => "/budget", "parent_page" => 1]
                ]
            ],
            [
                'module_name' => $this->modulePermissionAllotmentManagement,
                'sub_module_name' => $this->allotmentManagement,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 50, "name" => "allotment-create", "page_url" => "/allotment/create", "parent_page" => 0],
                    ["id" => 51, "name" => "allotment-view", "page_url" => "/allotment", "parent_page" => 0],
                    ["id" => 52, "name" => "allotment-edit", "page_url" => "/allotment/edit/:id", "parent_page" => 0],
                    ["id" => 53, "name" => "allotment-delete", "page_url" => "/allotment", "parent_page" => 0]
                ]
            ],
            [
                'module_name' => $this->modulePermissionSystemConfiguration,
                'sub_module_name' => $this->menuManagement,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 54, "name" => "menu-create", "page_url" => "/system-configuration/menu/create", "parent_page" => 0],
                    ["id" => 55, "name" => "menu-view", "page_url" => "/system-configuration/menu", "parent_page" => 0],
                    ["id" => 56, "name" => "menu-edit", "page_url" => "/system-configuration/menu/edit/:id", "parent_page" => 0],
                    ["id" => 57, "name" => "menu-delete", "page_url" => "/system-configuration/menu", "parent_page" => 0]
                ]
            ],
            [
                'module_name' => $this->modulePermissionSystemConfiguration,
                'sub_module_name' => $this->deviceRegistrationManagement,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 58, "name" => "device-registration-create", "page_url" => "/system-configuration/device-registration/create", "parent_page" => 0],
                    ["id" => 59, "name" => "device-registration-view", "page_url" => "/system-configuration/device-registration", "parent_page" => 0],
                    ["id" => 60, "name" => "device-registration-edit", "page_url" => "/system-configuration/device-registration/edit/:id", "parent_page" => 0],
                    ["id" => 61, "name" => "device-registration-delete", "page_url" => "/system-configuration/device-registration", "parent_page" => 0]
                ]
            ],

            /* -------------------------------------------------------------------------- */
            /*                            Application Selection                           */
            /* -------------------------------------------------------------------------- */
            [
                'module_name' => $this->modulePermissionApplicationSelection,
                'sub_module_name' => $this->subOnlineApplicationManagement,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 62, "name" => "application-entry-view", "page_url" => "/application-management/application", "parent_page" => 1],
                    ["id" => 63, "name" => "application-entry-edit", "page_url" => "/application-management/application/edit/:id", "parent_page" => 1],
                    ["id" => 64, "name" => "application-entry-delete", "page_url" => "/application-management/application", "parent_page" => 1],

                    ["id" => 65, "name" => "primaryUnion-create", "page_url" => "/application-management/primary-selection-union/create", "parent_page" => 1],
                    ["id" => 66, "name" => "primaryUnion-view", "page_url" => "/application-management/primary-selection-union", "parent_page" => 1],
                    ["id" => 67, "name" => "primaryUnion-edit", "page_url" => "/application-management/primary-selection-union/edit/:id", "parent_page" => 1],
                    ["id" => 68, "name" => "primaryUnion-delete", "page_url" => "/application-management/primary-selection-union", "parent_page" => 1],

                    ["id" => 69, "name" => "primaryUpazila-create", "page_url" => "/application-management/primary-selection-upazila/create", "parent_page" => 1],
                    ["id" => 70, "name" => "primaryUpazila-view", "page_url" => "/application-management/primary-selection-upazila", "parent_page" => 1],
                    ["id" => 71, "name" => "primaryUpazila-edit", "page_url" => "/application-management/primary-selection-upazila/edit/:id", "parent_page" => 1],
                    ["id" => 72, "name" => "primaryUpazila-delete", "page_url" => "/application-management/primary-selection-upazila", "parent_page" => 1],

                    ["id" => 73, "name" => "final-view-create", "page_url" => "/application-management/final/create", "parent_page" => 1],
                    ["id" => 74, "name" => "final-view-view", "page_url" => "/application-management/final", "parent_page" => 1],
                    ["id" => 75, "name" => "final-view-edit", "page_url" => "/application-management/final/edit/:id", "parent_page" => 1],
                    ["id" => 76, "name" => "final-view-delete", "page_url" => "/application-management/final", "parent_page" => 1],

                    ["id" => 77, "name" => "approval-view-create", "page_url" => "/application-management/approval/create", "parent_page" => 1],
                    ["id" => 78, "name" => "approval-view-view", "page_url" => "/application-management/approval", "parent_page" => 1],
                    ["id" => 79, "name" => "approval-view-edit", "page_url" => "/application-management/approval/edit/:id", "parent_page" => 1],
                    ["id" => 80, "name" => "approval-view-delete", "page_url" => "/application-management/approval", "parent_page" => 1],
                ]
            ],
            [
                'module_name' => $this->modulePermissionApplicationSelection,
                'sub_module_name' => $this->subPovertyScoreManagement,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 81, "name" => "poverty-cut-off-score-create", "page_url" => "/application-management/poverty-cut-off-score/create", "parent_page" => 1],
                    ["id" => 82, "name" => "poverty-cut-off-score-view", "page_url" => "/application-management/poverty-cut-off-score", "parent_page" => 1],
                    ["id" => 83, "name" => "poverty-cut-off-score-edit", "page_url" => "/application-management/poverty-cut-off-score/edit/:id", "parent_page" => 1],
                    ["id" => 84, "name" => "poverty-cut-off-score-delete", "page_url" => "/application-management/poverty-cut-off-score", "parent_page" => 1],

                    ["id" => 85, "name" => "district-fixed-effect-create", "page_url" => "/application-management/district-fixed-effect/create", "parent_page" => 1],
                    ["id" => 86, "name" => "district-fixed-effect-view", "page_url" => "/application-management/district-fixed-effect", "parent_page" => 1],
                    ["id" => 87, "name" => "district-fixed-effect-edit", "page_url" => "/application-management/district-fixed-effect/edit/:id", "parent_page" => 1],
                    ["id" => 88, "name" => "district-fixed-effect-delete", "page_url" => "/application-management/district-fixed-effect", "parent_page" => 1],

                    ["id" => 89, "name" => "variable-create", "page_url" => "/application-management/variable/create", "parent_page" => 1],
                    ["id" => 90, "name" => "variable-view", "page_url" => "/application-management/variable", "parent_page" => 1],
                    ["id" => 91, "name" => "variable-edit", "page_url" => "/application-management/variable/edit/:id", "parent_page" => 1],
                    ["id" => 92, "name" => "variable-delete", "page_url" => "/application-management/variable", "parent_page" => 1],

                    ["id" => 93, "name" => "sub-variable-create", "page_url" => "/application-management/sub-variable/create", "parent_page" => 1],
                    ["id" => 94, "name" => "sub-variable-view", "page_url" => "/application-management/sub-variable", "parent_page" => 1],
                    ["id" => 95, "name" => "sub-variable-edit", "page_url" => "/application-management/sub-variable/edit/:id", "parent_page" => 1],
                    ["id" => 96, "name" => "sub-variable-delete", "page_url" => "/application-management/sub-variable", "parent_page" => 1]

                ]
            ],


            /* -------------------------------------------------------------------------- */
            /*                           Beneficiary Management                           */
            /* -------------------------------------------------------------------------- */
            [
                'module_name' => $this->modulePermissionBeneficiaryManagement,
                'sub_module_name' => $this->subBeneficiaryInformationManagement,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 97, "name" => "beneficiaryInfo-create", "page_url" => "/beneficiary-management/beneficiary-info/create", "parent_page" => 1],
                    ["id" => 98, "name" => "beneficiaryInfo-view", "page_url" => "/beneficiary-management/beneficiary-info", "parent_page" => 1],
                    ["id" => 99, "name" => "beneficiaryInfo-edit", "page_url" => "/beneficiary-management/beneficiary-info/edit/:id", "parent_page" => 1],
                    ["id" => 100, "name" => "beneficiaryInfo-delete", "page_url" => "/beneficiary-management/beneficiary-info", "parent_page" => 1]

                ]
            ],
            [
                'module_name' => $this->modulePermissionBeneficiaryManagement,
                'sub_module_name' => $this->subCommitteeInformation,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 101, "name" => "committee-create", "page_url" => "/beneficiary-management/committee/create", "parent_page" => 1],
                    ["id" => 102, "name" => "committee-view", "page_url" => "/beneficiary-management/committee", "parent_page" => 1],
                    ["id" => 103, "name" => "committee-edit", "page_url" => "/beneficiary-management/committee/edit/:id", "parent_page" => 1],
                    ["id" => 104, "name" => "committee-delete", "page_url" => "/beneficiary-management/committee", "parent_page" => 1]
                ]
            ],
            [
                'module_name' => $this->modulePermissionBeneficiaryManagement,
                'sub_module_name' => $this->subAllocationInformation,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 105, "name" => "allocation-create", "page_url" => "/beneficiary-management/allocation/create", "parent_page" => 1],
                    ["id" => 106, "name" => "allocation-view", "page_url" => "/beneficiary-management/allocation", "parent_page" => 1],
                    ["id" => 107, "name" => "allocation-edit", "page_url" => "/beneficiary-management/allocation/edit/:id", "parent_page" => 1],
                    ["id" => 108, "name" => "allocation-delete", "page_url" => "/beneficiary-management/allocation", "parent_page" => 1]
                ]
            ],
            [
                'module_name' => $this->modulePermissionBeneficiaryManagement,
                'sub_module_name' => $this->subBeneficiaryReplacement,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 109, "name" => "beneficiaryReplacement-create", "page_url" => "/beneficiary-management/beneficiary-replacement/create", "parent_page" => 1],
                    ["id" => 110, "name" => "beneficiaryReplacement-view", "page_url" => "/beneficiary-management/beneficiary-replacement", "parent_page" => 1],
                    ["id" => 111, "name" => "beneficiaryReplacement-edit", "page_url" => "/beneficiary-management/beneficiary-replacement/edit/:id", "parent_page" => 1],
                    ["id" => 112, "name" => "beneficiaryReplacement-delete", "page_url" => "/beneficiary-management/beneficiary-replacement", "parent_page" => 1]

                ]
            ],
            [
                'module_name' => $this->modulePermissionBeneficiaryManagement,
                'sub_module_name' => $this->subBeneficiaryIDCard,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 113, "name" => "beneficiaryCard-create", "page_url" => "/beneficiary-management/beneficiary-card/create", "parent_page" => 1],
                    ["id" => 114, "name" => "beneficiaryCard-view", "page_url" => "/beneficiary-management/beneficiary-card", "parent_page" => 1],
                    ["id" => 115, "name" => "beneficiaryCard-edit", "page_url" => "/beneficiary-management/beneficiary-card/edit/:id", "parent_page" => 1],
                    ["id" => 116, "name" => "beneficiaryCard-delete", "page_url" => "/beneficiary-management/beneficiary-card", "parent_page" => 1]

                ]
            ],
            [
                'module_name' => $this->modulePermissionBeneficiaryManagement,
                'sub_module_name' => $this->subBeneficiaryIDShifting,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 117, "name" => "beneficiaryShifting-create", "page_url" => "/beneficiary-management/beneficiary-shifting/create", "parent_page" => 1],
                    ["id" => 118, "name" => "beneficiaryShifting-view", "page_url" => "/beneficiary-management/beneficiary-shifting", "parent_page" => 1],
                    ["id" => 119, "name" => "beneficiaryShifting-edit", "page_url" => "/beneficiary-management/beneficiary-shifting/edit/:id", "parent_page" => 1],
                    ["id" => 120, "name" => "beneficiaryShifting-delete", "page_url" => "/beneficiary-management/beneficiary-shifting", "parent_page" => 1]

                ]
            ],


            /* -------------------------------------------------------------------------- */
            /*                             Payroll Management                             */
            /* -------------------------------------------------------------------------- */
            [
                'module_name' => $this->modulePermissionPayrollManagement,
                'sub_module_name' => $this->subPaymentProcessorInformation,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 121, "name" => "payment-process-create", "page_url" => "/payroll-management/payment-process/create", "parent_page" => 1],
                    ["id" => 122, "name" => "payment-process-view", "page_url" => "/payroll-management/payment-process", "parent_page" => 1],
                    ["id" => 123, "name" => "payment-process-edit", "page_url" => "/payroll-management/payment-process/edit/:id", "parent_page" => 1],
                    ["id" => 124, "name" => "payment-process-delete", "page_url" => "/payroll-management/payment-process", "parent_page" => 1]
                ]
            ],
            [
                'module_name' => $this->modulePermissionPayrollManagement,
                'sub_module_name' => $this->subAccountsInformation,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 125, "name" => "account-information-create", "page_url" => "/payroll-management/account-information/create", "parent_page" => 1],
                    ["id" => 126, "name" => "account-information-view", "page_url" => "/payroll-management/account-information", "parent_page" => 1],
                    ["id" => 127, "name" => "account-information-edit", "page_url" => "/payroll-management/account-information/edit/:id", "parent_page" => 1],
                    ["id" => 128, "name" => "account-information-delete", "page_url" => "/payroll-management/account-information", "parent_page" => 1]
                ]
            ],
            [
                'module_name' => $this->modulePermissionPayrollManagement,
                'sub_module_name' => $this->subPayrollGeneration,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 129, "name" => "payroll-generation-create", "page_url" => "/payroll-management/payroll-generation/create", "parent_page" => 1],
                    ["id" => 130, "name" => "payroll-generation-view", "page_url" => "/payroll-management/payroll-generation", "parent_page" => 1],
                    ["id" => 131, "name" => "payroll-generation-edit", "page_url" => "/payroll-management/payroll-generation/edit/:id", "parent_page" => 1],
                    ["id" => 132, "name" => "payroll-generation-delete", "page_url" => "/payroll-management/payroll-generation", "parent_page" => 1]
                ]
            ],
            [
                'module_name' => $this->modulePermissionEmergencyManagement,
                'sub_module_name' => $this->subEmergencyPayment,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 133, "name" => "emergency-allotment-create", "page_url" => "/payroll-management/emergency-allotment/create", "parent_page" => 1],
                    ["id" => 134, "name" => "emergency-allotment-view", "page_url" => "/payroll-management/emergency-allotment", "parent_page" => 1],
                    ["id" => 135, "name" => "emergency-allotment-edit", "page_url" => "/payroll-management/emergency-allotment/edit/:id", "parent_page" => 1],
                    ["id" => 136, "name" => "emergency-allotment-delete", "page_url" => "/payroll-management/emergency-allotment", "parent_page" => 1],

                    ["id" => 137, "name" => "emergency-beneficiary-create", "page_url" => "/payroll-management/emergency-beneficiary/create", "parent_page" => 1],
                    ["id" => 138, "name" => "emergency-beneficiary-view", "page_url" => "/payroll-management/emergency-beneficiary", "parent_page" => 1],
                    ["id" => 139, "name" => "emergency-beneficiary-edit", "page_url" => "/payroll-management/emergency-beneficiary/edit/:id", "parent_page" => 1],
                    ["id" => 140, "name" => "emergency-beneficiary-delete", "page_url" => "/payroll-management/emergency-beneficiary", "parent_page" => 1],

                    ["id" => 141, "name" => "emergency-payroll-create", "page_url" => "/payroll-management/emergency-payroll/create", "parent_page" => 1],
                    ["id" => 142, "name" => "emergency-payroll-view", "page_url" => "/payroll-management/emergency-payroll", "parent_page" => 1],
                    ["id" => 143, "name" => "emergency-payroll-edit", "page_url" => "/payroll-management/emergency-payroll/edit/:id", "parent_page" => 1],
                    ["id" => 144, "name" => "emergency-payroll-delete", "page_url" => "/payroll-management/emergency-payroll", "parent_page" => 1]

                ]
            ],


            /* -------------------------------------------------------------------------- */
            /*                            Grievance Management                            */
            /* -------------------------------------------------------------------------- */
            [
                'module_name' => $this->modulePermissionGrievanceManagement,
                'sub_module_name' => $this->subGrievanceSetting,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 145, "name" => "grievance-setting-create", "page_url" => "/grievance-management/grievance-setting/create", "parent_page" => 1],
                    ["id" => 146, "name" => "grievance-setting-view", "page_url" => "/grievance-management/grievance-setting", "parent_page" => 1],
                    ["id" => 147, "name" => "grievance-setting-edit", "page_url" => "/grievance-management/grievance-setting/edit/:id", "parent_page" => 1],
                    ["id" => 148, "name" => "grievance-setting-delete", "page_url" => "/grievance-management/grievance-setting", "parent_page" => 1]
                ]
            ],
            [
                'module_name' => $this->modulePermissionGrievanceManagement,
                'sub_module_name' => $this->subGrievanceList,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 149, "name" => "grievance-list-create", "page_url" => "/grievance-management/grievance-list/create", "parent_page" => 1],
                    ["id" => 150, "name" => "grievance-list-view", "page_url" => "/grievance-management/grievance-list", "parent_page" => 1],
                    ["id" => 151, "name" => "grievance-list-edit", "page_url" => "/grievance-management/grievance-list/edit/:id", "parent_page" => 1],
                    ["id" => 152, "name" => "grievance-list-delete", "page_url" => "/grievance-management/grievance-list", "parent_page" => 1]
                ]
            ],

            /* -------------------------------------------------------------------------- */
            /*                              Reporting System                              */
            /* -------------------------------------------------------------------------- */
            [
                'module_name' => $this->modulePermissionReportingSystem,
                'sub_module_name' => $this->modulePermissionReportingSystem,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 153, "name" => "reporting-list-create", "page_url" => "/reporting-management/reporting-list/create", "parent_page" => 1],
                    ["id" => 154, "name" => "reporting-list-view", "page_url" => "/reporting-management/reporting-list", "parent_page" => 1],
                    ["id" => 155, "name" => "reporting-list-edit", "page_url" => "/reporting-management/reporting-list/edit/:id", "parent_page" => 1],
                    ["id" => 156, "name" => "reporting-list-delete", "page_url" => "/reporting-management/reporting-list/delete", "parent_page" => 1],

                    ["id" => 157, "name" => "reporting-survey-create", "page_url" => "/reporting-management/reporting-survey", "parent_page" => 1],
                    ["id" => 158, "name" => "reporting-survey-view", "page_url" => "/reporting-management/reporting-survey", "parent_page" => 1],
                    ["id" => 159, "name" => "reporting-survey-edit", "page_url" => "/reporting-management/reporting-survey/edit/:id", "parent_page" => 1],
                    ["id" => 160, "name" => "reporting-survey-delete", "page_url" => "/reporting-management/reporting-survey/delete", "parent_page" => 1],

                    ["id" => 161, "name" => "reporting-report-create", "page_url" => "/reporting-management/reporting-report", "parent_page" => 1],
                    ["id" => 162, "name" => "reporting-report-view", "page_url" => "/reporting-management/reporting-report", "parent_page" => 1],
                    ["id" => 163, "name" => "reporting-report-edit", "page_url" => "/reporting-management/reporting-report/edit/:id", "parent_page" => 1],
                    ["id" => 164, "name" => "reporting-report-delete", "page_url" => "/reporting-management/reporting-report/delete", "parent_page" => 1],

                    ["id" => 165, "name" => "reporting-bireport-create", "page_url" => "/reporting-management/reporting-bireport", "parent_page" => 1],
                    ["id" => 166, "name" => "reporting-bireport-view", "page_url" => "/reporting-management/reporting-bireport", "parent_page" => 1],
                    ["id" => 167, "name" => "reporting-bireport-edit", "page_url" => "/reporting-management/reporting-bireport/edit/:id", "parent_page" => 1],
                    ["id" => 168, "name" => "reporting-bireport-delete", "page_url" => "/reporting-management/reporting-bireport/delete", "parent_page" => 1],
                ]
            ],

            /* -------------------------------------------------------------------------- */
            /*                             Training Management                            */
            /* -------------------------------------------------------------------------- */
            [
                'module_name' => $this->modulePermissionTrainingManagement,
                'sub_module_name' => $this->modulePermissionTrainingManagement,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 169, "name" => "training-create", "page_url" => "/training-management/training/create", "parent_page" => 1],
                    ["id" => 170, "name" => "training-view", "page_url" => "/training-management/training", "parent_page" => 1],
                    ["id" => 171, "name" => "training-edit", "page_url" => "/training-management/training/edit/:id", "parent_page" => 1],
                    ["id" => 172, "name" => "training-delete", "page_url" => "/training-management/training", "parent_page" => 1]
                ]

            ],
            [
                'module_name' => $this->modulePermissionSettingManagement,
                'sub_module_name' => $this->settingManagement,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 173, "name" => "general-setting-create", "page_url" => "/setting/general/create", "parent_page" => 0],
                    ["id" => 174, "name" => "general-setting-view", "page_url" => "/setting/general", "parent_page" => 0],
                    ["id" => 175, "name" => "general-setting-edit", "page_url" => "/setting/general/edit/:id", "parent_page" => 0],
                    ["id" => 176, "name" => "general-setting-delete", "page_url" => "/setting/general", "parent_page" => 0],

                    // ["id" => 177, "name"=>"global-setting-create", "page_url"=>"/setting/global/create", "parent_page"=>0],
                    // ["id" => 178, "name"=>"global-setting-view", "page_url"=>"/setting/global", "parent_page"=>0],
                    // ["id" => 179, "name"=>"global-setting-edit", "page_url"=>"/setting/global/edit/:id", "parent_page"=>0],
                    // ["id" => 180, "name"=>"global-setting-delete", "page_url"=>"/setting/global", "parent_page"=>0]
                ]

            ],

            [
                'module_name' => $this->modulePermissionBeneficiaryManagement,
                'sub_module_name' => $this->subCommitteePermissionInformation,
                'guard_name' => $this->guard,
                'permissions' => [
                    ["id" => 178, "name" => "committee-permission-create", "page_url" => "/beneficiary-management/committee-permission/create", "parent_page" => 1],
                    ["id" => 179, "name" => "committee-permission-view", "page_url" => "/beneficiary-management/committee-permission", "parent_page" => 1],
                    ["id" => 180, "name" => "allowance-Field-view", "page_url" => "/system-configuration/allowance-program-additional-field", "parent_page" => 1],
                    ["id" => 181, "name" => "committee-permission-delete", "page_url" => "/beneficiary-management/committee-permission", "parent_page" => 1]
                ]
            ],
        ];

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        for ($i = 0; $i < count($permissions); $i++) {
            $groupPermissions = $permissions[$i]['module_name'];
            $subModulePermissions = $permissions[$i]['sub_module_name'];
            $guardPermissions = $permissions[$i]['guard_name'];
            for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
                //create permissions
                $permission = Permission::create([
                    'id' => $permissions[$i]['permissions'][$j]['id'],
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
