<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MealController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\searchController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserInfoController;
use App\Http\Controllers\RestaurantController;

Route::get('/user', function (Request $request) {
    return response()->json([
        'id' => $request->user()->id,
        'email' => $request->user()->email,
        'role_id' => $request->user()->role_id,
    ]);
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

Route::middleware(['auth:sanctum'])->get('/client/info/{id}', [UserInfoController::class, 'getClientInfo']);

Route::middleware(['auth:sanctum'])->get('/restaurant/info/{id}', [UserInfoController::class, 'getRestaurantInfo']);

Route::get('/all-clients', [UserInfoController::class, 'retrieveClients']);

Route::get('/admin/unapproved-restaurants', [AdminController::class, 'unapprovedRestaurants']);

Route::middleware('auth:sanctum')->post('/admin/approve-restaurant/{id}', [AdminController::class, 'approveRestaurant']);

Route::middleware('auth:sanctum')->post('create/meals', [MealController::class, 'createMeal']);

Route::get('/meals/{id}', [MealController::class, 'getMealDetails']);

Route::get('/meals/category/{category}', [MealController::class, 'getMealsByCategory']);

Route::get('/meals/price/{price}', [MealController::class, 'getMealsByPrice']);

Route::get('/vegetarian-meals', [MealController::class, 'getVegetarianMeals']);

Route::middleware('auth:sanctum')->get('/restaurant-meals', [MealController::class, 'getMealsForRestaurant']);

Route::middleware('auth:sanctum')->post('/order', [OrderController::class, 'placeOrder']);

Route::middleware('auth:sanctum')->get('/restaurant/reserved-orders', [RestaurantController::class, 'getReservedOrders']);

Route::post('/order/{orderId}/pickup', [RestaurantController::class, 'markOrderAsPickedUp']);

Route::post('/available/meals/{id}', [RestaurantController::class, 'getAvailableMealsInRestaurant']);

Route::middleware('auth:sanctum')->get('/client/orders', [orderController::class, 'getClientOrders']);

Route::middleware('auth:sanctum')->post('cancel/client/orders/{id}', [orderController::class, 'cancelReservationByClient']);

Route::middleware('auth:sanctum')->post('cancel/resturant/orders/{id}', [orderController::class, ' cancelReservationByRestaurant']);

Route::get('/search', [searchController::class, 'search']);
