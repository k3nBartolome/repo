<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassesController;
use  App\Http\Controllers\CapEmailController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the 'web' middleware group. Now create something great!
|
*/
Route::get( '/webclassesdashboard', [ ClassesController::class, 'WebDashboardClasses' ] );
Route::get( '/websiteclasses', [ ClassesController::class, 'WebDashboardSiteClasses' ] );
Route::get( '/websiteb2classes', [ ClassesController::class, 'retrieveB2DataForEmail' ] );
Route::post( 'render', [ CapEmailController::class, 'sendEmail' ] );
