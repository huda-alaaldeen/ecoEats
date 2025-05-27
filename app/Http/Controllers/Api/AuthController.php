<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('api-token')->plainTextToken;
    
            return response()->json([
                'message' => 'Login successful (user)',
                'token' => $token,
                'role_id' => $user->role_id
            ]);
        }
    
        $restaurant = Restaurant::where('email', $request->email)->first();
        if ($restaurant && Hash::check($request->password, $restaurant->password)) {
            if (!$restaurant->is_approved) {
                return response()->json(['message' => 'Your account is pending approval'], 403);
            }
    
            $token = $restaurant->createToken('api-token')->plainTextToken;
    
            return response()->json([
                'message' => 'Login successful (restaurant)',
                'token' => $token,
                'role_id' => 3,
            ]);
        }
    
        $admin = Admin::where('email', $request->email)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            $token = $admin->createToken('admin-token')->plainTextToken;
    
            return response()->json([
                'message' => 'Login successful (admin)',
                'token' => $token,
                'role_id' => $admin->role_id,
            ]);
        }
    
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
