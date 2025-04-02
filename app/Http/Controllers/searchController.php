<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class searchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $restaurants = Restaurant::where('name', 'LIKE', "%{$query}%")
            ->orWhere('address', 'LIKE', "%{$query}%")
            ->get();

        $meals = Meal::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();

        return response()->json([
            'restaurants' => $restaurants,
            'meals' => $meals,
        ]);
    }
}
