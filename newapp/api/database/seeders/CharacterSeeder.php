<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $characters = [
            ['id' => 1, 'character_name' => 'Tiny', 'primary_weapon_id' => 1, 'secondary_weapon_id' => 2, 'character_type_id' => 2, 'user_id' => 1, 'guild_rank_id' => null],
            ['id' => 2, 'character_name' => 'Brutus', 'primary_weapon_id' => 3, 'secondary_weapon_id' => 4, 'character_type_id' => 1, 'user_id' => 1, 'guild_rank_id' => null],
            ['id' => 3, 'character_name' => 'Shadowfang', 'primary_weapon_id' => 2, 'secondary_weapon_id' => 5, 'character_type_id' => 3, 'user_id' => 1, 'guild_rank_id' => null],
            ['id' => 4, 'character_name' => 'Luna', 'primary_weapon_id' => 4, 'secondary_weapon_id' => 6, 'character_type_id' => 2, 'user_id' => 1, 'guild_rank_id' => null],
            ['id' => 5, 'character_name' => 'Ragnar', 'primary_weapon_id' => 7, 'secondary_weapon_id' => 1, 'character_type_id' => 1, 'user_id' => 3, 'guild_rank_id' => null],
            ['id' => 6, 'character_name' => 'Eldar', 'primary_weapon_id' => 6, 'secondary_weapon_id' => 3, 'character_type_id' => 3, 'user_id' => 2, 'guild_rank_id' => null],
            ['id' => 7, 'character_name' => 'Seraphina', 'primary_weapon_id' => 5, 'secondary_weapon_id' => 7, 'character_type_id' => 2, 'user_id' => 2, 'guild_rank_id' => null],
            ['id' => 8, 'character_name' => 'Goliath', 'primary_weapon_id' => 3, 'secondary_weapon_id' => 1, 'character_type_id' => 1, 'user_id' => 3, 'guild_rank_id' => null],
            ['id' => 9, 'character_name' => 'Nyx', 'primary_weapon_id' => 2, 'secondary_weapon_id' => 4, 'character_type_id' => 3, 'user_id' => 2, 'guild_rank_id' => null],
            ['id' => 10, 'character_name' => 'Zaros', 'primary_weapon_id' => 7, 'secondary_weapon_id' => 5, 'character_type_id' => 1, 'user_id' => 3, 'guild_rank_id' => null],
        ];

        DB::table('characters')->insert($characters);
    }
}
