<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@parking.local'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'balance' => 0,
            ]
        );

        // Create display user (for gate screen)
        User::updateOrCreate(
            ['email' => 'display@parking.local'],
            [
                'name' => 'Gate Display',
                'password' => bcrypt('display123'),
                'role' => 'display',
                'balance' => 0,
            ]
        );
    }
}
