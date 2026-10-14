<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['Plumber', 'plumber', 'Pipes, taps and drains', 'water'], ['Electrician', 'electrician', 'Wiring, lights and repairs', 'bolt'],
            ['Carpenter', 'carpenter', 'Furniture and woodwork', 'hammer'], ['Mechanic', 'mechanic', 'Car care and repairs', 'wrench'],
            ['Painter', 'painter', 'Fresh walls and finishes', 'brush'], ['Phone technician', 'phone-technician', 'Phone repairs and screens', 'phone'],
            ['AC technician', 'ac-technician', 'Cooling and air conditioning', 'snowflake'], ['Cleaning service', 'cleaning-service', 'Home and office cleaning', 'sparkles'],
            ['Barber', 'barber', 'Cuts and grooming', 'scissors'], ['Tailor', 'tailor', 'Alterations and custom wear', 'needle'],
            ['Photographer', 'photographer', 'Events and portraits', 'camera'],
        ];
        foreach ($categories as $index => [$name, $slug, $description, $icon]) {
            ServiceCategory::updateOrCreate(['slug' => $slug], ['name' => $name, 'description' => $description, 'icon' => $icon, 'sort_order' => $index + 1, 'is_active' => true]);
        }
    }
}
