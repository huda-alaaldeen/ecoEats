<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function approveRestaurant($id)
{
    $user = Auth::user();

        if ($user->role_id != 1) {
        return response()->json(['message' => 'Unauthorized'], 403); // إذا كان ليس أدمن
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

    public function unapprovedRestaurants()
{
    $restaurants = Restaurant::where('is_approved', false)->get();
    return response()->json($restaurants);
}
    
}
