<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MealController extends Controller
{
    public function createMeal(Request $request)
    {
        $user = Auth::user();
    
        if ($user->role_id !== 3) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        $request->validate([
            'name' => 'required|string|max:255',
            'available_count' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // تعديل هنا
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contains_meat' => 'boolean',
            'contains_chicken' => 'boolean',
        ]);
    
        $imagePath = $request->hasFile('image') ? 
            $request->file('image')->store('meals', 'public') : null;
    
        $meal = Meal::create([
            'restaurant_id' => $user->id,
            'name' => $request->name,
            'available_count' => $request->available_count,
            'price' => $request->price,
            'image' => $imagePath,
            'category' => $request->category,
            'description' => $request->description,
            'contains_meat' => $request->contains_meat ?? false,
            'contains_chicken' => $request->contains_chicken ?? false,
        ]);
    
        return response()->json([
            'message' => 'Meal created successfully',
            'meal' => $meal,
            'imageUrl' => $imagePath ? Storage::url($imagePath) : null,
        ]);
    }
    
    public function getMealDetails($id)
    {
        $mealDetails = Meal::with('restaurant')->find($id);

        if (!$mealDetails) {
            return response()->json([
                'message' => 'Meal not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Meal details retrieved successfully',
            'meal' => $mealDetails
        ], 200);
    }

    public function getMealsByCategory($category)
    {

        $meals = Meal::where('category', $category)->get()
            ->where('status', 'available')
            ->where('available_count', '>', 0)
            ->get();
        if ($meals->isEmpty()) {
            return response()->json([
                'message' => 'No meals found for this category.'
            ], 404);
        }

        return response()->json([
            'meals' => $meals
        ]);
    }

    public function getMealsByPrice($price)
    {
        $meals = Meal::where('status', 'available')
            ->where('available_count', '>', 0)
            ->orderBy('price', $price)
            ->get();

        if ($meals->isEmpty()) {
            return response()->json([
                'message' => 'No meals found.'
            ], 404);
        }

        return response()->json([
            'meals' => $meals
        ]);
    }

    public function getVegetarianMeals()

    {
        $user = Auth::user();

        if ($user && $user->is_vegetarian) {
            $meals = Meal::where('contains_meat', 0)
                ->where('contains_chicken', 0)
                ->where('status', 'available')
                ->where('available_count', '>', 0)
                ->get();

            return response()->json([
                'meals' => $meals
            ]);
        } else {
            return response()->json([
                'message' => 'No vegetarian meals available'
            ], 404);
        }
    }

    public function getMealsForRestaurant()
    {
        $restaurant = Auth::user();

        if (!$restaurant || $restaurant->role_id != 3) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $meals = Meal::where('restaurant_id', $restaurant->id)->get();

        if ($meals->isEmpty()) {
            return response()->json([
                'message' => 'No meals available'
            ], 404);
        }

        return response()->json([
            'meals' => $meals
        ], 200);
    }
}
