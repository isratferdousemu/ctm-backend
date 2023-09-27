<?php

use App\Http\Controllers\Api\V1\GlobalController;

Route::prefix('global')->group(function () {
    Route::post('/location/insert',[GlobalController::class, 'insertLocation']);
    // Route::get('/device/details',[GlobalController::class, 'getDevice ']);

});
