<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@demo.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Manager User',
            'username' => 'manager',
            'email' => 'manager@demo.com',
            'password' => bcrypt('manager123'),
            'role' => 'manager',
        ]);

        User::create([
            'name' => 'Member User',
            'username' => 'member',
            'email' => 'member@demo.com',
            'password' => bcrypt('member123'),
            'role' => 'member',
        ]);
    }
}
