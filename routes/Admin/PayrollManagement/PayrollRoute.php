<?php

use App\Http\Controllers\Api\V1\Admin\Emergency\EmergencySupplementaryController;
use App\Http\Controllers\Api\V1\Admin\PaymentProcessorController;
use App\Http\Controllers\Api\V1\Admin\PayrollDashboardController;
use App\Http\Controllers\Api\V1\Admin\Payroll\PayrollController;
use App\Http\Controllers\Api\V1\Admin\PayrollSettingController;

Route::middleware('auth:sanctum')->group(function () {
    /* -------------------------------------------------------------------------- */
    /*                       Payroll setting Routes                         */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/payroll')->group(function () {
        //for payroll setting
        Route::get('/get-all-allowance', [PayrollSettingController::class, 'getAllAllowance'])->middleware(['role_or_permission:super-admin|payroll-setting-view']);
        Route::get('/get-financial-year', [PayrollSettingController::class, 'getFinancialYear'])->middleware(['role_or_permission:super-admin|payroll-setting-view']);
        Route::get('/get-all-installments', [PayrollSettingController::class, 'getAllInstallments'])->middleware(['role_or_permission:super-admin|payroll-setting-view']);
        Route::post('/setting-submit', [PayrollSettingController::class, 'payrollSettingSubmit']);
        Route::get('/get-setting-data', [PayrollSettingController::class, 'getSettingData']);
        //for payroll verification
        Route::post('/verification-setting-submit', [PayrollSettingController::class, 'payrollVerification']);
        Route::get('/get-verification-setting', [PayrollSettingController::class, 'getVerificationSetting']);
        //for payment processor
        Route::get('/get-banks', [PaymentProcessorController::class, 'getBanks']);
        Route::apiResource('/payment-processor', PaymentProcessorController::class);
        // beneficiary tracking information
        Route::post('/payment-tracking-info', [PaymentProcessorController::class, 'getPaymentTrackingInfo']);
        // dashboard
        Route::get('/payroll-status-data', [PayrollDashboardController::class, 'payrollData']);
        Route::get('/payment-cycle-status-data', [PayrollDashboardController::class, 'paymentCycleStatusData']);
        Route::get('/monthly-approved-payroll', [PayrollDashboardController::class, 'monthlyApprovedPayroll']);
        Route::get('/program-wise-payroll', [PayrollDashboardController::class, 'programWisePayroll']);
        Route::get('/total-payment-processor', [PayrollDashboardController::class, 'totalPaymentProcessor']);
        Route::get('/program-wise-payment-cycle', [PayrollDashboardController::class, 'programWisePaymentCycle']);
        Route::get('/total-amount-disbursed', [PayrollDashboardController::class, 'totalAmountDisbursed']);
        Route::get('/program-balance', [PayrollDashboardController::class, 'programBalance']);
        //emergency payment dashboard
        Route::get('/payment-cycle-disbursement-status', [PayrollDashboardController::class, 'paymentCycleDisbursementStatus']);
        Route::get('/emergency-dashboard-data', [PayrollDashboardController::class, 'emergencyDashboardData']);
        //emergency supplementary payroll
        Route::get('/emergency-supplementary-payroll', [EmergencySupplementaryController::class, 'emergencySupplementaryPayrollData']);
        Route::get('/emergency-supplementary-payroll-show/{id}', [EmergencySupplementaryController::class, 'emergencySupplementaryPayrollShow']);
        // for payroll create
        Route::get('/get-program-info/{program_id}', [PayrollController::class, 'getProgramInfo'])->middleware(['role_or_permission:super-admin|payroll-create|payroll-view']);
        Route::get('/get-active-installments/{program_id}/{financial_year_id}', [PayrollController::class, 'getActiveInstallments'])->middleware(['role_or_permission:super-admin|payroll-create|payroll-view']);
        Route::get('/get-allotment-area-list', [PayrollController::class, 'getAllotmentAreaList'])->middleware(['role_or_permission:super-admin|payroll-create|payroll-view']);
        Route::get('/get-active-beneficiaries/{allotment_id}', [PayrollController::class, 'getActiveBeneficiaries'])->middleware(['role_or_permission:super-admin|payroll-create|payroll-view']);
        Route::post('/set-beneficiaries', [PayrollController::class, 'setBeneficiaries'])->middleware(['role_or_permission:super-admin|payroll-create']);
        Route::get('/preview-beneficiaries', [PayrollController::class, 'previewBeneficiaries'])->middleware(['role_or_permission:super-admin|payroll-create']);
        Route::post('/submit-payroll', [PayrollController::class, 'submitPayroll'])->middleware(['role_or_permission:super-admin|payroll-create']);
        // for payroll approve
        Route::prefix('approve')->group(function () {
            Route::get('/get-pending-payroll-list', [PayrollController::class, 'getAllotmentAreaList'])->middleware(['role_or_permission:super-admin|payroll-create|payroll-view']);
            Route::get('/view-beneficiaries/{payroll_id}', [PayrollController::class, 'getActiveBeneficiaries'])->middleware(['role_or_permission:super-admin|payroll-create|payroll-view']);
            Route::put('/reject-beneficiary/{beneficiary_id}', [PayrollController::class, 'submitPayroll'])->middleware(['role_or_permission:super-admin|payroll-create']);
            Route::put('/reject-payroll/{payroll_id}', [PayrollController::class, 'submitPayroll'])->middleware(['role_or_permission:super-admin|payroll-create']);
            Route::put('/approve-payroll/{payroll_id}', [PayrollController::class, 'submitPayroll'])->middleware(['role_or_permission:super-admin|payroll-create']);
        });
    });


});
