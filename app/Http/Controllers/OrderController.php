<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
    $validatedData = $request->validate([
        'meal_id'  => 'required|exists:meals,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $meal = Meal::find($validatedData['meal_id']);

    if ($meal->available_count < $validatedData['quantity']) {
        return response()->json(['message' => 'Not enough available count'], 422);
    } 

    $meal->available_count -= $validatedData['quantity'];

    if ($meal->available_count == 0) {
        $meal->status = 'reserved';
    }
    $meal->save();

    $order = order::create([
        'meal_id'  => $meal->id,
        'restaurant_id'=>$meal->restaurant_id,
        'user_id'  => Auth::id(),  
        'quantity' => $validatedData['quantity'],
        'total_price' => $meal->price * $validatedData['quantity'],
        'pickup_time' => now()->addHour(),
        'status'   => 'Reserved',
    ]);

    return response()->json([
        'message' => 'Order placed successfully',
        'order'   => $order,
    ], 201);
    }

    public function getClientOrders() {
        $clientId = Auth::user()->id;
  
        $orders=order::where('user_id',$clientId)->get();
        return response()->json([
            'orders'=> $orders,
        ], 200);
    }



}
