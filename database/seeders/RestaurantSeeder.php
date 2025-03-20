<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::table('restaurants')->insert([
                'name' => $faker->company,
                'phone_number' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password123'),
                'role_id' => 3, 
                'working_hours' => $faker->time('H:i') . ' - ' . $faker->time('H:i'),
                'address' => $faker->address,
                'restaurant_info' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

    }
  }
}
