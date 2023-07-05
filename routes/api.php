<?php

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/************* APP Routes */

Route::prefix('v1')->group(function () {

    include "Admin/Auth/AuthRoute.php"; 
    include "Admin/AdminRoute.php";
    include "Global/PushNotification.php";
    include "Global/public.php";
});



