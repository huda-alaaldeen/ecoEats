<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class RoleController extends Controller
{
    public function getPage(Request $request){
        $user=$request->user();
        if($user->role_id==2){
            $client=User::find($user->id);
            return response()->json([
                'message' => 'Client page',
                'client' => $client,
            ]);
        }
        elseif($user->role_id==3){
            $restaurant=Restaurant::find($user->id);
            return response()->json([
                'message' => 'Restaurant page',
                'restaurant' => $restaurant,
            ]);
        }
        else {
            return response()->json([
                'error' => 'Unauthorized role',
            ], 403);
        }

    }

    public function editClient(Request $request)
    {
        $client = User::find($request->user()->id);
    
        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }
    
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'diet_system' => $request->diet_system,
        ];
    
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
    
        $client->update($data);
    
        return response()->json([
            'message' => 'Client data updated successfully',
            'client' => $client,
        ]);
    }
    

        public function editRestaurant(Request $request) {
            $restaurant = Restaurant::find($request->user()->id); 

            $data=[
            'name' => $request->name,
            'phone_number'=>$request->phone_number,
            'email' => $request->email,
            'working_hours' => $request->working_hours,
            'address' => $request->address,
            'restaurant_info'=>$request->restaurant_info,
            ];
            
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
            
            $restaurant->update($data);

            return response()->json([
            'message' => 'Restaurant data updated successfully',
            'restaurant' => $restaurant,
        ]);
    }

         public function deleteClient(Request $request){
            $client=User::find($request->user()->id);
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
