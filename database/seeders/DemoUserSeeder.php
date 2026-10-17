<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@pos.test'],
            ['name' => 'Admin Kafe', 'role' => 'admin', 'password' => 'password']
        );
        
        User::firstOrCreate(
            ['email' => 'kasir@pos.test'],
            ['name' => 'Kasir Kafe', 'role' => 'kasir', 'password' => 'password']
        );
    }
}
