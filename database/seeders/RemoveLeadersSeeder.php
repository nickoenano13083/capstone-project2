<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Member;
use App\Models\Chapter;

class RemoveLeadersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Removing existing leader accounts...");

        // List of leader emails to remove
        $leaderEmails = [
            'john.santos.sorsogoncity@jilsorsogon.org',
            'maria.cruz.bulan@jilsorsogon.org',
            'david.reyes.gubat@jilsorsogon.org',
            'grace.mendoza.bulusan@jilsorsogon.org',
            'mark.villanueva.casiguran@jilsorsogon.org',
            'sarah.delacruz.pilar@jilsorsogon.org',
        ];

        foreach ($leaderEmails as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $this->command->info("Removing user: {$user->name} ({$email})");
                
                // Remove member record if exists
                if ($user->member) {
                    // Clear chapter leadership if this member was a leader
                    Chapter::where('leader_id', $user->member->id)
                          ->where('leader_type', 'App\\Models\\Member')
                          ->update([
                              'leader_id' => null,
                              'leader_type' => null
                          ]);
                    
                    $user->member->delete();
                    $this->command->info("  - Removed member record");
                }
                
                $user->delete();
                $this->command->info("  - Removed user account");
            } else {
                $this->command->warn("User with email {$email} not found, skipping...");
            }
        }

        // Also remove any users with Leader role that might have been created incorrectly
        $additionalLeaders = User::where('role', 'Leader')
                                ->whereNotIn('email', ['test@example.com']) // Keep test user
                                ->get();

        foreach ($additionalLeaders as $leader) {
            $this->command->info("Removing additional leader: {$leader->name} ({$leader->email})");
            
            if ($leader->member) {
                Chapter::where('leader_id', $leader->member->id)
                      ->where('leader_type', 'App\\Models\\Member')
                      ->update([
                          'leader_id' => null,
                          'leader_type' => null
                      ]);
                $leader->member->delete();
            }
            
            $leader->delete();
        }

        $this->command->info("Cleanup completed!");
    }
}
