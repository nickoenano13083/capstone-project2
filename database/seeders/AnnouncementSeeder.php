<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user
        $admin = User::where('role', 'Admin')->first();
        if (!$admin) {
            return;
        }

        Announcement::create([
            'title' => 'Welcome to the New Dashboard!',
            'body' => 'We are excited to launch new features for our admin dashboard. Stay tuned for more updates!',
            'user_id' => $admin->id,
        ]);

        Announcement::create([
            'title' => 'Upcoming Maintenance',
            'body' => 'The system will be undergoing scheduled maintenance on Sunday at 10:00 PM. Please save your work.',
            'user_id' => $admin->id,
        ]);
    }
} 