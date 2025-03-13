<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Essential
            WeaponSeeder::class,
            CharTypeSeeder::class,
            ServerSeeder::class,
            GuildApplicationStatusSeeder::class,
            GuildRankSeeder::class,
            ActivityParticipationStatusSeeder::class,

            // Non essential
            
            UserSeeder::class,
            CharacterSeeder::class,
            GuildSeeder::class,
            CharactersWithGuildSeeder::class,
            GuildApplicationSeeder::class,
            GuildMastersSeeder::class,
            ActivitySeeder::class,
            ActivityParticipantsSeeder::class,
            
        ]);
    }
}