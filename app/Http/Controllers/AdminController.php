<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\User;
use App\Models\order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function approveForRestaurant($id)
    {
        $user = Auth::user();

        if ($user->role_id != 1) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if (!is_numeric($id)) {
            return response()->json(['message' => 'Invalid ID'], 400);
        }

        $restaurant = Restaurant::find($id);
        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        $restaurant->update(['is_approved' => true]);

        return response()->json(['message' => 'Restaurant approved successfully', 'restaurant' => $restaurant]);
    }


    public function unapproveForRestaurant($id)
    {
        $user = Auth::user();

        if ($user->role_id != 1) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if (!is_numeric($id)) {
            return response()->json(['message' => 'Invalid ID'], 400);
        }

        $restaurant = Restaurant::find($id);
        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        $restaurant->update(['is_approved' => false]);

        return response()->json(['message' => 'Restaurant unapproved successfully']);
    }
    
    public function deleteRestaurantByAdmin($restaurantId)
    {
        $admin = Auth::user();

        if ($admin->role_id != 1) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $restaurant = Restaurant::where('id', $restaurantId)->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        $restaurant->delete();

        return response()->json(['message' => 'Restaurant deleted by admin']);
    }

    public function deleteClientByAdmin($clientId)
    {
        $admin = Auth::user();

        if ($admin->role_id != 1) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::where('id', $clientId)->first();

        if (!$user) {
            return response()->json(['message' => 'client not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Client deleted by admin']);
    }
}
