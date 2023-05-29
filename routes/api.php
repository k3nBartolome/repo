<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\User\PermissionController;
use App\Http\Controllers\API\User\RoleController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\ClassesController;
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
Route::get('sites/{id}', [SiteController::class, 'show']);
Route::post('sites', [SiteController::class, 'store']);
Route::put('sites/{id}', [SiteController::class, 'update']);
Route::put('sites_activate/{id}', [SiteController::class, 'activate']);
Route::put('sites_deactivate/{id}', [SiteController::class, 'deactivate']);
Route::delete('sites/{id}', [SiteController::class, 'destroy']);

//Program Routes
Route::get('programs', [ProgramController::class, 'index']);
Route::get('programs2', [ProgramController::class, 'index2']);
Route::get('programs3', [ProgramController::class, 'index3']);
Route::get('programs4', [ProgramController::class, 'index4']);
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
Route::get('classesallindia', [ClassesController::class, 'classesAllInd']);
Route::get('classesalljam', [ClassesController::class, 'classesAllJam']);
Route::put('classes/pushedback/{id}', [ClassesController::class, 'pushedback']);
Route::put('classes/edit/{id}', [ClassesController::class, 'edit']);
Route::put('classes/cancel/{id}', [ClassesController::class, 'cancel']);
Route::post('classes', [ClassesController::class, 'store']);
Route::get('check', [ClassesController::class, 'checkCombinationExistence']);
Route::get('transaction/{id}', [ClassesController::class, 'transaction']);

//dateRange
Route::get('daterange', [DateRangeController::class, 'index']);
Route::get('daterange_selected/{monthId}', [DateRangeController::class, 'indexByMonth']);
