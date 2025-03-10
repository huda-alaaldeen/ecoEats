<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meal extends Model
{
    use HasFactory;

    protected $fillable=[
        'restaurant_id',
        'name',
        'tags',
        'available_count',
        'price',
        'image',
    ];

    protected $casts = [
        'tags' => 'array',
  
    ];

    public function restaurant(){
        return $this->belongsTo(Restaurant::class);
    }
}
