<?php

use App\Http\Controllers\CapEmailController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ClassStaffingController;
use Illuminate\Support\Facades\Route;

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

Route::get('/webclassesdashboard', [ClassesController::class, 'WebDashboardClasses']);
Route::get('/websiteclasses', [ClassesController::class, 'WebDashboardSiteClasses']);
Route::get('/websiteexternal', [ClassesController::class, 'WebDashboardExternalClasses']);
Route::get('/websiteinternal', [ClassesController::class, 'WebDashboardInternalClasses']);
Route::get('/websiteb2classes', [ClassesController::class, 'retrieveB2DataForEmail']);
Route::get('/automated_sr', [ClassesController::class, 'AutomatedSr']);
Route::get('/automated_sr_export', [ClassesController::class, 'AutomatedSrExportData']);
Route::post('render', [CapEmailController::class, 'sendEmail']);
Route::post('sr_render', [CapEmailController::class, 'sendSR']);
Route::get('out', [CapEmailController::class, 'OutOfSla']);
Route::get('cancel', [CapEmailController::class, 'Cancelled']);
Route::get('/ytd', [CapEmailController::class, 'ytd']);
Route::get('/ytdstaffing', [ClassStaffingController::class, 'ytdForEmail']);
Route::get('/mtd', [CapEmailController::class, 'ytd']);
Route::get('/mtdstaffing', [ClassStaffingController::class, 'mtdForEmail']);
Route::get('/weeklypipe', [CapEmailController::class, 'mtd']);
Route::get('/weeklypipestaffing', [ClassStaffingController::class, 'weeklyPipeForEmail']);
