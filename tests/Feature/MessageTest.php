<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Message;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_see_all_users_with_member_records()
    {
        // Create admin user
        $admin = User::factory()->create(['role' => 'admin']);
        Member::factory()->create(['user_id' => $admin->id]);

        // Create regular users with member records
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Member::factory()->create(['user_id' => $user1->id]);
        Member::factory()->create(['user_id' => $user2->id]);

        // Act as admin and visit messages page
        $response = $this->actingAs($admin)->get('/messages');

        // Assert that admin can see all users
        $response->assertStatus(200);
        $response->assertViewHas('chatUsers', function ($chatUsers) use ($user1, $user2) {
            return $chatUsers->contains($user1) && $chatUsers->contains($user2);
        });
    }

    /** @test */
    public function member_can_only_see_users_who_have_messaged_them()
    {
        // Create member user
        $member = User::factory()->create(['role' => 'member']);
        Member::factory()->create(['user_id' => $member->id]);

        // Create other users with member records
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        Member::factory()->create(['user_id' => $user1->id]);
        Member::factory()->create(['user_id' => $user2->id]);
        Member::factory()->create(['user_id' => $user3->id]);

        // Create messages: user1 messaged member, member messaged user2, no messages with user3
        Message::factory()->create([
            'sender_id' => $user1->id,
            'receiver_id' => $member->id,
            'content' => 'Hello from user1'
        ]);

        Message::factory()->create([
            'sender_id' => $member->id,
            'receiver_id' => $user2->id,
            'content' => 'Hello to user2'
        ]);

        // Act as member and visit messages page
        $response = $this->actingAs($member)->get('/messages');

        // Assert that member can only see users they have message history with
        $response->assertStatus(200);
        $response->assertViewHas('chatUsers', function ($chatUsers) use ($user1, $user2, $user3) {
            return $chatUsers->contains($user1) && 
                   $chatUsers->contains($user2) && 
                   !$chatUsers->contains($user3);
        });
    }

    /** @test */
    public function member_with_no_message_history_sees_no_users()
    {
        // Create member user
        $member = User::factory()->create(['role' => 'member']);
        Member::factory()->create(['user_id' => $member->id]);

        // Create other users with member records but no message history
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Member::factory()->create(['user_id' => $user1->id]);
        Member::factory()->create(['user_id' => $user2->id]);

        // Act as member and visit messages page
        $response = $this->actingAs($member)->get('/messages');

        // Assert that member sees no users
        $response->assertStatus(200);
        $response->assertViewHas('chatUsers', function ($chatUsers) {
            return $chatUsers->count() === 0;
        });
    }

    /** @test */
    public function chapter_filter_works_for_admins()
    {
        // Create admin user
        $admin = User::factory()->create(['role' => 'admin']);
        Member::factory()->create(['user_id' => $admin->id]);

        // Create users with different preferred chapters
        $user1 = User::factory()->create(['preferred_chapter_id' => 1]);
        $user2 = User::factory()->create(['preferred_chapter_id' => 2]);
        Member::factory()->create(['user_id' => $user1->id]);
        Member::factory()->create(['user_id' => $user2->id]);

        // Act as admin and visit messages page with chapter filter
        $response = $this->actingAs($admin)->get('/messages?chapter_id=1');

        // Assert that admin only sees users from the specified chapter
        $response->assertStatus(200);
        $response->assertViewHas('chatUsers', function ($chatUsers) use ($user1, $user2) {
            return $chatUsers->contains($user1) && !$chatUsers->contains($user2);
        });
    }
}
