<?php

use Illuminate\Database\Seeder;

class EventUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(env('APP_ENV') == 'local') {
            DB::table('event_user')->insert([
                'user_id' => 1,
                'event_id' => 1
            ]);

            DB::table('event_user')->insert([
                'user_id' => 2,
                'event_id' => 1
            ]);

            DB::table('event_user')->insert([
                'user_id' => 3,
                'event_id' => 1
            ]);

            DB::table('event_user')->insert([
                'user_id' => 4,
                'event_id' => 1
            ]);

            DB::table('event_user')->insert([
                'user_id' => 3,
                'event_id' => 2
            ]);
        }
        else{
            DB::table('event_user')->insert([
                'user_id' => 1,
                'event_id' => 1
            ]);

            DB::table('event_user')->insert([
                'user_id' => 2,
                'event_id' => 1
            ]);

            DB::table('event_user')->insert([
                'user_id' => 3,
                'event_id' => 1
            ]);

            DB::table('event_user')->insert([
                'user_id' => 4,
                'event_id' => 1
            ]);

            DB::table('event_user')->insert([
                'user_id' => 1,
                'event_id' => 2
            ]);

            DB::table('event_user')->insert([
                'user_id' => 4,
                'event_id' => 2
            ]);

            DB::table('event_user')->insert([
                'user_id' => 3,
                'event_id' => 2
            ]);

            DB::table('event_user')->insert([
                'user_id' => 7,
                'event_id' => 2
            ]);

            DB::table('event_user')->insert([
                'user_id' => 2,
                'event_id' => 2
            ]);

            DB::table('event_user')->insert([
                'user_id' => 2,
                'event_id' => 3
            ]);

            DB::table('event_user')->insert([
                'user_id' => 5,
                'event_id' => 3
            ]);

            DB::table('event_user')->insert([
                'user_id' => 9,
                'event_id' => 3
            ]);

            DB::table('event_user')->insert([
                'user_id' => 3,
                'event_id' => 3
            ]);

            DB::table('event_user')->insert([
                'user_id' => 1,
                'event_id' => 4
            ]);

            DB::table('event_user')->insert([
                'user_id' => 2,
                'event_id' => 4
            ]);

            DB::table('event_user')->insert([
                'user_id' => 3,
                'event_id' => 4
            ]);

            DB::table('event_user')->insert([
                'user_id' => 4,
                'event_id' => 4
            ]);

            DB::table('event_user')->insert([
                'user_id' => 5,
                'event_id' => 4
            ]);

            DB::table('event_user')->insert([
                'user_id' => 6,
                'event_id' => 4
            ]);

            DB::table('event_user')->insert([
                'user_id' => 7,
                'event_id' => 4
            ]);

            DB::table('event_user')->insert([
                'user_id' => 8,
                'event_id' => 4
            ]);

            DB::table('event_user')->insert([
                'user_id' => 9,
                'event_id' => 4
            ]);

            DB::table('event_user')->insert([
                'user_id' => 10,
                'event_id' => 4
            ]);

            DB::table('event_user')->insert([
                'user_id' => 11,
                'event_id' => 4
            ]);

            DB::table('event_user')->insert([
                'user_id' => 11,
                'event_id' => 5
            ]);

            DB::table('event_user')->insert([
                'user_id' => 1,
                'event_id' => 5
            ]);
        }
    }
}
