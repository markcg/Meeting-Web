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
      DB::table('field')->insert([
          'name' => 'Football Field',
          'description' => 'Indoor football field',
      ]);
    }
}
