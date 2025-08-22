<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        $director = User::firstOrCreate(
            ['email' => 'director@gmail.com'],
            [
                'name' => 'Director',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $director->assignRole('Ict_director');

        $technician = User::firstOrCreate(
            ['email' => 'technician@gmail.com'],
            [
                'name' => 'Technician',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $technician->assignRole('technician');
    }
}