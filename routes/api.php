<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\User\PermissionController;
use App\Http\Controllers\API\User\RoleController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ClassStaffingController;
use App\Http\Controllers\DateRangeController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SiteController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return new UserResource($request->user());
});
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);

//User Routes
Route::post('/create_user', [UserController::class, 'store']);
Route::put('/update_user/{id}', [UserController::class, 'update']);
Route::delete('/delete_user/{id}', [UserController::class, 'destroy']);
Route::get('/show_user/{id}', [UserController::class, 'show']);
Route::get('/list_user', [UserController::class, 'index']);

//Role Routes
Route::get('/list_role', [RoleController::class, 'index']);
Route::post('/create_role', [RoleController::class, 'store']);
Route::put('/update_role/{id}', [RoleController::class, 'update']);
Route::delete('/delete_role/{id}', [RoleController::class, 'destroy']);
Route::get('/show_role/{id}', [RoleController::class, 'show']);

//Permission Routes
Route::get('/list_permission', [PermissionController::class, 'index']);
Route::post('/create_permission', [PermissionController::class, 'store']);
Route::put('/update_permission/{id}', [PermissionController::class, 'update']);
Route::delete('/delete_permission/{id}', [PermissionController::class, 'destroy']);
Route::get('/show_permission/{id}', [PermissionController::class, 'show']);

//Site Routes
Route::get('sites', [SiteController::class, 'index']);
Route::get('sites2', [SiteController::class, 'index2']);
Route::get('sites3', [SiteController::class, 'index3']);
Route::get('sites4', [SiteController::class, 'index4']);
Route::get('sites5', [SiteController::class, 'index5']);
Route::get('sites6', [SiteController::class, 'index6']);
Route::get('sites7', [SiteController::class, 'index7']);
Route::get('sites8', [SiteController::class, 'index8']);
Route::get('sites/{id}', [SiteController::class, 'show']);
Route::post('sites', [SiteController::class, 'store']);
Route::post('sites2', [SiteController::class, 'store2']);
Route::post('sites3', [SiteController::class, 'store3']);
Route::post('sites4', [SiteController::class, 'store4']);
Route::post('sites5', [SiteController::class, 'store5']);
Route::post('sites6', [SiteController::class, 'store6']);
Route::post('sites7', [SiteController::class, 'store7']);
Route::post('sites8', [SiteController::class, 'store8']);
Route::put('sites/{id}', [SiteController::class, 'update']);
Route::put('sites_activate/{id}', [SiteController::class, 'activate']);
Route::put('sites_deactivate/{id}', [SiteController::class, 'deactivate']);
Route::delete('sites/{id}', [SiteController::class, 'destroy']);

//Program Routes
Route::get('programs', [ProgramController::class, 'index']);
Route::get('programs2', [ProgramController::class, 'index2']);
Route::get('programs3', [ProgramController::class, 'index3']);
Route::get('programs4', [ProgramController::class, 'index4']);
Route::get('programs5', [ProgramController::class, 'index5']);
Route::get('programs6', [ProgramController::class, 'index6']);
Route::get('programs7', [ProgramController::class, 'index7']);
Route::get('programs8', [ProgramController::class, 'index8']);
Route::get('programs/{id}', [ProgramController::class, 'show']);
Route::post('programs', [ProgramController::class, 'store']);
Route::put('programs/{id}', [ProgramController::class, 'update']);
Route::put('programs_activate/{id}', [ProgramController::class, 'activate']);
Route::put('programs_deactivate/{id}', [ProgramController::class, 'deactivate']);
Route::delete('programs/{id}', [ProgramController::class, 'destroy']);
Route::get('programs_selected/{siteId}', [ProgramController::class, 'indexBySite']);

//Classes Routes
Route::get('classes', [ClassesController::class, 'index']);
Route::get('classes/{id}', [ClassesController::class, 'show']);
Route::delete('classes/{id}', [ClassesController::class, 'destroy']);
Route::get('classesall', [ClassesController::class, 'classesAll']);
Route::get('cstat', [ClassesController::class, 'cstat']);
Route::get('classesallindia', [ClassesController::class, 'classesAllInd']);
Route::get('classesalljam', [ClassesController::class, 'classesAllJam']);
Route::get('classesallgua', [ClassesController::class, 'classesAllGua']);
Route::put('classes/pushedback/{id}', [ClassesController::class, 'pushedback']);
Route::put('classes/edit/{id}', [ClassesController::class, 'edit']);
Route::put('classes/cancel/{id}', [ClassesController::class, 'cancel']);
Route::post('classes', [ClassesController::class, 'store']);
Route::get('check', [ClassesController::class, 'checkCombinationExistence']);
Route::get('transaction/{id}', [ClassesController::class, 'transaction']);
Route::post('classesstaffing', [ClassStaffingController::class, 'store']);
Route::get('classesstaffing/{id}', [ClassStaffingController::class, 'show']);
Route::put('updateclassesstaffing/{id}', [ClassStaffingController::class, 'update']);
Route::get('classesstaffing', [ClassStaffingController::class, 'index']);
Route::get('classestransaction/{id}', [ClassStaffingController::class, 'transaction']);
//dateRange
Route::get('daterange', [DateRangeController::class, 'index']);
Route::get('daterange_selected/{monthId}', [DateRangeController::class, 'indexByMonth']);
//chart
Route::get('countstatus', [ClassesController::class, 'countStatus']);