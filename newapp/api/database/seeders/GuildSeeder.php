<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuildSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guilds = [
            ['id' => 1, 'name' => 'JankovaGuilda', 'server_id' => 14, 'character_id' => 1],
            ['id' => 2, 'name' => 'DragonSlayers', 'server_id' => 14, 'character_id' => 2],
            ['id' => 3, 'name' => 'ShadowLegion', 'server_id' => 14, 'character_id' => 3],
            ['id' => 4, 'name' => 'IronFists', 'server_id' => 14, 'character_id' => 4],
            ['id' => 5, 'name' => 'MysticWolves', 'server_id' => 14, 'character_id' => 5],
            ['id' => 6, 'name' => 'FrostGuardians', 'server_id' => 1, 'character_id' => 6],
            ['id' => 7, 'name' => 'BloodSeekers', 'server_id' => 1, 'character_id' => 7],
            ['id' => 8, 'name' => 'CelestialKnight', 'server_id' => 1, 'character_id' => 8],
            ['id' => 9, 'name' => 'PhantomRaiders', 'server_id' => 1, 'character_id' => 9],
            ['id' => 10, 'name' => 'StormBringers', 'server_id' => 1, 'character_id' => 10],
        ];

        DB::table('guilds')->insert($guilds);
    }
}
