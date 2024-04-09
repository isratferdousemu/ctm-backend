<?php



use App\Http\Controllers\Api\V1\Admin\ReportController;
use App\Http\Controllers\Api\V1\Setting\ActivityLogController;


Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('admin')->group(function () {
        Route::any('/activity-log/all/filtered',[ActivityLogController::class, 'getAllActivityLogsPaginated'])->middleware(['role_or_permission:super-admin|activityLog-view']);
        Route::get('/activity-log/view/{id}',[ActivityLogController::class, 'viewAnonymousActivityLog'])->middleware(['role_or_permission:super-admin|activityLog-view']);
        Route::delete('/activity-log/destroy/{id}',[ActivityLogController::class, 'destroyActivityLog'])->middleware(['role_or_permission:super-admin|activityLog-delete']);

    });
});
Route::get('/activity-log/get-information',[ActivityLogController::class, 'getAnonymousActivityLog']);
