<?php



use App\Http\Controllers\Api\V1\Admin\ReportController;


Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('admin')->group(function () {
        Route::any('/generate-pdf', [ReportController::class, 'commonReport']);
    });
});
