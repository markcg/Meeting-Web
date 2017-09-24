<?php

use Illuminate\Database\Seeder;

class TeamMemberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('team_member')->insert(
            [
            'team_id' => 1,
            'customer_id' => 1,
            'confirm' => 1,
            ]
        );
        DB::table('team_member')->insert(
            [
            'team_id' => 1,
            'customer_id' => 2,
            'confirm' => 1,
            ]
        );
        DB::table('team_member')->insert(
            [
            'team_id' => 1,
            'customer_id' => 3,
            'confirm' => 1,
            ]
        );
        DB::table('team_member')->insert(
            [
            'team_id' => 1,
            'customer_id' => 4,
            'confirm' => 1,
            ]
        );
        DB::table('team_member')->insert(
            [
            'team_id' => 1,
            'customer_id' => 5,
            'confirm' => 1,
            ]
        );
        DB::table('team_member')->insert(
            [
            'team_id' => 2,
            'customer_id' => 6,
            'confirm' => 1,
            ]
        );
        DB::table('team_member')->insert(
            [
            'team_id' => 2,
            'customer_id' => 7,
            'confirm' => 1,
            ]
        );
        DB::table('team_member')->insert(
            [
            'team_id' => 2,
            'customer_id' => 8,
            'confirm' => 1,
            ]
        );
        DB::table('team_member')->insert(
            [
            'team_id' => 2,
            'customer_id' => 9,
            'confirm' => 1,
            ]
        );
        DB::table('team_member')->insert(
            [
            'team_id' => 2,
            'customer_id' => 10,
            'confirm' => 1,
            ]
        );
    }
}
