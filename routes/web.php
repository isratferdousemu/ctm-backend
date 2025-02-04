<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes(['register' => false,'reset' => false,'verify' => false,]);
Route::get('/', function () {
    return view('welcome');
});
Route::get('/report', function () {
    return view('reports.division_report');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

