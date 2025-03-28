<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class RestaurantController extends Controller
{
    public function getAvailableMealsInRestaurant($id)
    {
        $meals = Meal::where('restaurant_id', $id)
            ->where('available_count', '!=', 0)
            ->get();

        return response()->json([
            'meals' => $meals
        ]);
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
            ->get();

        return response()->json([
            'orders' => $orders
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
}
