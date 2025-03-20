<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Meal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class RevertUnclaimedOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   
    public function handle()
    {
        $expiredOrders = Order::where('status', 'Reserved')
            ->where('pickup_time', '<', Carbon::now()->subHour()) 
            ->get();

        foreach ($expiredOrders as $order) {
            $meal = Meal::find($order->meal_id);
            if ($meal) {
                $meal->available_count += $order->quantity;
                $meal->status = 'available'; 
                $meal->save();
            }

            $order->status = 'available';
            $order->save();
        }
    }
}


