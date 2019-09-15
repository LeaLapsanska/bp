<?php

use Illuminate\Database\Seeder;
use App\Event;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(env('APP_ENV') == 'local') {
            Event::create([
                'name' => 'porada',
                'start' => '2019-03-01 10:30',
                'end' => '2019-03-01 12:00',
                'room_id' => 1,
                'user_id' => 1
            ]);

            Event::create([
                'name' => 'skolenie manazerov',
                'start' => '2019-03-29 12:30',
                'end' => '2019-03-29 15:00',
                'room_id' => 1,
                'user_id' => 1
            ]);

            Event::create([
                'name' => 'test Evnet',
                'start' => '2019-03-22 12:30',
                'end' => '2019-03-22 15:00',
                'room_id' => 1,
                'user_id' => 1
            ]);
        }
        else{
            Event::create([
                'name' => 'Porada developerov',
                'start' => '2019-05-10 10:30',
                'end' => '2019-05-10 12:00',
                'room_id' => 1,
                'user_id' => 1
            ]);

            Event::create([
                'name' => 'Školenie manažérov',
                'start' => '2019-06-24 12:30',
                'end' => '2019-06-24 15:00',
                'room_id' => 3,
                'user_id' => 2
            ]);

            Event::create([
                'name' => 'Porada grafikov',
                'start' => '2019-05-27 09:30',
                'end' => '2019-05-27 11:00',
                'room_id' => 2,
                'user_id' => 5
            ]);

            Event::create([
                'name' => 'Predstavenie nového projektu',
                'start' => '2019-06-19 10:30',
                'end' => '2019-06-29 12:00',
                'room_id' => 5,
                'user_id' => 1
            ]);

            Event::create([
                'name' => 'Pohovor - tester',
                'start' => '2019-06-21 10:30',
                'end' => '2019-06-21 12:00',
                'room_id' => 3,
                'user_id' => 5
            ]);


        }
    }
}
