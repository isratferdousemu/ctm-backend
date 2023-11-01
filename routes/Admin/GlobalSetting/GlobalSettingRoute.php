<?php



use App\Http\Controllers\Api\V1\Admin\GlobalSettingController;


Route::middleware('auth:sanctum')->group(function () {


 /* -------------------------------------------------------------------------- */
 /*                                Beneficiary Routes                          */
 /* -------------------------------------------------------------------------- */
Route::prefix('admin/globalsetting')->group(function () {

Route::post('/insert', [GlobalSettingController::class, 'insertGlobalSetting'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
Route::get('/get',[GlobalSettingController::class, 'getAllGlobalSettingPaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
Route::post('/update', [GlobalSettingController::class, 'globalSettingUpdate'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);
Route::get('/destroy/{id}', [GlobalSettingController::class, 'destroyGlobalSetting'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
Route::get('/{id}', [GlobalSettingController::class, 'editGlobalSetting'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);


});
});
