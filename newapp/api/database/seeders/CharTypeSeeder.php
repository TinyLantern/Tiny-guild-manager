<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CharTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $character_types = [
            ['id' => 1, 'type' => 'DPS'],
            ['id' => 2, 'type' => 'Healer'],
            ['id' => 3, 'type' => 'Tank'],
        ];

        DB::table('character_types')->insert($character_types);
    }
}
