<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Member;
use App\Models\Chapter;
use Illuminate\Support\Facades\Hash;

class ChapterLeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, let's see what chapters exist
        $this->command->info("Checking existing chapters...");
        $existingChapters = Chapter::all();
        foreach ($existingChapters as $chapter) {
            $this->command->info("Found chapter: {$chapter->name} (ID: {$chapter->id})");
        }

        $leaders = [
            'JIL SORSOGON CITY' => [
                'name' => 'Pastor John Santos',
                'email' => 'john.santos.sorsogoncity@jilsorsogon.org',
                'birthday' => '1975-03-15',
                'age' => 49,
                'address' => 'Rizal Street, Sorsogon City',
                'gender' => 'Male',
                'phone' => '09171234567',
            ],
            'JIL BULAN' => [
                'name' => 'Pastor Maria Cruz',
                'email' => 'maria.cruz.bulan@jilsorsogon.org',
                'birthday' => '1980-07-22',
                'age' => 44,
                'address' => 'Poblacion, Bulan, Sorsogon',
                'gender' => 'Female',
                'phone' => '09182345678',
            ],
            'JIL GUBAT' => [
                'name' => 'Pastor David Reyes',
                'email' => 'david.reyes.gubat@jilsorsogon.org',
                'birthday' => '1978-11-08',
                'age' => 45,
                'address' => 'Barangay Centro, Gubat, Sorsogon',
                'gender' => 'Male',
                'phone' => '09193456789',
            ],
            'JIL BULUSAN' => [
                'name' => 'Pastor Grace Mendoza',
                'email' => 'grace.mendoza.bulusan@jilsorsogon.org',
                'birthday' => '1982-05-12',
                'age' => 42,
                'address' => 'San Roque, Bulusan, Sorsogon',
                'gender' => 'Female',
                'phone' => '09204567890',
            ],
            'JIL CASIGURAN' => [
                'name' => 'Pastor Mark Villanueva',
                'email' => 'mark.villanueva.casiguran@jilsorsogon.org',
                'birthday' => '1976-09-30',
                'age' => 47,
                'address' => 'Barangay Poblacion, Casiguran, Sorsogon',
                'gender' => 'Male',
                'phone' => '09215678901',
            ],
            'JIL PILAR' => [
                'name' => 'Pastor Sarah Dela Cruz',
                'email' => 'sarah.delacruz.pilar@jilsorsogon.org',
                'birthday' => '1979-12-18',
                'age' => 44,
                'address' => 'Donsol Road, Pilar, Sorsogon',
                'gender' => 'Female',
                'phone' => '09226789012',
            ],
        ];

        foreach ($leaders as $chapterName => $leaderData) {
            $this->command->info("Looking for chapter: {$chapterName}");
            $chapter = Chapter::where('name', $chapterName)->first();
            
            if ($chapter) {
                $this->command->info("Found chapter: {$chapter->name}");
                
                // Check if leader already exists
                $existingUser = User::where('email', $leaderData['email'])->first();
                if ($existingUser) {
                    $this->command->warn("Leader {$leaderData['name']} already exists, skipping...");
                    continue;
                }

                // Create user account
                $user = User::create([
                    'name' => $leaderData['name'],
                    'email' => $leaderData['email'],
                    'password' => Hash::make('password123'), // Default password
                    'role' => 'Leader',
                    'preferred_chapter_id' => $chapter->id,
                    'birthday' => $leaderData['birthday'],
                    'age' => $leaderData['age'],
                    'address' => $leaderData['address'],
                    'gender' => $leaderData['gender'],
                ]);

                // Create member record
                $member = Member::create([
                    'user_id' => $user->id,
                    'name' => $leaderData['name'],
                    'email' => $leaderData['email'],
                    'phone' => $leaderData['phone'],
                    'address' => $leaderData['address'],
                    'join_date' => now()->toDateString(),
                    'chapter_id' => $chapter->id,
                    'role' => 'Leader',
                    'status' => 'Active',
                    'birthday' => $leaderData['birthday'],
                    'age' => $leaderData['age'],
                    'gender' => $leaderData['gender'],
                    'qr_code' => \Illuminate\Support\Str::uuid(),
                ]);

                // Assign as chapter leader
                $chapter->update([
                    'leader_id' => $member->id,
                    'leader_type' => 'App\\Models\\Member',
                ]);

                $this->command->info("✓ Created leader: {$leaderData['name']} for {$chapter->name}");
            } else {
                $this->command->error("Chapter '{$chapterName}' not found!");
            }
        }
        
        $this->command->info("Seeder completed!");
    }
}
