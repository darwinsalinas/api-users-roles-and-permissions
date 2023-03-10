<?php

use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserPermissionsController;
use App\Http\Controllers\UserRolesController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('me', 'me');
});


Route::apiResource('users', UsersController::class)->middleware('auth:api');
Route::apiResource('users.roles', UserRolesController::class)->middleware('auth:api')->only('store');
Route::delete('users/{user}/roles', [UserRolesController::class, 'destroy'])->middleware('auth:api');
Route::patch('users/{user}/roles', [UserRolesController::class, 'update'])->middleware('auth:api');

Route::apiResource('users.permissions', UserPermissionsController::class)->middleware('auth:api')->only('store');
Route::delete('users/{user}/permissions', [UserPermissionsController::class, 'destroy'])->middleware('auth:api');
Route::patch('users/{user}/permissions', [UserPermissionsController::class, 'update'])->middleware('auth:api');

Route::apiResource('roles', RolesController::class)->middleware('auth:api');
Route::apiResource('permissions', PermissionsController::class)->middleware('auth:api');
