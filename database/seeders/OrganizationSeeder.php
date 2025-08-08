<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    $sector = \App\Models\Sector::firstOrCreate(['name' => 'Research And Development']);
    $division = $sector->divisions()->firstOrCreate(['name' => 'System And Infrastructure Development Division']);
    $division->departments()->createMany([
        ['name' => 'Web Development','description' => 'Handles all web projects'],
        ['name' => 'Mobile Development','description' => 'Handles all mobile projects'],
        ['name' => 'Machine Learning','description' => 'Handles all machine learning projects'],
    ]);
    }
}
