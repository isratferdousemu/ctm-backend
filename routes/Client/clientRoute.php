<?php


use App\Http\Controllers\Client\ApplicationController;
use App\Http\Controllers\Client\BeneficiaryController;

Route::prefix('client')->group(function () {
    Route::get('application/get', [ApplicationController::class, 'getAllApplicationPaginated']);
    Route::get('application/get/{id}', [ApplicationController::class, 'getApplicationById']);

    Route::get('beneficiary/list', [BeneficiaryController::class, 'getBeneficiariesList']);
    Route::get('getByBeneficiaryId/{beneficiary_id}', [BeneficiaryController::class, 'getBeneficiaryById']);

    Route::post('update-beneficiary/nominee-info/{beneficiary_id}', [BeneficiaryController::class, 'updateNomineeInfo']);
    Route::post('update-beneficiary/account-info/{beneficiary_id}', [BeneficiaryController::class, 'updateAccountInfo']);
});

