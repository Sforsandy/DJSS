<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(EventTypesTableSeeder::class);
        $this->call(EventFormatsTableSeeder::class);
        $this->call(GamesTableSeeder::class);
        $this->call(WinnerPositionsTableSeeder::class);
        $this->call(LeaderboardPointTableSeeder::class);
        $this->call(BonusRuleTableSeeder::class);
    }
}
