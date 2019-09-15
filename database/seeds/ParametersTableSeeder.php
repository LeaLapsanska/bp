<?php

use Illuminate\Database\Seeder;
use App\Parameter;

class ParametersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(env('APP_ENV') == 'local') {
            Parameter::create([
                'name' => 'dataprojektor',
            ]);

            Parameter::create([
                'name' => 'tabuľa',
            ]);

            Parameter::create([
                'name' => 'počítač',
            ]);
        }
        else{
            Parameter::create([
                'name' => 'dataprojektor',
            ]);

            Parameter::create([
                'name' => 'tabuľa',
            ]);

            Parameter::create([
                'name' => 'počítač',
            ]);
            Parameter::create([
                'name' => 'klimatizácia',
            ]);

            Parameter::create([
                'name' => 'kávovar',
            ]);

            Parameter::create([
                'name' => 'ventilátor',
            ]);
            Parameter::create([
                'name' => 'televízor',
            ]);

            Parameter::create([
                'name' => 'premietacie plátno',
            ]);

        }
    }
}
