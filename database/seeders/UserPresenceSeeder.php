<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserPresence;

class UserPresenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            UserPresence::create([
                'user_id' => $user->id,
                'status' => 'offline',
                'last_seen_at' => now()->subHours(rand(1, 24)),
                'last_activity_at' => now()->subMinutes(rand(5, 60)),
                'is_typing' => false,
            ]);
        }
    }
}
