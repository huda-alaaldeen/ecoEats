<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function registerClient(Request $request){
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'nullable|string|max:20',
            'is_vegetarian' => 'required|boolean',
            'role_id' => 'integer',
        ]);
        
        $isVegetarian = $validateData['is_vegetarian'] ? 1 : 0;

        $user = User::create([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'password' => Hash::make($validateData['password']),
            'phone_number' => $validateData['phone_number'] ?? null,
            'is_vegetarian' => $isVegetarian,
            'role_id' => $validateData['role_id'],
        ]);

       
        Auth::login($user);
    
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
        ], 201);
    }

    public function registerRestaurant(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:restaurants',
            'password' => 'required|string|min:8',
            'phone_number' => 'nullable|string|max:20',
            'working_hours' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'restaurant_info' => 'nullable|string',
            'role_id' => 'integer',
        ]);
    
        $restaurant = Restaurant::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone_number' => $validatedData['phone_number'] ?? null,
            'working_hours' => $validatedData['working_hours'],
            'address' => $validatedData['address'],
            'restaurant_info' => $validatedData['restaurant_info'] ?? null,
            'role_id' => $validatedData['role_id'],
            'is_approved' => false,
        ]);
    
    
        return response()->json([
            'message' => 'Restaurant successfully registered',
            'restaurant' => $restaurant,
        ], 201);
    }
    
    
}
