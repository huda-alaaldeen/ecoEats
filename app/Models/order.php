<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    protected $fillable = ['status', 'user_id', 'meal_id', 'quantity', 'total_price','restaurant_id','pickup_time','order_time','expected_delivery_time'];

    const STATUS_AVAILABLE = 'Available';
    const STATUS_RESERVED = 'Reserved';
    const STATUS_PICKED_UP = 'Picked Up';
    const STATUS_CANCELLED = 'Cancelled';

    protected $casts = [
        'status' => 'string',
    ];

    public static function statusOptions()
    {
        return [
            self::STATUS_AVAILABLE,
            self::STATUS_RESERVED,
            self::STATUS_PICKED_UP,
            self::STATUS_CANCELLED,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
