<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\User\RoleController;
use App\Http\Controllers\API\User\PermissionController;
use App\Http\Resources\UserResource;

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
Route::post('login',[AuthController::class,'login'])->name('login');
Route::post('logout',[AuthController::class,'logout'])->name('logout');

//User Routes
Route::post('/create_user',[UserController::class,'store'])->name('users.create');
Route::put('/update_user/{id}',[UserController::class,'update'])->name('users.update');
Route::delete('/delete_user/{id}',[UserController::class,'destroy'])->name('users.delete');
Route::get('/show_user/{id}',[UserController::class,'show'])->name('users.show');
Route::get('/list_user',[UserController::class,'index'])->name('users.list');

//Role Routes
Route::get('/list_role',[RoleController::class,'index'])->name('roles.list');
Route::post('/create_role',[RoleController::class,'store'])->name('roles.create');
Route::put('/update_role/{id}',[RoleController::class,'update'])->name('roles.update');
Route::delete('/delete_role/{id}',[RoleController::class,'destroy'])->name('roles.delete');
Route::get('/show_role/{id}',[RoleController::class,'show'])->name('roles.show');

//Permission Routes
Route::get('/list_permission',[PermissionController::class,'index'])->name('permissions.list');
Route::post('/create_permission',[PermissionController::class,'store'])->name('permissions.create');
Route::put('/update_permission/{id}',[PermissionController::class,'update'])->name('permissions.update');
Route::delete('/delete_permission/{id}',[PermissionController::class,'destroy'])->name('permissions.delete');
Route::get('/show_permission/{id}',[PermissionController::class,'show'])->name('permissions.show');