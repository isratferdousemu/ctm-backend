<?php

use App\Http\Controllers\Api\V1\Admin\Emergency\EmergencyAllotmentController;
use App\Http\Controllers\Api\V1\Admin\PaymentProcessorController;
use App\Http\Controllers\Api\V1\Admin\PayrollSettingController;

Route::middleware('auth:sanctum')->group(function () {
    /* -------------------------------------------------------------------------- */
    /*                       Payroll setting Routes                         */
    /* -------------------------------------------------------------------------- */
    Route::prefix('mobile/emergency')->group(function () {
        //for payroll setting
        Route::get('/allotments', [EmergencyAllotmentController::class, 'getEmergencyAllotments'])->middleware(['role_or_permission:super-admin|emergency-allotment-view']);
    });
});
