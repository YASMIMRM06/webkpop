<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin FanBase',
            'email' => 'admin@kpop.com',
            'password' => Hash::make('password'), // Altere para uma senha segura!
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Usuário Fã',
            'email' => 'user@kpop.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }
}