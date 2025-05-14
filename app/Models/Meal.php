<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
   
class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'name',
        'available_count',
        'price',
        'image',
        'category',
        'description',
        'tags',
        'status',
        'contains_meat',
        'contains_chicken',
        'original_price'
    ];



    public function restaurant(){
        return $this->belongsTo(Restaurant::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
