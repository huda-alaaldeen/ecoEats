<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserInfoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/clients/register', [RegisterController::class, 'registerClient']);

Route::post('/restaurants/register', [RegisterController::class, 'registerRestaurant']);

Route::middleware(['auth:sanctum'])->get('/userPage', [RoleController::class, 'getpage']);

Route::middleware(['auth:sanctum'])->post('/client/edit', [RoleController::class, 'editClient']);

Route::middleware(['auth:sanctum'])->post('/restaurant/edit', [RoleController::class, 'editRestaurant']);

Route::middleware(['auth:sanctum'])->post('/client/delete', [RoleController::class, 'deleteClient']);

Route::middleware(['auth:sanctum'])->post('/restaurant/delete', [RoleController::class, 'deleteRestaurant']);

Route::middleware(['auth:sanctum'])->get('/client/info', [UserInfoController::class, 'getClientInfo']);

Route::middleware(['auth:sanctum'])->get('/restaurant/info', [UserInfoController::class, 'getRestaurantInfo']);







