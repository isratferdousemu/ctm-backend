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
                'division-create',
                'division-list',
                'division-edit',
                'division-view',
                'division-delete',
                'district-create',
                'district-list',
                'district-edit',
                'district-view',
                'district-delete',
                'city-create',
                'city-list',
                'city-edit',
                'city-view',
                'city-delete',
                'thana-create',
                'thana-list',
                'thana-edit',
                'thana-view',
                'thana-delete',
                'union-create',
                'union-list',
                'union-edit',
                'union-view',
                'union-delete',
                'ward-create',
                'ward-list',
                'ward-edit',
                'ward-view',
                'ward-delete',
                        ]
        ],
        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->subAllowanceProgramManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                'allowance-create',
                'allowance-list',
                'allowance-edit',
                'allowance-view',
                'allowance-delete'
                        ]
        ],
        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->subOfficeInformationManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                'office-create',
                'office-list',
                'office-edit',
                'office-view',
                'office-delete'
                        ]
        ],
        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->subFinancialInformationManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                'financial-create',
                'financial-list',
                'financial-edit',
                'financial-view',
                'financial-delete'
                        ]
        ],
        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->subUserManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                'role-create',
                'role-list',
                'role-edit',
                'role-view',
                'role-delete',
                'role-permission-create',
                'user-create',
                'user-list',
                'user-edit',
                'user-view',
                'user-delete',
                        ]
        ],
        [
            'module_name' => $this->modulePermissionSystemConfiguration,
            'sub_module_name' => $this->subDeviceRegistrationManagement,
            'guard_name' => $this->guard,
            'permissions'=>[
                'device-create',
                'device-list',
                'device-edit',
                'device-view',
                'device-delete'
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
                'training-create',
                'training-list',
                'training-edit',
                'training-view',
                'training-delete'
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
                            'name' => $permissions[$i]['permissions'][$j],
                            'module_name' => $groupPermissions,
                            'sub_module_name' => $subModulePermissions,
                            'guard_name' => $guardPermissions,
                            ]);

                        }

                        }
    }
}
