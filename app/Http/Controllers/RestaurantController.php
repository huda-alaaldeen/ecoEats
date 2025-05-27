<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\User;
use App\Models\order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{

    
    protected function generateImageUrl($path)
    {
    if (empty($path)) {
    return null;
    }
    
    $cleanPath = ltrim(str_replace('storage/', '', $path), '/');
    
    if (Storage::disk('public')->exists($cleanPath)) {
    return app()->environment('local')
    ? 'https://4399-91-186-255-241.ngrok-free.app/storage/' . $cleanPath
    : url('storage/' . $cleanPath);
    }
    
    return null;
    }
    
    
    
    public function getAvailableMealsInRestaurant($id)
    {
        $meals = Meal::where('restaurant_id', $id)
            ->where('available_count', '!=', 0)
            ->get();
    
        $meals->transform(function ($meal) {
            $meal->image = $this->generateImageUrl($meal->image);
            return $meal;
        });
    
        return response()->json([
            'meals' => $meals
        ]);
    }
    

    public function getMealsForRestaurant()
    {
        $restaurant = Auth::user();

        if (!$restaurant || $restaurant->role_id != 3) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $meals = Meal::where('restaurant_id', $restaurant->id)
            ->get();

        if ($meals->isEmpty()) {
            return response()->json([
                'message' => 'No meals available'
            ], 404);
        }
        $meals->transform(function ($meal) {
            $meal->image = $this->generateImageUrl($meal->image);
            return $meal;
        });

        return response()->json([
            'meals' => $meals
        ], 200);
    }


    public function getReservedOrders()
    {
        $user = Auth::user();

        $restaurant = Restaurant::where('email', $user->email)->first();

        if (!$restaurant) {
            return response()->json([
                'message' => 'Unauthorized - Only restaurants can access this'
            ], 403);
        }

        $orders = Order::where('restaurant_id', $restaurant->id)
            ->where('status', 'Reserved')
            ->with(['user:id,name,phone_number'])
            ->get();


        return response()->json([
            'orders' => $orders,
        ]);
    }




    public function markOrderAsPickedUp($orderId)
    {
        $order = Order::find($orderId);
        if ($order && $order->status === 'Reserved') {
            $order->status = 'Picked Up';
            $order->save();

            return response()->json([
                'message' => 'Order status updated successfully.',
                'order' => $order
            ]);
        }

        return response()->json(['message' => 'Order not found or already picked up.'], 404);
    }

    public function getAllRestaurants()
    {
        $restaurants = Restaurant::all();

        return response()->json([
            'message' => 'All restaurants retrieved successfully',
            'restaurants' => $restaurants,
        ]);
    }
    public function getRestaurantByToken()
    {
        $user = Auth::user();

        $restaurant = Restaurant::where('email', $user->email)->first();

        if (!$restaurant) {
            return response()->json([
                'message' => 'Unauthorized - Only restaurants can access this'
            ], 403);
        }

        return response()->json([
            'message' => 'Info for restaurants retrieved successfully',
            'restaurants' => $restaurant,
        ]);
    }

    public function getAllRestaurantNames()
    {
        $restaurants = Restaurant::select('id', 'name')->get();

        return response()->json([
            'message' => 'All restaurants retrieved successfully',
            'restaurants' => $restaurants
        ]);
    }

   
}
