<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(env('APP_ENV') == 'local'){
            User::create([
                'name' => 'Lea',
                'email' => 'lea@lea.sk',
                'password' => bcrypt('lea'),
                'isAdmin' => true,
                'isActive' => true,
                'isManager' => true
            ]);

            User::create([
                'name' => 'Marek',
                'email' => 'marek@marek.sk',
                'password' => bcrypt('marek'),
                'isAdmin' => false,
                'isActive' => true,
                'isManager' => true
            ]);

            User::create([
                'name' => 'test',
                'email' => 'test@test.sk',
                'password' => bcrypt('test'),
                'isAdmin' => false,
                'isActive' => true,
                'isManager' => false
            ]);

            User::create([
                'name' => 'testmail',
                'email' => 'lealapsanska@icloud.com',
                'password' => bcrypt('test'),
                'isAdmin' => false,
                'isActive' => true,
                'isManager' => false
            ]);

            User::create([
                'name' => 'testEdit',
                'email' => 'testEdit@test.sk',
                'password' => bcrypt('test'),
                'isAdmin' => false,
                'isActive' => true,
                'isManager' => false
            ]);

            User::create([
                'name' => 'testPassword',
                'email' => 'testPassword@test.sk',
                'password' => bcrypt('test'),
                'isAdmin' => false,
                'isActive' => true,
                'isManager' => false
            ]);
        }
        else {
            User::create([
                'name' => 'Lea Lapšanská',
                'email' => 'lealapsanska@icloud.com',
                'password' => bcrypt('LeaAdmin'),
                'isAdmin' => true,
                'isActive' => true,
                'isManager' => true
            ]);

            User::create([
                'name' => 'Ján Malý',
                'email' => 'jan.maly@example.com',
                'password' => bcrypt('JanMaly1'),
                'isAdmin' => false,
                'isActive' => true,
                'isManager' => true
            ]);

            User::create([
                'name' => 'Andrea Krátka',
                'email' => 'andrea.kratka@example.com',
                'password' => bcrypt('AndreaKratka2'),
                'isAdmin' => false,
                'isActive' => true,
                'isManager' => false
            ]);


            User::create([
                'name' => 'Peter Slobodný',
                'email' => 'peter.slobodný@example.com',
                'password' => bcrypt('PeterSlobodny3'),
                'isAdmin' => false,
                'isActive' => false,
                'isManager' => false
            ]);

            User::create([
                'name' => 'Karin Prvá',
                'email' => 'karin.prva@example.com',
                'password' => bcrypt('KarinPrva4'),
                'isAdmin' => false,
                'isActive' => true,
                'isManager' => true
            ]);
            User::create([
                'name' => 'Olívia Nová',
                'email' => 'olivia.nova@example.com',
                'password' => bcrypt('OliviaNova5'),
                'isAdmin' => false,
                'isActive' => true,
                'isManager' => false
            ]);

            User::create([
                'name' => 'Anton Konečný',
                'email' => 'anton.konecny@example.com',
                'password' => bcrypt('AntonKonecny6'),
                'isAdmin' => false,
                'isActive' => true,
                'isManager' => false
            ]);

            User::create([
                'name' => 'Richard All',
                'email' => 'richard.all@example.com',
                'password' => bcrypt('RichardAll7'),
                'isAdmin' => false,
                'isActive' => true,
                'isManager' => false
            ]);

            User::create([
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => bcrypt('JohnDoe8'),
                'isAdmin' => false,
                'isActive' => true,
                'isManager' => false
            ]);

            User::create([
                'name' => 'Jane Doe',
                'email' => 'jane.doe@example.com',
                'password' => bcrypt('JaneDoe9'),
                'isAdmin' => false,
                'isActive' => false,
                'isManager' => false
            ]);

            User::create([
                'name' => 'Marek Drnzik',
                'email' => 'marek.drnzik@example.com',
                'password' => bcrypt('MarekAdmin'),
                'isAdmin' => true,
                'isActive' => true,
                'isManager' => true
            ]);


        }
    }
}
