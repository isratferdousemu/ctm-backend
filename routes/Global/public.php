<?php

use App\Http\Controllers\Api\V1\GlobalController;

Route::prefix('global')->group(function () {
    Route::get('/program',[GlobalController::class, 'getAllProgram']);
    // Route::get('/device/details',[GlobalController::class, 'getDevice ']);

});
