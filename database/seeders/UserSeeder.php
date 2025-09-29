<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@church.com',
                'password' => Hash::make('password'),
                'role' => 'Admin',
            ],
            [
                'name' => 'System Leader',
                'email' => 'leader@church.com',
                'password' => Hash::make('password'),
                'role' => 'Leader',
            ],
            [
                'name' => 'Chapter Coordinator',
                'email' => 'coordinator@church.com',
                'password' => Hash::make('password'),
                'role' => 'Leader',
            ],
            [
                'name' => 'Regional Manager',
                'email' => 'manager@church.com',
                'password' => Hash::make('password'),
                'role' => 'Admin',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
} 