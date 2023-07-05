<?php

use App\Http\Controllers\Api\V1\Bank\BankController;
use App\Http\Controllers\Api\V1\ProductCategory\ProductCategoriesController;
use App\Http\Controllers\Api\V1\Setting\ActivityLogController;

Route::middleware('auth:sanctum')->group(function () {

    Route::group(['middleware' => ['role_or_permission:super-admin|main-product-category-view']], function () {
        Route::post('admin/product/category/all/filtered', [ProductCategoriesController::class, 'getAllProductCategoryPaginated']);
    });
    Route::group(['middleware' => ['role_or_permission:super-admin|main-product-category-store']], function () {
        Route::post('admin/product/category/insert', [ProductCategoriesController::class, 'insertProductCategory']);
    });
    Route::post('admin/product/category/update',[ProductCategoriesController::class, 'UpdateProductCategory'])->middleware(['role_or_permission:super-admin|main-product-category-update']);

    Route::post('admin/product/category/delete',[ProductCategoriesController::class, 'deleteProductCategory'])->middleware(['role_or_permission:super-admin|main-product-category-delete']);

    Route::post('admin/product/category/delete/permanent',[ProductCategoriesController::class, 'ForceDeleteProductCategory'])->middleware(['role_or_permission:super-admin|main-product-category-delete']);

    Route::post('admin/activity-log/all/filtered',[ActivityLogController::class, 'getAllActivityLogsPaginated'])->middleware(['role_or_permission:super-admin|main-setting-activity-log']);

    Route::prefix('admin/bank')->group(function () {

        Route::post('all/filtered',[BankController::class, 'getAllBankPaginated'])->middleware(['role_or_permission:super-admin|main-bank-view']);

        Route::post('/insert', [BankController::class, 'insertBank'])->middleware(['role_or_permission:super-admin|main-bank-store']);

    });

});
