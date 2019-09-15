<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    /*public function testExampleTest()
    {
        $this->assertTrue(true);
    }*/

    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    // no registered user
    public function testNoUserLogin()
    {
     $response = $this->json('POST','/api/login',['email' => 'bla@bla.sk', 'password' => 'bla']);
     $response->assertStatus(401);
     $response->assertJson(['status' => 'error']);
    }

    // invalid mail address
    public function testUserInvalidEmail()
    {
        $response = $this->json('POST','/api/login',['email' => 'lealea.sk', 'password' => 'lea']);
        $response->assertStatus(422);
        $response->assertJson(['status' => 'error']);
    }

    // invalid mai address
    public function testUserInvalidEmail2()
    {
        $response = $this->json('POST','/api/login',['email' => 'lealea', 'password' => 'lea']);
        $response->assertStatus(422);
        $response->assertJson(['status' => 'error']);
    }

    // success login, logout - admin
    public function testAdminLoginLogout()
    {
        $response = $this->json('POST','/api/login',['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
        $response->assertJsonStructure(['data' => ['token']]);

        $data = $response->json();
        $token = $data['data']['token'];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/logout');
        $response->assertStatus(200);
    }

    // success login, logout - manager
    public function testManagerLoginLogout()
    {
        $response = $this->json('POST','/api/login',['email' => 'marek@marek.sk', 'password' => 'marek']);
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
        $response->assertJsonStructure(['data' => ['token']]);

        $data = $response->json();
        $token = $data['data']['token'];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/logout');
        $response->assertStatus(200);
    }

    // success login, logout - user
    public function testUserLoginLogout()
    {
        $response = $this->json('POST','/api/login',['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
        $response->assertJsonStructure(['data' => ['token']]);

        $data = $response->json();
        $token = $data['data']['token'];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/logout');
        $response->assertStatus(200);
    }
}

