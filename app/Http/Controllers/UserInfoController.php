<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    public function getClientInfo($id){
        $client=User::findOrFail($id);
        return response()->json([
            'client_info'=>$client
        ]);
    }
    public function getRestaurantInfo($id){
        $restaurant = Restaurant::findOrFail($id);
        return response()->json([
            'restaurant_info' => $restaurant
        ]);
    }
    
    public function retrieveClients() {
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
    
}
