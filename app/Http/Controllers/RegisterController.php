<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function registerClient(Request $request)
    {
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


    
    protected function generateImageUrl($path)
    {
        if (empty($path)) {
            return null;
        }
    
        $cleanPath = ltrim(str_replace('storage/', '', $path), '/');
    
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
    
        if (!$disk->exists($cleanPath)) {
            return null;
        }
    
        try {
            $fileContent = $disk->get($cleanPath);
            $mimeType = $disk->mimeType($cleanPath) ?? 'application/octet-stream';
    
            $base64 = base64_encode($fileContent);
            return "data:{$mimeType};base64,{$base64}";
        } catch (\Throwable $e) {
            return null;
        }
    }
    
    
    
        

    public function registerRestaurant(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:restaurants',
            'password' => 'required|string|min:8',
            'phone_number' => 'nullable|string|max:20',
            'working_hours_from' => 'required|string',
            'working_hours_to' => 'required|string',
            'address' => 'required|string|max:255',
            'restaurant_info' => 'nullable|string',
            'role_id' => 'nullable|integer',
            'license' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);
    
        $licensePath = null;
        if ($request->hasFile('license')) {
            $file = $request->file('license');
            $ext = $file->getClientOriginalExtension();
            $filename = uniqid() . '.' . $ext;
            Storage::disk('public')->put('licenses/' . $filename, file_get_contents($file));
            $licensePath = 'licenses/' . $filename;
        }
    
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = uniqid() . '.' . $ext;
            Storage::disk('public')->put('restaurant_images/' . $filename, file_get_contents($file));
            $imagePath = 'restaurant_images/' . $filename;
        }
    
        $restaurant = Restaurant::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone_number' => $validatedData['phone_number'] ?? null,
            'working_hours_from' => $validatedData['working_hours_from'],
            'working_hours_to' => $validatedData['working_hours_to'],
            'address' => $validatedData['address'],
            'restaurant_info' => $validatedData['restaurant_info'] ?? null,
            'role_id' => $validatedData['role_id'] ?? 3,
            'license' => $licensePath,
            'image' => $imagePath,
        ]);
    
        $restaurant->license = $this->generateImageUrl($licensePath);
        $restaurant->image = $this->generateImageUrl($imagePath);
    
        return response()->json([
            'message' => 'Restaurant registered successfully',
            'restaurant' => $restaurant,
        ], 201);
    }
    

    public function getRestaurants()
    {
        $restaurants = Restaurant::all();
        $restaurants->transform(function ($restaurant) {
            $restaurant->image = $this->generateImageUrl($restaurant->image);
            $restaurant->license = $this->generateImageUrl($restaurant->license);
            return $restaurant;
        });

        return response()->json([
            'message' => 'All restaurants retrieved successfully',
            'restaurants' => $restaurants
        ]);
    }

    public function getRestaurantInfo($id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $restaurant->image = $this->generateImageUrl($restaurant->image);
        $restaurant->license = $this->generateImageUrl($restaurant->license);

        return response()->json([
            'restaurant_info' => $restaurant
        ]);
    }

  
    public function getUnapprovedRestaurants()
    {
        $licenses = Restaurant::where('is_approved', false)
            ->get()
            ->map(function ($restaurant) {
                return [
                    'id'=>$restaurant->id,
                    'name' => $restaurant->name,
                    'license' => $this->generateImageUrl($restaurant->license),
                ];
            });
    
        return response()->json([
            'unapproved_restaurant_licenses' => $licenses
        ]);
    }
    
    public function getApprovedRestaurants()
    {
        $licenses = Restaurant::where('is_approved', true)
            ->get()
            ->map(function ($restaurant) {
                return [
                    'id'=>$restaurant->id,
                    'name' => $restaurant->name,
                    'license' => $this->generateImageUrl($restaurant->license),
                ];
            });
    
        return response()->json([
            'approved_restaurant_licenses' => $licenses
        ]);
    }
    
    
}
