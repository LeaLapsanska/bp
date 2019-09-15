<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RoomsTableSeeder::class);
        $this->call(ParametersTableSeeder::class);
        $this->call(EventsTableSeeder::class);
		$this->call(ParameterRoomTableSeeder::class);
        $this->call(EventUserTableSeeder::class);
    }
}
