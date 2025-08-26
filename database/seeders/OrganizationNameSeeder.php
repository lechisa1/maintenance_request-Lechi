<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrganizationNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $organizations = [
            ['name' => 'Ethiopian Artificial Intelligence'],
            ['name' => 'INSA'],
            ['name' => 'Global Enterprises'],
            ['name' => 'Creative Innovations'],
        ];

        foreach ($organizations as $org) {
            Organization::create($org);
        }
    }
}
