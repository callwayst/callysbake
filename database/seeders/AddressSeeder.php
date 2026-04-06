<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Address;
use App\Models\User;

class AddressSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            Address::create([
                'user_id' => $user->id,
                'receiver_name' => $user->name,
                'phone' => '0812345678'.$user->id,
                'address' => 'Jl. Contoh No.'.$user->id, // pakai "address" sesuai migration
                'city' => 'Jakarta',
                'postal_code' => '12345',
                'is_default' => true
            ]);
        }
    }
}