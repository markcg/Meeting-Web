<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin')->insert(
            [
            'username' => 'admin',
            'password' => '123456',
            ]
        );
        DB::table('admin')->insert(
            [
            'username' => 'admin_2',
            'password' => '123456',
            ]
        );
    }
}
