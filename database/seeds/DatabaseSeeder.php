<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminTableSeeder::class);
        $this->call(FieldTableSeeder::class);
        $this->call(CustomerTableSeeder::class);
        $this->call(TeamTableSeeder::class);
        $this->call(TeamMemberTableSeeder::class);
    }
}
