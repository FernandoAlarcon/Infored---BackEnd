<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin')
        ]);
        User::create([
            'name' => 'User', 
            'email' => 'user@test.com',
            'password' => Hash::make('secret')
        ]);
        User::create([
            'name' => 'Infored Admin',
            'email' => 'erickfernando_20@hotmail.com',
            'password' => Hash::make('12345678')
        ]);
    }
}
