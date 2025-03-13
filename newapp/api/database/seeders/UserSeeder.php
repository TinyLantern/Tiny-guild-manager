<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['id' => 1, 'discord_global_name' => 'Tiny_Lantern', 'discord_username' => 'tiny_lantern', 'discord_id' => '295953124835983360',
             'discord_avatar' => '2f80f6183fbd0adad864af35089e283b', 'last_active_character_id' => null],
            ['id' => 2, 'discord_global_name' => 'Janci', 'discord_username' => 'janci', 'discord_id' => '295953124835999990',
            'discord_avatar' => '2f8099993fbd0adad864af35089e283b', 'last_active_character_id' => null],
            ['id' => 3, 'discord_global_name' => 'Benci', 'discord_username' => 'benci', 'discord_id' => '295999924835983360',
            'discord_avatar' => '2f80f6183fbd0ad99994af35089e283b', 'last_active_character_id' => null],
        ];

        DB::table('users')->insert($users);
    }
}
