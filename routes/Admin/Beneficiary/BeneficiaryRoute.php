<?php


use App\Http\Controllers\Api\V1\Admin\CommitteeController;


Route::middleware('auth:sanctum')->group(function () {


 /* -------------------------------------------------------------------------- */
 /*                                Beneficiary Routes                          */
 /* -------------------------------------------------------------------------- */
Route::prefix('admin/committee')->group(function () {

Route::post('/insert', [CommitteeController::class, 'insertCommittee'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
Route::get('/get',[CommitteeController::class, 'getAllCommitteePaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
Route::post('/update', [CommitteeController::class, 'committeeUpdate'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);
Route::get('/destroy/{id}', [CommitteeController::class, 'destroyCommittee'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
Route::get('/{id}', [CommitteeController::class, 'editCommittee'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);


});



});


