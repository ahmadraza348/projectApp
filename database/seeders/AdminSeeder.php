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
            'email' => 'admin@demo.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',            
        ]);

         User::create([
            'name' => 'Manager User',
            'email' => 'manager@demo.com',
            'password' => bcrypt('manager123'),
            'role' => 'manager',            
        ]);
     
         
        User::create([
            'name' => 'member User',
            'email' => 'member@demo.com',
            'password' => bcrypt('member123'),
            'role' => 'member',            
        ]);

    }
}
