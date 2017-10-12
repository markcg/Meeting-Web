<?php

use Illuminate\Database\Seeder;

class FieldTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('field')->insert(
            [
            'name' => 'Football A Field',
            'description' => 'Indoor football field',
            'email' => 'field_a@f.com',
            'address' => '100/20 Address to Field',
            'phone_number' => '0123456789',
            'username' => 'field_a',
            'password' => '123456',
            'latitude' => '18.796143',
            'longitude' => '98.979263',
            'confirm' => '0',
            ]
        );
        DB::table('field')->insert(
            [
            'name' => 'Football B Field',
            'description' => 'Indoor football field',
            'email' => 'field_b@f.com',
            'address' => '100/20 Address to Field',
            'phone_number' => '0123456789',
            'username' => 'field_b',
            'password' => '123456',
            'latitude' => '18.796143',
            'longitude' => '98.979263',
            'confirm' => '1',
            ]
        );
        DB::table('field')->insert(
            [
            'name' => 'Football C Field',
            'description' => 'Indoor football field',
            'email' => 'field_c@f.com',
            'address' => '100/20 Address to Field',
            'phone_number' => '0123456789',
            'username' => 'field_c',
            'password' => '123456',
            'latitude' => '18.796143',
            'longitude' => '98.979263',
            'confirm' => '1',
            ]
        );
        DB::table('field')->insert(
            [
            'name' => 'Football D Field',
            'description' => 'Indoor football field',
            'email' => 'field_d@f.com',
            'address' => '100/20 Address to Field',
            'phone_number' => '0123456789',
            'username' => 'field_d',
            'password' => '123456',
            'latitude' => '18.796143',
            'longitude' => '98.979263',
            'confirm' => '1',
            ]
        );
        DB::table('field')->insert(
            [
            'name' => 'Football E Field',
            'description' => 'Indoor football field',
            'email' => 'field_e@f.com',
            'address' => '100/20 Address to Field',
            'phone_number' => '0123456789',
            'username' => 'field_e',
            'password' => '123456',
            'latitude' => '18.796143',
            'longitude' => '98.979263',
            'confirm' => '1',
            ]
        );
    }
}
