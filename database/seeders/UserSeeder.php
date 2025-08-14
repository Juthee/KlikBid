<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@klikbid.lk',
            'email_verified_at' => now(),
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        // Create test seller
        \App\Models\User::create([
            'name' => 'Kamal Perera',
            'email' => 'kamal@gmail.com',
            'email_verified_at' => now(),
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        // Create test bidder
        \App\Models\User::create([
            'name' => 'Nimal Silva',
            'email' => 'nimal@gmail.com',
            'email_verified_at' => now(),
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        // Create another test user
        \App\Models\User::create([
            'name' => 'Priya Fernando',
            'email' => 'priya@gmail.com',
            'email_verified_at' => now(),
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);
    }
}
