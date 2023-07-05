<?php

use App\Http\Controllers\Api\V1\GlobalController;

Route::prefix('global')->group(function () {
    Route::post('/bank/all/filtered',[GlobalController::class, 'getAllPublicBankPaginated']);

});
