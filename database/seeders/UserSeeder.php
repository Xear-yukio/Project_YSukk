<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Admin Belanja',
            'email' => 'admin@belanja.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Petugas
        User::create([
            'name' => 'Petugas Belanja',
            'email' => 'petugas@belanja.id',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        // Regular User
        User::create([
            'name' => 'User Belanja',
            'email' => 'user@belanja.id',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
