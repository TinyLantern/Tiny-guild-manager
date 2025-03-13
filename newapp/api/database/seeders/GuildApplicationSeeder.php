<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuildApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guild_applications = [
            ['id' => 1, 'character_id' => 21, 'guild_id' => 1, 'guild_application_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'character_id' => 22, 'guild_id' => 1, 'guild_application_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'character_id' => 23, 'guild_id' => 1, 'guild_application_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'character_id' => 24, 'guild_id' => 1, 'guild_application_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'character_id' => 25, 'guild_id' => 1, 'guild_application_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'character_id' => 26, 'guild_id' => 1, 'guild_application_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'character_id' => 27, 'guild_id' => 1, 'guild_application_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'character_id' => 28, 'guild_id' => 1, 'guild_application_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'character_id' => 29, 'guild_id' => 1, 'guild_application_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'character_id' => 30, 'guild_id' => 1, 'guild_application_status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('guild_applications')->insert($guild_applications);
    }
}
