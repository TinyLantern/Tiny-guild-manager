<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuildMastersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($id = 1; $id <= 10; $id++) {
            DB::table('characters')
                ->where('id', $id)
                ->update([
                    'guild_id' => $id,
                    'guild_rank_id' => 1
                ]);
        }
    }
}
