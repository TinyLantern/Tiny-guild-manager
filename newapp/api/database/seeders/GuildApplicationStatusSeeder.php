<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuildApplicationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guild_application_statuses = [
            ['id' => 1, 'status' => 'Pending'],
            ['id' => 2, 'status' => 'Accepted'],
            ['id' => 3, 'status' => 'Declined'],
        ];

        DB::table('guild_application_statuses')->insert($guild_application_statuses);
    }
}
