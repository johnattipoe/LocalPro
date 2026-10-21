<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = 'App\\Models\\Booking';

    public function definition(): array
    {
        return [
            'status' => 'pending',
            'scheduled_at' => $this->faker->dateTimeBetween('+1 day', '+2 weeks'),
        ];
    }
}
