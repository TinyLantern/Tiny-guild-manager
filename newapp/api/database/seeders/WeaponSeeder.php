<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeaponSeeder extends Seeder
{
    public function run()
    {
        $weapons = [
            ['id' => 1, 'name' => 'Sword and Shield'],
            ['id' => 2, 'name' => 'Spear'],
            ['id' => 3, 'name' => 'Greatsword'],
            ['id' => 4, 'name' => 'Staff'],
            ['id' => 5, 'name' => 'Wand and Tome'],
            ['id' => 6, 'name' => 'Longbow'],
            ['id' => 7, 'name' => 'Crossbows'],
            ['id' => 8, 'name' => 'Daggers'],
        ];

        DB::table('weapons')->insert($weapons);
    }
}
