<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
            'firstname' => 'super',
            'lastname' => 'admin',
            'email' => 'superadmin@gmail.com',
            'mobile_no'=> '9999999999',
            'password' => bcrypt('9999999999'),
            'character_name' => 'admin',
            'refer_code'=> 'ADM12345',
            'status'=> '1',
            'gender'=> 'male',
            'reg_date' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'firstname' => 'moderator',
            'lastname' => 'moderator',
            'email' => 'moderator@gmail.com',
            'mobile_no'=> '1111111111',
            'password' => bcrypt('1111111111'),
            'character_name' => 'moderator',
            'refer_code'=> 'MOD12345',
            'status'=> '1',
            'gender'=> 'male',
            'reg_date' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'firstname' => 'sudhir',
            'lastname' => 'beladiya',
            'email' => 'sudhir@gmail.com',
            'mobile_no'=> '9033334288',
            'password' => bcrypt('9033334288'),
            'character_name' => 'general_user',
            'refer_code'=> 'SUD12345',
            'status'=> '1',
            'gender'=> 'male',
            'reg_date' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
            'firstname' => 'maulik',
            'lastname' => 'patel',
            'email' => 'maulik@gmail.com',
            'mobile_no'=> '8128251152',
            'password' => bcrypt('8128251152'),
            'character_name' => 'general_user',
            'refer_code'=> 'MUL12345',
            'status'=> '1',
            'gender'=> 'male',
            'reg_date' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
