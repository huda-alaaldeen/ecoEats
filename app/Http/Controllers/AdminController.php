<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function approveRestaurant($id)
    {
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
