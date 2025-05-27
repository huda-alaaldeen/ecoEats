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
use App\Models\Meal;

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

Route::get('/client/info/{id}', [UserInfoController::class, 'getClientInfoById']);

Route::middleware('auth:sanctum')->get('/rest/info-ByToken', [UserInfoController::class, 'getRestaurantInfoByToken']);

Route::middleware(['auth:sanctum'])->get('/client/info', [UserInfoController::class, 'getClientInfo']);

Route::get('/get/clients', [UserInfoController::class, 'getClients']);

Route::get('/restaurant/info/{id}', [RegisterController::class, 'getRestaurantInfo']);

Route::get('/all-clients', [UserInfoController::class, 'retrieveClients']);

Route::middleware('auth:sanctum')->post('/admin/approve-restaurant/{restId}', [AdminController::class, 'approveForRestaurant']);

Route::middleware('auth:sanctum')->post('/admin/unapprove-restaurant/{restId}', [AdminController::class, 'unapproveForRestaurant']);

Route::get('/get-unapproved-restaurants', [RegisterController::class, 'getUnapprovedRestaurants']);

Route::get('/get-approved-restaurants', [RegisterController::class, 'getApprovedRestaurants']);

Route::middleware('auth:sanctum')->post('create/meals', [MealController::class, 'createMeal']);

Route::get('/meals/{id}', [MealController::class, 'getMealDetails']);

Route::get('/meals/category/{category}', [MealController::class, 'getMealsByCategory']);

Route::get('/all-meals', [MealController::class, 'getAllMealsByPrice']);

Route::middleware('auth:sanctum')->get('/vegetarian-meals', [MealController::class, 'getVegetarianMeals']);

Route::middleware('auth:sanctum')->get('/non-vegetarian-meals', [MealController::class, 'getNonVegetarianMeals']);

Route::get('/restaurant-meals/{id}', [MealController::class, 'getMealsForRestaurant']);

Route::middleware('auth:sanctum')->post('/place-order', [OrderController::class, 'placeOrder']);

Route::middleware('auth:sanctum')->get('/restaurant/reserved-orders', [RestaurantController::class, 'getReservedOrders']);

Route::post('/order/{orderId}/pickup', [RestaurantController::class, 'markOrderAsPickedUp']);

Route::post('/available/meals/{id}', [RestaurantController::class, 'getAvailableMealsInRestaurant']);

Route::middleware('auth:sanctum')->post('cancel/client/orders/{id}', [orderController::class, 'cancelReservationByClient']);

Route::middleware('auth:sanctum')->post('cancel/resturant/orders/{id}', [orderController::class, 'cancelReservationByRestaurant']);

Route::get('/search/restaurant', [searchController::class, 'searchForRest']);

Route::get('/search/meal', [searchController::class, 'searchForMeal']);

Route::get('/search/user', [searchController::class, 'searchForUser']);

Route::post('/updateMealQuantity/{mealId}/{newQuantity}', [MealController::class, 'updateMealQuantity']);

Route::get('/get/all-restaurants', [RestaurantController::class, 'getAllRestaurants']);

Route::middleware('auth:sanctum')->get('getRestaurantByToken', [RestaurantController::class, 'getRestaurantByToken']);

Route::get('/get/restaurants', [RegisterController::class, 'getRestaurants']);

Route::middleware('auth:sanctum')->get('/restaurant-meals', [RestaurantController::class, 'getMealsForRestaurant']);

Route::get('/get/all-restaurants-names', [RestaurantController::class, 'getAllRestaurantNames']);

Route::middleware('auth:sanctum')->get('/client-orders', [OrderController::class, 'getClientOrders']);

Route::middleware('auth:sanctum')->post('/delete-restaurant/{id}', [AdminController::class, 'deleteRestaurantByAdmin']);

Route::middleware('auth:sanctum')->post('/delete-client/{id}', [AdminController::class, 'deleteClientByAdmin']);

Route::middleware('auth:sanctum')->post('/delete-meal/{id}', [MealController::class, 'deleteMealByRestaurant']);

Route::middleware('auth:sanctum')->get('/client-orders-history', [OrderController::class, 'getClientOrdersHistory']);
