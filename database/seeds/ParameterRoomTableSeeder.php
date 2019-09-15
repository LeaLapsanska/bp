<?php

use Illuminate\Database\Seeder;
use App\Parameter;
use App\Room;


class ParameterRoomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(env('APP_ENV') == 'local') {
            DB::table('parameter_room')->insert([
                'parameter_id' => 1,
                'room_id' => 1
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 2,
                'room_id' => 1
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 3,
                'room_id' => 1
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 1,
                'room_id' => 2
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 3,
                'room_id' => 2
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 1,
                'room_id' => 4
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 2,
                'room_id' => 4
            ]);
        }
        else{
            DB::table('parameter_room')->insert([
                'parameter_id' => 1,
                'room_id' => 1
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 8,
                'room_id' => 1
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 4,
                'room_id' => 1
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 2,
                'room_id' => 1
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 6,
                'room_id' => 2
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 7,
                'room_id' => 2
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 5,
                'room_id' => 2
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 2,
                'room_id' => 3
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 3,
                'room_id' => 3
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 1,
                'room_id' => 4
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 8,
                'room_id' => 4
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 2,
                'room_id' => 4
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 3,
                'room_id' => 4
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 1,
                'room_id' => 5
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 8,
                'room_id' => 5
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 2,
                'room_id' => 5
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 2,
                'room_id' => 6
            ]);

            DB::table('parameter_room')->insert([
                'parameter_id' => 5,
                'room_id' => 6
            ]);

        }
    }
}
