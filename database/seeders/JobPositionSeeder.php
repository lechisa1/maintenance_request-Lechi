<?php

namespace Database\Seeders;

use App\Models\JobPosition;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JobPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
            $positions = [
        'Professional',
        'General Director',
        'Division Director',
        'Team Leader',
        'Supervisor',
        'Dept Director',
    ];

    foreach ($positions as $position) {
        JobPosition::create(['title' => $position]);
    }
    }
}
