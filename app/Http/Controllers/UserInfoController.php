<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserInfoController extends Controller
{
    public function getClientInfoById($id)
    {
        $client = User::findOrFail($id);
        return response()->json([
            'client_info' => $client
        ]);
    }

    public function getClientInfo()
    {
        return response()->json([
            'client_info' => Auth::user()
        ]);
    }



    public function getClients()
    {
        $clients=User::all();
        return response()->json([
            'clients' => $clients
        ]);
    }

    public function retrieveClients()
    {
        try {
            $clients = User::all();
            return response()->json([
                'clients' => $clients
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function getRestaurantInfoByToken()
    {
        $restaurant = Auth::user();
        return response()->json([
            'restaurant_info' => $restaurant
        ]);
    }
}
