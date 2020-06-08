<?php

use Illuminate\Database\Seeder;

class BonusRuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('bonus_rules')->insert([
            [
            'name' => 'Sign with refer code',
            'rule' => 'sign_with_refer_code',
            'amount' => '100',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'name' => 'Referrer user earn',
            'rule' => 'referrer_earn',
            'amount' => '50',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'name' => '3 Paid event per day',
            'rule' => '3_paid_event_per_day',
            'amount' => '50',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'name' => '1 Paid event consecutive 3day',
            'rule' => '1paid_event_consecutive_3day',
            'amount' => '30',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'name' => '5 paid event per week',
            'rule' => '5paid_event_per_week',
            'amount' => '25',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
