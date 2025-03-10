<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

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
        'restaurant_info'
    ];

    public function meals()
{
    return $this->hasMany(Meal::class);
}

}
