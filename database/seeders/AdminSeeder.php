<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{

    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'type' => 'admin',
            'password' => bcrypt('password'),
        ]);
        User::create([
            'name' => 'Manager',
            'email' => 'manager@manager.com',
            'type' => 'manager',
            'password' => bcrypt('password'),
        ]);
    }
}
