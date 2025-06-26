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
        //
        $admin=User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->roles()->attach(Role::where('name','admin')->first()->id);
        
        $director=User::create([
            'name'=>' Director',
            'email'=>'director@gmail.com',
            'password'=>Hash::make('password'),
            'email_verified_at'=>now(),
        ]);
        $director->roles()->attach(Role::where('name','director')->first()->id);

        $technician=User::create([
            "name"=>"Technician",
            "email"=>"technician@gmail.com",
            "password"=>Hash::make('password'),
            "email_verified_at"=>now(),
        ]);
        $technician->roles()->attach(Role::where('name','technician')->first()->id);
    }
}