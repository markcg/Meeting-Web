<?php

use Illuminate\Database\Seeder;

class TeamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('team')->insert(
            [
            'customer_id' => 1,
            'name' => 'Team Lionel A',
            'description' => 'Football lionel team A',
            ]
        );
        DB::table('team')->insert(
            [
            'customer_id' => 1,
            'name' => 'Team Lionel B',
            'description' => 'Football lionel team B',
            ]
        );
        DB::table('team')->insert(
            [
            'customer_id' => 1,
            'name' => 'Team Lionel C',
            'description' => 'Football lionel team C',
            ]
        );
        DB::table('team')->insert(
            [
            'customer_id' => 1,
            'name' => 'Team Lionel D',
            'description' => 'Football lionel team D',
            ]
        );
        DB::table('team')->insert(
            [
            'customer_id' => 1,
            'name' => 'Team Lionel E',
            'description' => 'Football lionel team E',
            ]
        );
        DB::table('team')->insert(
            [
            'customer_id' => 2,
            'name' => 'Team Ronaldo A',
            'description' => 'Football Ronaldo team A',
            ]
        );
        DB::table('team')->insert(
            [
            'customer_id' => 2,
            'name' => 'Team Ronaldo B',
            'description' => 'Football Ronaldo team B',
            ]
        );
        DB::table('team')->insert(
            [
            'customer_id' => 2,
            'name' => 'Team Ronaldo C',
            'description' => 'Football Ronaldo team C',
            ]
        );
        DB::table('team')->insert(
            [
            'customer_id' => 2,
            'name' => 'Team Ronaldo D',
            'description' => 'Football Ronaldo team D',
            ]
        );
        DB::table('team')->insert(
            [
            'customer_id' => 2,
            'name' => 'Team Ronaldo E',
            'description' => 'Football Ronaldo team E',
            ]
        );
    }
}
