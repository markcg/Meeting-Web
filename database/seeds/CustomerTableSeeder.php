<?php

use Illuminate\Database\Seeder;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customer')->insert(
            [
            'name' => 'Lionel Messi',
            'email' => 'messi@m.com',
            'phone_number' => '0123456789',
            'username' => 'user_a',
            'password' => '123456',
            'latitude' => '18.796143',
            'longitude' => '98.979263',
            ]
        );
        DB::table('customer')->insert(
            [
            'name' => 'Cristiano Ronaldo',
            'email' => 'ronaldo@m.com',
            'phone_number' => '0123456789',
            'username' => 'user_b',
            'password' => '123456',
            'latitude' => '18.796143',
            'longitude' => '98.979263',
            ]
        );
        DB::table('customer')->insert(
            [
            'name' => 'Xavi',
            'email' => 'xavi@m.com',
            'phone_number' => '0123456789',
            'username' => 'user_c',
            'password' => '123456',
            'latitude' => '18.796143',
            'longitude' => '98.979263',
            ]
        );
        DB::table('customer')->insert(
            [
            'name' => 'Andres Iniesta',
            'email' => 'iniesta@m.com',
            'phone_number' => '0123456789',
            'username' => 'user_d',
            'password' => '123456',
            'latitude' => '18.796143',
            'longitude' => '98.979263',
            ]
        );
        DB::table('customer')->insert(
            [
            'name' => 'Zlatan Ibrahimovic',
            'email' => 'zlatan@m.com',
            'phone_number' => '0123456789',
            'username' => 'user_e',
            'password' => '123456',
            'latitude' => '18.796143',
            'longitude' => '98.979263',
            ]
        );
        DB::table('customer')->insert(
            [
            'name' => 'Radamel Falcao',
            'email' => 'falcao@m.com',
            'phone_number' => '0123456789',
            'username' => 'user_f',
            'password' => '123456',
            'latitude' => '18.796143',
            'longitude' => '98.979263',
            ]
        );
        DB::table('customer')->insert(
            [
            'name' => 'Robin van Persie',
            'email' => 'persie@m.com',
            'phone_number' => '0123456789',
            'username' => 'user_g',
            'password' => '123456',
            'latitude' => '18.796143',
            'longitude' => '98.979263',
            ]
        );
        DB::table('customer')->insert(
            [
            'name' => 'Andrea Pirlo',
            'email' => 'pirlo@m.com',
            'phone_number' => '0123456789',
            'username' => 'user_h',
            'password' => '123456',
            'latitude' => '18.796143',
            'longitude' => '98.979263',
            ]
        );
        DB::table('customer')->insert(
            [
            'name' => 'Yaya Toure',
            'email' => 'toure@m.com',
            'phone_number' => '0123456789',
            'username' => 'user_i',
            'password' => '123456',
            'latitude' => '18.796143',
            'longitude' => '98.979263',
            ]
        );
        DB::table('customer')->insert(
            [
            'name' => 'Edinson Cavani',
            'email' => 'cavani@m.com',
            'phone_number' => '0123456789',
            'username' => 'user_j',
            'password' => '123456',
            'latitude' => '18.796143',
            'longitude' => '98.979263',
            ]
        );
        /* New Seeder */
        DB::table('customer')->insert(
            [
            'name' => 'Kanchanit Puapun',
            'email' => 'jomsucre@gmai.com',
            'phone_number' => '0882634644',
            'username' => 'kanchanit1',
            'password' => 'jom123',
            'latitude' => '18.796367351551',
            'longitude' => '98.95334243774414',
            ]
        );
    }
}
