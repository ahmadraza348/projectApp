<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(AdminSeeder::class); 
        User::factory(10)->create(['role' => 'member']);
        User::factory(3)->create(['role' => 'manager']);

        $this->call([
            CategorySeeder::class,
            ProjectSeeder::class, 
            TaskSeeder::class,    
        ]);
    }
}
