<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\QuickReplyTemplate;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ServiceCategorySeeder::class);

        QuickReplyTemplate::insert([
            [
                'title' => 'Hello',
                'body' => 'Hi! How can I help you with your service request?',
                'is_system' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Available for a quote',
                'body' => "I'm available to provide a quote for this job. Let me know a good time to discuss the details.",
                'is_system' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Running late',
                'body' => "I'm running a few minutes late. I'll be there as soon as possible.",
                'is_system' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Job completed',
                'body' => 'The job has been completed. Please let me know if everything looks good.',
                'is_system' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
