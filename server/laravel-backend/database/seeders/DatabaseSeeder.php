<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Productos;
use App\Models\Categorias;
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
            'password' => Hash::make('admin'),
            'role' => 2
        ]);
        User::create([
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => Hash::make('secret'),
            'role' => 1
        ]);
        User::create([
            'name' => 'Infinety Admin',
            'email' => 'erickfernando_20@hotmail.com',
            'password' => Hash::make('12345678'),
            'role' => 1
        ]);
        // \App\Models\User::factory(10)->create();
        Categorias::factory(20)->create();
        Productos::factory(100)->create();
    }
}
