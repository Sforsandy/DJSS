<?php

use Illuminate\Database\Seeder;

class LeaderboardPointTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('leaderboard_points')->insert([
            [
            'point_condition' => 'daily_login',
            'title' => 'Daily Login',
            'point'=>'5',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'point_condition' => 'join_event',
            'title' => 'Join Event',
            'point'=>'10',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'point_condition' => 'winner',
            'title' => 'Winner',
            'point'=>'50',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'point_condition' => 'runnerup',
            'title' => 'Runner-Up',
            'point'=>'25',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'point_condition' => 'second_runnerup',
            'title' => 'Second Runner-Up',
            'point'=>'10',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
