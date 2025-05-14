<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class RoleController extends Controller
{
    public function getPage(Request $request)
    {
        $user = $request->user();
        if ($user->role_id == 2) {
            $client = User::find($user->id);
            return response()->json([
                'message' => 'Client page',
                'client' => $client,
            ]);
        } elseif ($user->role_id == 3) {
            $restaurant = Restaurant::find($user->id);
            return response()->json([
                'message' => 'Restaurant page',
                'restaurant' => $restaurant,
            ]);
        } else {
            return response()->json([
                'error' => 'Unauthorized role',
            ], 403);
        }
    }

    public function editClient(Request $request)
    {
        /** @var \App\Models\User $client */

        $client = Auth::user();

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $client->id,
            'phone_number' => 'nullable|string|max:20',
            'diet_system' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
        ]);

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $client->update($validatedData);

        return response()->json([
            'message' => 'Client data updated successfully',
            'client' => $client,
        ]);
    }




    public function editRestaurant(Request $request)
    {
        /** @var \App\Models\Restaurant $restaurant */

        $restaurant = Auth::user();

        if (!$restaurant || $restaurant->role_id != 3) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }


        $data = [
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'working_hours_from' => $request->working_hours_from,
            'working_hours_to' => $request->working_hours_to,
            'address' => $request->address,
            'restaurant_info' => $request->restaurant_info,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $restaurant->update($data);

        return response()->json([
            'message' => 'Restaurant data updated successfully',
            'restaurant' => $restaurant
        ]);
    }

    public function deleteClient(Request $request)
    {
        $client = User::find($request->user()->id);
        $client->delete();
        return response()->json([
            'message' => 'Client deleted successfully',
        ]);
    }

    public function deleteRestaurant(Request $request)
    {
        $restaurant = Restaurant::find($request->user()->id);
        $restaurant->delete();

        return response()->json([
            'message' => 'Restaurant deleted successfully',
        ]);
    }
}
