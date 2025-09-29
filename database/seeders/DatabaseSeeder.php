<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create sample events
        Event::factory(5)->create();

        // Create chapters for Sorsogon locations
        $this->call([
            UserSeeder::class,
            MemberSeeder::class,
            ChapterSeeder::class,
            ChapterLeaderSeeder::class,
            ResourceSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}
