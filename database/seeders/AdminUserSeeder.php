<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Note: The first user to register will automatically become admin
        // This seeder is intentionally empty to allow the first user to become admin
        
        \Log::info('AdminUserSeeder: First user registration will automatically become admin');
    }
}
