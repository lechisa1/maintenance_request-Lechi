<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
class OrganizationUnitLabelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
                DB::table('organization_unit_labels')->insert([
            ['unit_type' => 'organization', 'label' => 'Organization'],
            ['unit_type' => 'sector', 'label' => 'Sector'],
            ['unit_type' => 'division', 'label' => 'Division'],
            ['unit_type' => 'department', 'label' => 'Department'],
        ]);
    }
}
