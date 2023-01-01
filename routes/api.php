<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\User\RoleController;
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

//User Routes
Route::post('/create_user',[UserController::class,'store'])->name('user.create');
Route::put('/update_user/{id}',[UserController::class,'update'])->name('user.update');
Route::delete('/delete_user/{id}',[UserController::class,'destroy'])->name('user.delete');
Route::get('/show_user/{id}',[UserController::class,'show'])->name('user.show');
Route::get('/list_user',[UserController::class,'index'])->name('user.list');

//Role Routes
Route::get('/list_role',[RoleController::class,'index'])->name('role.list');
Route::post('/create_role',[RoleController::class,'store'])->name('role.create');