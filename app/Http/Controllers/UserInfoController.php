<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    public function getClientInfo(Request $request){
        $client=User::findOrFail($request->user()->id);
        return response()->json([
            'client_info'=>$client
        ]);
    }
    public function getRestaurantInfo(Request $request){
        $restaurant=Restaurant::findOrFail($request->user()->id);
        return response()->json([
            'Restaurant_info'=>$restaurant
        ]);
    }
}
