<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            ['id' => 1, 'name' => 'Janko', 'description' => 'descr', 'start_time' => '2025-02-06 17:33:00', 'end_time' => '2025-02-06 20:37:00', 'dkp' => 700, 'guild_id' => 1]
        ];
        DB::table('activities')->insert($activities);
    }
}
