<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create(); 

        // Admin
        User::create([
            'name' => 'Admin Callys',
            'email' => 'admin@callysbake.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // User biasa
        User::create([
            'name' => 'Nadia Callysta',
            'email' => 'user@callysbake.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        for ($i = 1; $i <= 8; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'),
                'role' => 'user',
                'status' => 1, // default aktif
            ]);
        }
    }
}
