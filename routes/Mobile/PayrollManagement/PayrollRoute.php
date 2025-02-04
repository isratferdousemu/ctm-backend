<?php

use App\Http\Controllers\Api\V1\Admin\PaymentProcessorController;
use App\Http\Controllers\Api\V1\Admin\PayrollSettingController;

Route::middleware('auth:sanctum')->group(function () {
    /* -------------------------------------------------------------------------- */
    /*                       Payroll setting Routes                         */
    /* -------------------------------------------------------------------------- */
    Route::prefix('mobile/payroll')->group(function () {
        //for payroll setting
        Route::get('/get-all-allowance', [PayrollSettingController::class, 'getAllAllowance'])->middleware(['role_or_permission:super-admin|payroll-setting-view']);
        Route::get('/get-financial-year', [PayrollSettingController::class, 'getFinancialYear'])->middleware(['role_or_permission:super-admin|payroll-setting-view']);
        Route::get('/get-all-installments', [PayrollSettingController::class, 'getAllInstallments'])->middleware(['role_or_permission:super-admin|payroll-setting-view']);
        Route::post('/setting-submit', [PayrollSettingController::class, 'payrollSettingSubmit']);
        Route::get('/get-setting-data', [PayrollSettingController::class, 'getSettingData']);
        //for payroll verification
        Route::post('/verification-setting-submit', [PayrollSettingController::class, 'payrollVerification']);
        Route::get('/get-verification-setting', [PayrollSettingController::class, 'getVerificationSetting']);
    });

});
// beneficiary tracking information
Route::post('mobile/payroll/payment-tracking-info', [PaymentProcessorController::class, 'getPaymentTrackingInfoMobile']);
