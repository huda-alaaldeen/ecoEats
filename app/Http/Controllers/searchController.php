<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;

class searchController extends Controller
{
    public function searchForRest(Request $request)
    {
        $query = $request->input('query');

        $restaurants = Restaurant::where(function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
                ->orWhere('address', 'LIKE', "%{$query}%");
        })->get('name');

        return response()->json([
            'restaurants' => $restaurants,
        ]);
    }

    public function searchForMeal(Request $request)
    {
        $query = $request->input('query');

        $meals = Meal::where('name', 'LIKE', "%{$query}%")->get('name');

        return response()->json([
            'meals' => $meals,
        ]);
    }

    public function searchForUser(Request $request)
    {
        $query = $request->input('query');

        $users = User::where('name', 'LIKE', "%{$query}%")->get('name');
        
        return response()->json([
            'user' => $users,
        ]);
    }
}
