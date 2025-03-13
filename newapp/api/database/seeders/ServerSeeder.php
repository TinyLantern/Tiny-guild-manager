<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servers = [
            ['id' => 1, 'name' => 'Abundance'],
            ['id' => 2, 'name' => 'Adriana'],
            ['id' => 3, 'name' => 'Anvy'],
            ['id' => 4, 'name' => 'Arkhan'],
            ['id' => 5, 'name' => 'Arnette'],
            ['id' => 6, 'name' => 'Basilisk'],
            ['id' => 7, 'name' => 'Bellandir'],
            ['id' => 8, 'name' => 'Benny'],
            ['id' => 9, 'name' => 'Brent'],
            ['id' => 10, 'name' => 'Conquest'],
            ['id' => 11, 'name' => 'Cornelius'],
            ['id' => 12, 'name' => 'Destruction'],
            ['id' => 13, 'name' => 'Domoore'],
            ['id' => 14, 'name' => 'Elleia'],
            ['id' => 15, 'name' => 'Ember'],
            ['id' => 16, 'name' => 'Excavator'],
            ['id' => 17, 'name' => 'Ezekiel'],
            ['id' => 18, 'name' => 'Justice'],
            ['id' => 19, 'name' => 'Junath'],
            ['id' => 20, 'name' => 'Karchars'],
            ['id' => 21, 'name' => 'Kazar'],
            ['id' => 22, 'name' => 'Leonardas'],
            ['id' => 23, 'name' => 'Lequirus'],
            ['id' => 24, 'name' => 'Lightbringer'],
            ['id' => 25, 'name' => 'Mallok'],
            ['id' => 26, 'name' => 'Mankipak'],
            ['id' => 27, 'name' => 'Oliver'],
            ['id' => 28, 'name' => 'Paola'],
            ['id' => 29, 'name' => 'Pakilo'],
            ['id' => 30, 'name' => 'Penance'],
            ['id' => 31, 'name' => 'Porfos'],
            ['id' => 32, 'name' => 'Payton'],
            ['id' => 33, 'name' => 'Rumi'],
            ['id' => 34, 'name' => 'Shaderock'],
            ['id' => 35, 'name' => 'Soiri'],
            ['id' => 36, 'name' => 'Stallof'],
            ['id' => 37, 'name' => 'Starfall'],
            ['id' => 38, 'name' => 'Talem'],
            ['id' => 39, 'name' => 'Talus'],
            ['id' => 40, 'name' => 'Titanreach'],
            ['id' => 41, 'name' => 'Torrent'],
            ['id' => 42, 'name' => 'Vanfeld'],
            ['id' => 43, 'name' => 'Vortex'],
            ['id' => 44, 'name' => 'Windwhisper'],
            ['id' => 45, 'name' => 'Wraith'],
            ['id' => 46, 'name' => 'Zarek'],
            ['id' => 47, 'name' => 'Zenith'],
        ];
        

        DB::table('servers')->insert($servers);
    }
}
