<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityParticipantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activity_participants = [
            ['id' => 1, 'activity_id' => 1, 'character_id' => 41, 'activity_participation_status_id' => 1],
            ['id' => 2, 'activity_id' => 1, 'character_id' => 42, 'activity_participation_status_id' => 2],
            ['id' => 3, 'activity_id' => 1, 'character_id' => 43, 'activity_participation_status_id' => 3],
            ['id' => 4, 'activity_id' => 1, 'character_id' => 44, 'activity_participation_status_id' => 1],
            ['id' => 5, 'activity_id' => 1, 'character_id' => 45, 'activity_participation_status_id' => 2],
            ['id' => 6, 'activity_id' => 1, 'character_id' => 46, 'activity_participation_status_id' => 3],
            ['id' => 7, 'activity_id' => 1, 'character_id' => 47, 'activity_participation_status_id' => 1],
            ['id' => 8, 'activity_id' => 1, 'character_id' => 48, 'activity_participation_status_id' => 2],
            ['id' => 9, 'activity_id' => 1, 'character_id' => 49, 'activity_participation_status_id' => 3],
            ['id' => 10, 'activity_id' => 1, 'character_id' => 50, 'activity_participation_status_id' => 1],
            ['id' => 11, 'activity_id' => 1, 'character_id' => 51, 'activity_participation_status_id' => 2],
            ['id' => 12, 'activity_id' => 1, 'character_id' => 52, 'activity_participation_status_id' => 3],
            ['id' => 13, 'activity_id' => 1, 'character_id' => 53, 'activity_participation_status_id' => 1],
            ['id' => 14, 'activity_id' => 1, 'character_id' => 54, 'activity_participation_status_id' => 2],
            ['id' => 15, 'activity_id' => 1, 'character_id' => 55, 'activity_participation_status_id' => 3],
            ['id' => 16, 'activity_id' => 1, 'character_id' => 56, 'activity_participation_status_id' => 1],
            ['id' => 17, 'activity_id' => 1, 'character_id' => 57, 'activity_participation_status_id' => 2],
            ['id' => 18, 'activity_id' => 1, 'character_id' => 58, 'activity_participation_status_id' => 3],
            ['id' => 19, 'activity_id' => 1, 'character_id' => 59, 'activity_participation_status_id' => 1],
            ['id' => 20, 'activity_id' => 1, 'character_id' => 60, 'activity_participation_status_id' => 2],
            ['id' => 21, 'activity_id' => 1, 'character_id' => 61, 'activity_participation_status_id' => 3],
            ['id' => 22, 'activity_id' => 1, 'character_id' => 62, 'activity_participation_status_id' => 1],
            ['id' => 23, 'activity_id' => 1, 'character_id' => 63, 'activity_participation_status_id' => 2],
            ['id' => 24, 'activity_id' => 1, 'character_id' => 64, 'activity_participation_status_id' => 3],
            ['id' => 25, 'activity_id' => 1, 'character_id' => 65, 'activity_participation_status_id' => 1],
            ['id' => 26, 'activity_id' => 1, 'character_id' => 66, 'activity_participation_status_id' => 2],
            ['id' => 27, 'activity_id' => 1, 'character_id' => 67, 'activity_participation_status_id' => 3],
            ['id' => 28, 'activity_id' => 1, 'character_id' => 68, 'activity_participation_status_id' => 1],
            ['id' => 29, 'activity_id' => 1, 'character_id' => 69, 'activity_participation_status_id' => 2],
            ['id' => 30, 'activity_id' => 1, 'character_id' => 70, 'activity_participation_status_id' => 3],
        ];

        DB::table('activity_participants')->insert($activity_participants);
    }
}
