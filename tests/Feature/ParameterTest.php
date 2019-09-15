<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Generator as Faker;

class ParameterTest extends TestCase
{
    use WithFaker;
    /**
     * A basic test example.
     *
     * @return void
     */

    // success create, edit and delete parameter - admin
    public function testCreateEditDeleteParameterAdmin()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // create parameter with faker word
        $name = $this->faker->word;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/parameters', ['name' => $name]);
        $response->assertStatus(201);
        $created_data = $response->json();
        $id = $created_data['id'];

        // edit parameter
        $name_edit = $this->faker->word;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/parameters/'.$id, ['name' => $name_edit]);
        $response->assertStatus(200);
        $response->assertJson(['name' => $name_edit]);

        // delete parameter
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', '/api/parameters/'.$id);
        $response->assertStatus(200);
        $response->assertJson(['id' => $id]);

    }

    // success create, edit and delete parameter - manager
    public function testCreateEditDeleteParameterManager()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'marek@marek.sk', 'password' => 'marek']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // create parameter
        $name = $this->faker->word;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/parameters', ['name' => $name]);
        $response->assertStatus(201);

        $created_data = $response->json();
        $id = $created_data['id'];

        // edit parameter
        $name_edit = $this->faker->word;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/parameters/'.$id, ['name' => $name_edit]);
        $response->assertStatus(200);
        $response->assertJson(['name' => $name_edit]);

        // delete parameter
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', '/api/parameters/'.$id);
        $response->assertStatus(200);
        $response->assertJson(['id' => $id]);

    }

    // create, edit and delete parameter - user = unauthorized
    public function testCreateEditDeleteParameterUser()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // create parameter
        $name = $this->faker->word;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/parameters', ['name' => $name]);
        $response->assertStatus(401);

        // edit parameter
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/parameters/2');
        $response->assertStatus(401);

        // delete parameter
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', '/api/parameters/2');
        $response->assertStatus(401);

    }

    // success show parameter for admin
    public function testShowParameterAdmin()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // show parameter with id 1
        $id = 1;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/parameters/'.$id);
        $response->assertStatus(200);
        $response->assertJson(['id' => $id]);

    }

    // success show parameter for manager
    public function testShowParameterManager()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'marek@marek.sk', 'password' => 'marek']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // show parameter with id 2
        $id = 2;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/parameters/'.$id);
        $response->assertStatus(200);
        $response->assertJson(['id' => $id]);
    }

    // show parameter for user = unauthorized
    public function testShowParameterUser()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // show parameter with id 1
        $id = 1;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/parameters/'.$id);
        $response->assertStatus(401);
    }
}
