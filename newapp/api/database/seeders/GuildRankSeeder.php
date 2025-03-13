<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuildRankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guild_ranks = [
            ['id' => 1, 'rank' => 'Guild Master'],
            ['id' => 2, 'rank' => 'Advisor'],
            ['id' => 3, 'rank' => 'Guardian'],
            ['id' => 4, 'rank' => 'Member'],
        ];

        DB::table('guild_ranks')->insert($guild_ranks);
    }
}
