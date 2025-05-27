<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Cache;
class MealController extends Controller
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
            'original_price' => 'required|numeric|min:0',
            'image' => 'nullable|image',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contains_meat' => 'boolean',
            'contains_chicken' => 'boolean',
        ]);
    
        $imagePath = null;
    
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = uniqid() . '.' . $ext;
            Storage::disk('public')->put('meals/' . $filename, file_get_contents($file));
            $imagePath = 'meals/' . $filename;
        }
    
        $meal = Meal::create([
            'restaurant_id' => $user->id,
            'name' => $request->name,
            'available_count' => $request->available_count,
            'price' => $request->price,
            'original_price' => $request->original_price,
            'image' => $imagePath,
            'category' => $request->category,
            'description' => $request->description,
            'contains_meat' => $request->contains_meat ?? false,
            'contains_chicken' => $request->contains_chicken ?? false,
        ]);
    
        $meal->image = $this->generateImageUrl($imagePath);
    
        return response()->json([
            'message' => 'Meal created successfully',
            'meal' => $meal,
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

        $mealDetails->image = $this->generateImageUrl($mealDetails->image);
        return response()->json([
            'message' => 'Meal details retrieved successfully',
            'meal' => $mealDetails
        ], 200);
    }

    public function getMealsByCategory($category)
    {
        $meals = Meal::where('category', $category)
            ->where('status', 'available')
            ->where('available_count', '>', 0)
            ->get(['id', 'name', 'price', 'image']);

        if ($meals->isEmpty()) {
            return response()->json([
                'message' => 'No meals found for this category.'
            ], 404);
        }

        $meals->transform(function ($meal) {
            $meal->image = $this->generateImageUrl($meal->image);
            return $meal;
        });

        return response()->json([
            'meals' => $meals
        ]);
    }

    public function getAllMealsByPrice()
    {
        $meals = Meal::where('status', 'available')
            ->where('available_count', '>', 0)
            ->orderBy('price')
            ->get();

        if ($meals->isEmpty()) {
            return response()->json([
                'message' => 'No meals found.'
            ], 404);
        }

        $meals->transform(function ($meal) {
            $meal->image = $this->generateImageUrl($meal->image);
            return $meal;
        });


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
                ->get(['id', 'name', 'price', 'image','original_price']);
            $meals->transform(function ($meal) {
                $meal->image = $this->generateImageUrl($meal->image);
                return $meal;
            });


            return response()->json([
                'meals' => $meals
            ]);
        } else {
            return response()->json([
                'message' => 'No vegetarian meals available'
            ], 404);
        }
    }

    public function getMealsForRestaurant($id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant || $restaurant->role_id != 3) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $meals = Meal::where('restaurant_id', $restaurant->id)
            ->where('status', 'available')
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

    public function updateMealQuantity($mealId, $newQuantity)
    {
        $meal = Meal::findOrFail($mealId);

        if ($meal->available_count == 0) {
            $meal->available_count += $newQuantity;
            $meal->created_at = now();
            $meal->save();

            $meal->image = $this->generateImageUrl($meal->image);

            return response()->json([
                'message' => 'Meal quantity updated successfully.',
                'meal' => $meal
            ]);
        }

        return response()->json([
            'message' => 'The meal quantity can only be updated if the quantity is zero.',
        ], 400);
    }

    public function getNonVegetarianMeals()
    {
        $user = Auth::user();

        if ($user && !$user->is_vegetarian) {
            $meals = Meal::where(function ($query) {
                $query->where('contains_meat', 1)
                    ->orWhere('contains_chicken', 1);
            })
                ->where('status', 'available')
                ->where('available_count', '>', 0)
                ->get(['id', 'name', 'price', 'image']);

            $meals->transform(function ($meal) {
                $meal->image = $this->generateImageUrl($meal->image);
                return $meal;
            });

            return response()->json([
                'meals' => $meals
            ]);
        } else {
            return response()->json([
                'message' => 'Access denied: You must be a non-vegetarian to view these meals.'
            ], 403);
        }
    }

    public function deleteMealByRestaurant($mealId){
        $restaurant=Auth::user();
        if($restaurant->role_id !=3){
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $meal = Meal::where('id', $mealId)
        ->first();
        if($restaurant->id != $meal->restaurant_id){
            return response()->json(['message' => 'This restaurant can not access to this meal'], 403);
        }
        $meal->delete();
        return response()->json(['message' => 'Meal deleted by restaurant']);

    }
}
