<?php

namespace App\Http\Traits;

trait PermissionTrait
{
    //permission groups
    private $permissionGroupAdminDashboard = 'AdminDashboard';
    private $permissionGroupAdminExpense = 'AdminExpense';
    private $permissionGroupAdminSupport = 'AdminSupport';
    private $permissionGroupAdminSetting = 'AdminSetting';

    // modules list
    private $modulePermissionSystemConfiguration = "SystemConfiguration";
    private $modulePermissionApplicationSelection = "ApplicationSelection";
    private $modulePermissionBeneficiaryManagement = "BeneficiaryManagement";
    private $modulePermissionPayrollManagement = "PayrollManagement";
    private $modulePermissionEmergencyManagement = "EmergencyManagement";
    private $modulePermissionGrievanceManagement = "GrievanceManagement";
    private $modulePermissionReportingSystem = "ReportingSystem";
    private $modulePermissionTrainingManagement = "TrainingManagement";
    private $modulePermissionBudgetManagement = "BudgetManagement";
    private $modulePermissionAllotmentManagement = "AllotmentManagement";
    private $modulePermissionSettingManagement = "SettingManagement";

    // sub modules list

    // module 1 sub modules
    private $subDemographicInformationManagement = "demographic-information-Management";
    private $subAllowanceProgramManagement = "Allowance-Program-Management";
    private $subOfficeInformationManagement = "office-information-management";
    private $subFinancialInformationManagement = "financial-information-management";
    private $subUserManagement = "user-management";
    private $subRoleManagement = "role-management";
    private $subRolePermissionManagement = "role-permission-management";
    private $subDeviceRegistrationManagement = "device-registration-management";

    private $menuManagement = "menu-management";
    private $deviceRegistrationManagement = "device-registration";

    // module 2 sub modules
    private $subPovertyScoreManagement = "poverty-score-management";
    private $subOnlineApplicationManagement = "online-application-management";
    private $subBeneficiarySelectionManagement   = "beneficiary-selection-management";


    // module 3 sub modules
    private $subCommitteeInformation= "committee-information";
    private $subAllocationInformation = "allocation-information";
    private $subBeneficiaryInformationManagement= "beneficiary-information-management";
    private $subBeneficiaryReplacement= "beneficiary-replacement";
    private $subBeneficiaryIDCard= "beneficiary-id-card";
    private $subBeneficiaryIDShifting= "beneficiary-id-shifting";


    // module 4 sub modules
    private $subPaymentProcessorInformation= "payment-processor-information";
    private $subAccountsInformation= "accounts-information";
    private $subPayrollGeneration= "payroll-generation";
    private $subEmergencyPayment= "emergency-payment";


    // module 5 sub modules
    private $subGrievanceSetting= "grievance-setting";
    private $subGrievanceList= "grievance-list";


    // module 6 sub modules

    private $budgetManagement= "budget-management";
    private $allotmentManagement= "allotment-management";
    private $settingManagement= "setting-management";

}
