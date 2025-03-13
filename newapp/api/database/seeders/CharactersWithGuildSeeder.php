<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CharactersWithGuildSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $characters = [
            ['id' => 11, 'character_name' => 'Aelric', 'primary_weapon_id' => 1, 'secondary_weapon_id' => 2, 'character_type_id' => 2, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 12, 'character_name' => 'Bryndel', 'primary_weapon_id' => 3, 'secondary_weapon_id' => 4, 'character_type_id' => 1, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 13, 'character_name' => 'Caelum', 'primary_weapon_id' => 5, 'secondary_weapon_id' => 6, 'character_type_id' => 3, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 14, 'character_name' => 'Darian', 'primary_weapon_id' => 2, 'secondary_weapon_id' => 7, 'character_type_id' => 1, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 15, 'character_name' => 'Elira', 'primary_weapon_id' => 6, 'secondary_weapon_id' => 1, 'character_type_id' => 2, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 16, 'character_name' => 'Fendrel', 'primary_weapon_id' => 7, 'secondary_weapon_id' => 3, 'character_type_id' => 3, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 17, 'character_name' => 'Gwyndolyn', 'primary_weapon_id' => 4, 'secondary_weapon_id' => 5, 'character_type_id' => 1, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 18, 'character_name' => 'Havard', 'primary_weapon_id' => 1, 'secondary_weapon_id' => 6, 'character_type_id' => 2, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 19, 'character_name' => 'Isolde', 'primary_weapon_id' => 3, 'secondary_weapon_id' => 7, 'character_type_id' => 3, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 20, 'character_name' => 'Jorvik', 'primary_weapon_id' => 2, 'secondary_weapon_id' => 4, 'character_type_id' => 1, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],

            ['id' => 21, 'character_name' => 'Kaelen', 'primary_weapon_id' => 7, 'secondary_weapon_id' => 5, 'character_type_id' => 2, 'user_id' => 3, 'guild_rank_id' => null, 'guild_id' => null],
            ['id' => 22, 'character_name' => 'Lirien', 'primary_weapon_id' => 6, 'secondary_weapon_id' => 1, 'character_type_id' => 3, 'user_id' => 2, 'guild_rank_id' => null, 'guild_id' => null],
            ['id' => 23, 'character_name' => 'Malrik', 'primary_weapon_id' => 4, 'secondary_weapon_id' => 3, 'character_type_id' => 1, 'user_id' => 3, 'guild_rank_id' => null, 'guild_id' => null],
            ['id' => 24, 'character_name' => 'Nerissa', 'primary_weapon_id' => 2, 'secondary_weapon_id' => 7, 'character_type_id' => 2, 'user_id' => 2, 'guild_rank_id' => null, 'guild_id' => null],
            ['id' => 25, 'character_name' => 'Orin', 'primary_weapon_id' => 5, 'secondary_weapon_id' => 6, 'character_type_id' => 3, 'user_id' => 3, 'guild_rank_id' => null, 'guild_id' => null],
            ['id' => 26, 'character_name' => 'Perrin', 'primary_weapon_id' => 3, 'secondary_weapon_id' => 4, 'character_type_id' => 1, 'user_id' => 2, 'guild_rank_id' => null, 'guild_id' => null],
            ['id' => 27, 'character_name' => 'Quorra', 'primary_weapon_id' => 7, 'secondary_weapon_id' => 2, 'character_type_id' => 2, 'user_id' => 3, 'guild_rank_id' => null, 'guild_id' => null],
            ['id' => 28, 'character_name' => 'Rowan', 'primary_weapon_id' => 1, 'secondary_weapon_id' => 5, 'character_type_id' => 3, 'user_id' => 2, 'guild_rank_id' => null, 'guild_id' => null],
            ['id' => 29, 'character_name' => 'Sienna', 'primary_weapon_id' => 6, 'secondary_weapon_id' => 3, 'character_type_id' => 1, 'user_id' => 3, 'guild_rank_id' => null, 'guild_id' => null],
            ['id' => 30, 'character_name' => 'Theron', 'primary_weapon_id' => 2, 'secondary_weapon_id' => 4, 'character_type_id' => 2, 'user_id' => 2, 'guild_rank_id' => null, 'guild_id' => null],

            ['id' => 31, 'character_name' => 'Alarion', 'primary_weapon_id' => 1, 'secondary_weapon_id' => 2, 'character_type_id' => 2, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 32, 'character_name' => 'Baelric', 'primary_weapon_id' => 3, 'secondary_weapon_id' => 4, 'character_type_id' => 1, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 33, 'character_name' => 'Cynden', 'primary_weapon_id' => 5, 'secondary_weapon_id' => 6, 'character_type_id' => 3, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 34, 'character_name' => 'Daenara', 'primary_weapon_id' => 2, 'secondary_weapon_id' => 7, 'character_type_id' => 1, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 35, 'character_name' => 'Eldrin', 'primary_weapon_id' => 6, 'secondary_weapon_id' => 1, 'character_type_id' => 2, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 36, 'character_name' => 'Faelora', 'primary_weapon_id' => 7, 'secondary_weapon_id' => 3, 'character_type_id' => 3, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 37, 'character_name' => 'Galenor', 'primary_weapon_id' => 4, 'secondary_weapon_id' => 5, 'character_type_id' => 1, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 38, 'character_name' => 'Haldor', 'primary_weapon_id' => 1, 'secondary_weapon_id' => 6, 'character_type_id' => 2, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 39, 'character_name' => 'Ithrian', 'primary_weapon_id' => 3, 'secondary_weapon_id' => 7, 'character_type_id' => 3, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 40, 'character_name' => 'Jareth', 'primary_weapon_id' => 2, 'secondary_weapon_id' => 4, 'character_type_id' => 1, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],

            ['id' => 41, 'character_name' => 'Kaelis', 'primary_weapon_id' => 1, 'secondary_weapon_id' => 2, 'character_type_id' => 2, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 42, 'character_name' => 'Lyara', 'primary_weapon_id' => 3, 'secondary_weapon_id' => 4, 'character_type_id' => 1, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 43, 'character_name' => 'Maelis', 'primary_weapon_id' => 5, 'secondary_weapon_id' => 6, 'character_type_id' => 3, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 44, 'character_name' => 'Norion', 'primary_weapon_id' => 2, 'secondary_weapon_id' => 7, 'character_type_id' => 1, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 45, 'character_name' => 'Orynth', 'primary_weapon_id' => 6, 'secondary_weapon_id' => 1, 'character_type_id' => 2, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 46, 'character_name' => 'Pyrren', 'primary_weapon_id' => 7, 'secondary_weapon_id' => 3, 'character_type_id' => 3, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 47, 'character_name' => 'Quinlan', 'primary_weapon_id' => 4, 'secondary_weapon_id' => 5, 'character_type_id' => 1, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 48, 'character_name' => 'Rhaelor', 'primary_weapon_id' => 1, 'secondary_weapon_id' => 6, 'character_type_id' => 2, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 49, 'character_name' => 'Sylwen', 'primary_weapon_id' => 3, 'secondary_weapon_id' => 7, 'character_type_id' => 3, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 50, 'character_name' => 'Tarian', 'primary_weapon_id' => 2, 'secondary_weapon_id' => 4, 'character_type_id' => 1, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],

            ['id' => 51, 'character_name' => 'Uthric', 'primary_weapon_id' => 1, 'secondary_weapon_id' => 2, 'character_type_id' => 2, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 52, 'character_name' => 'Vaelith', 'primary_weapon_id' => 3, 'secondary_weapon_id' => 4, 'character_type_id' => 1, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 53, 'character_name' => 'Wynthor', 'primary_weapon_id' => 5, 'secondary_weapon_id' => 6, 'character_type_id' => 3, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 54, 'character_name' => 'Xylaris', 'primary_weapon_id' => 2, 'secondary_weapon_id' => 7, 'character_type_id' => 1, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 55, 'character_name' => 'Yvaren', 'primary_weapon_id' => 6, 'secondary_weapon_id' => 1, 'character_type_id' => 2, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 56, 'character_name' => 'Zephirion', 'primary_weapon_id' => 7, 'secondary_weapon_id' => 3, 'character_type_id' => 3, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 57, 'character_name' => 'Aeris', 'primary_weapon_id' => 4, 'secondary_weapon_id' => 5, 'character_type_id' => 1, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 58, 'character_name' => 'Bryndis', 'primary_weapon_id' => 1, 'secondary_weapon_id' => 6, 'character_type_id' => 2, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 59, 'character_name' => 'Cassian', 'primary_weapon_id' => 3, 'secondary_weapon_id' => 7, 'character_type_id' => 3, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 60, 'character_name' => 'Dainar', 'primary_weapon_id' => 2, 'secondary_weapon_id' => 4, 'character_type_id' => 1, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],

            ['id' => 61, 'character_name' => 'Eryndor', 'primary_weapon_id' => 1, 'secondary_weapon_id' => 2, 'character_type_id' => 2, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 62, 'character_name' => 'Fenwyn', 'primary_weapon_id' => 3, 'secondary_weapon_id' => 4, 'character_type_id' => 1, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 63, 'character_name' => 'Gaius', 'primary_weapon_id' => 5, 'secondary_weapon_id' => 6, 'character_type_id' => 3, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 64, 'character_name' => 'Haleth', 'primary_weapon_id' => 2, 'secondary_weapon_id' => 7, 'character_type_id' => 1, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 65, 'character_name' => 'Ilyndra', 'primary_weapon_id' => 6, 'secondary_weapon_id' => 1, 'character_type_id' => 2, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 66, 'character_name' => 'Jorveth', 'primary_weapon_id' => 7, 'secondary_weapon_id' => 3, 'character_type_id' => 3, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 67, 'character_name' => 'Kyrian', 'primary_weapon_id' => 4, 'secondary_weapon_id' => 5, 'character_type_id' => 1, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 68, 'character_name' => 'Liora', 'primary_weapon_id' => 1, 'secondary_weapon_id' => 6, 'character_type_id' => 2, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 69, 'character_name' => 'Myrion', 'primary_weapon_id' => 3, 'secondary_weapon_id' => 7, 'character_type_id' => 3, 'user_id' => 3, 'guild_rank_id' => 4, 'guild_id' => 1],
            ['id' => 70, 'character_name' => 'Nymeris', 'primary_weapon_id' => 2, 'secondary_weapon_id' => 4, 'character_type_id' => 1, 'user_id' => 2, 'guild_rank_id' => 4, 'guild_id' => 1],
        ];

        DB::table('characters')->insert($characters);
    }
}
