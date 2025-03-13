<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityParticipationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activity_participation_statuses = [
            ['id' => 1, 'status' => 'Participating'],
            ['id' => 2, 'status' => 'Not Participating'],
            ['id' => 3, 'status' => 'Tentative'],
        ];

        DB::table('activity_participation_statuses')->insert($activity_participation_statuses);
    }
}
