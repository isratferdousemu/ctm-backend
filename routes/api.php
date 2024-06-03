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
    include "Admin/SystemConfig/LocationRoute.php";
    include "Admin/SystemConfig/UserRoutes.php";
    include "Admin/SystemConfig/SystemConfigRoute.php";
    include "Admin/allotment_budget/AllotmentBudgetRoute.php";
    include "Admin/Beneficiary/BeneficiaryRoute.php";
    include "Admin/Application/Poverty/PMTScoreRoute.php";
    include "Admin/Application/application-selection/applicationSelectionRoute.php";
    include "Admin/GlobalSetting/GlobalSettingRoute.php";
    include "Admin/ReportRoute.php";
    include "Admin/ActivityLogRoute.php";
    include "Admin/greivanceManagementRoute/greivanceManagementRoute.php";
    include "Admin/APIManager/APIManagerRoute.php";
    include "Admin/Training/trainingRoute.php";
    include "Admin/PayrollManagement/PayrollRoute.php";
    include "Admin/Emergency/EmergencyManagementRoute.php";

});


Route::prefix('v1')->group(function () {
    include "Client/clientRoute.php";
});


Route::prefix('v1')->group(function () {
    include "Mobile/Auth/AuthRoute.php";
    include "Mobile/Application/application-selection/applicationSelectionRoute.php";
    include "Mobile/Beneficiary/BeneficiaryRoute.php";
    include "Mobile/PayrollManagement/PayrollRoute.php";
});




