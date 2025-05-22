<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@library.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create student user
        User::create([
            'name' => 'Student User',
            'email' => 'student@library.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);
    }
} 