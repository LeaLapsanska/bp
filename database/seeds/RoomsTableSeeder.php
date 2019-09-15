<?php

use Illuminate\Database\Seeder;
use App\Room;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(env('APP_ENV') == 'local') {
            Room::create([
                'name' => 'zasadačka',
                'capacity' => '50',
                'isActive' => true,
            ]);

            Room::create([
                'name' => 'a112',
                'capacity' => '30',
                'isActive' => true,
            ]);

            Room::create([
                'name' => 'b211',
                'capacity' => '10',
                'isActive' => true,
            ]);

            Room::create([
                'name' => 'testRoom',
                'capacity' => '15',
                'isActive' => true,
            ]);
        }
        else {
            Room::create([
                'name' => 'Konfernčná miestnosť A1',
                'capacity' => '50',
                'isActive' => true,
            ]);

            Room::create([
                'name' => 'Zasadačka B2',
                'capacity' => '15',
                'isActive' => true,
            ]);

            Room::create([
                'name' => 'Zasadačka A3',
                'capacity' => '10',
                'isActive' => true,
            ]);

            Room::create([
                'name' => 'Konferenčná miestnosť C8',
                'capacity' => '25',
                'isActive' => false,
            ]);

            Room::create([
                'name' => 'Veľká zasadačka C2',
                'capacity' => '40',
                'isActive' => true,
            ]);

            Room::create([
                'name' => 'Učebňa A2',
                'capacity' => '50',
                'isActive' => false,
            ]);
        }

    }
}
