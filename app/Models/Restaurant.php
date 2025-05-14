<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use HasApiTokens;
    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'password',
        'role_id',
        'working_hours',
        'address',
        'restaurant_info',
        'is_approved',
        'working_hours_from',
        'working_hours_to',
        'license',
        'image'

    ];

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
}
