<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;

class UserTest extends TestCase
{
    use WithFaker;

    /**
     * A basic test example.
     *
     * @return void
     */

    // success create user - admin
    public function testCreateUserAdmin()
    {
        // login
        $response = $this->json('POST', '/api/login', [
            'email' => 'lea@lea.sk',
            'password' => 'lea'
        ]);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // create user
        $name = $this->faker->firstName .' '. $this->faker->lastName;
        $email = $this->faker->safeEmail;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/users', [
            'name' => $name,
            'email' => $email,
            'password' => '123456abcD',
            'isAdmin' => false,
            'isActive' => true,
            'isManager' => false]);
        $response->assertStatus(201);
    }

    // create user - user = unauthorized
    public function testCreateUserUser()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // create user
        $name = $this->faker->firstName .' '. $this->faker->lastName;
        $email = $this->faker->safeEmail;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/users', ['name' => $name, 'email' => $email, 'password' => '123456', 'isAdmin' => false, 'isActive' => true, 'isManager' => false]);
        $response->assertStatus(401);
    }

    // create user invalid mail
    public function testCreateUserEmail()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        $name = $this->faker->firstName .' '. $this->faker->lastName;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/users', ['name' => $name, 'email' => 'blabla.sk', 'password' => '123456', 'isAdmin' => false, 'isActive' => true, 'isManager' => false]);
        $response->assertStatus(422);
    }

    // create existent user
    public function testCreateUserExistent()
    {
        // admin login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // create user
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/users', ['name' => 'Test', 'email' => 'test@test.sk', 'password' => '123456', 'isAdmin' => false, 'isActive' => true, 'isManager' => false]);
        $response->assertStatus(422);
    }

    // show user for admin
    public function testReadUserSuccess()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // show user with id 3 and check ids
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/users/3');
        $response->assertStatus(200);
        $response->assertJson(['id' => 3]);
    }

    // show user which does not exist
    public function testReadUserNotExistent()
    {
        // admin login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // show user with id 11111111111111111111111
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/users/11111111111111111111111');
        $response->assertStatus(409);
    }

    // edit user - user = unauthorized
    public function testEditUserNoAdmin()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // show user with id 5
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/users/5', ['name' => 'Test', 'email' => 'testEdit@test.sk', 'password' => 'testEdit', 'isAdmin' => false, 'isActive' => true, 'isManager' => false]);
        $response->assertStatus(401);
    }

    // success edit user name - admin
    public function testEditUser()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // edit user
        $name = $this->faker->firstName .' '. $this->faker->lastName;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/users/5', ['name' => $name, 'email' => 'testEdit@test.sk', 'password' => 'testEdit', 'isAdmin' => false, 'isActive' => true, 'isManager' => false]);
        $response->assertStatus(200);
        $response->assertJson(['name' => $name]);
    }

    // edit users id
    public function testEditUserId()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // edit room with id 5
        $name = $this->faker->firstName .' '. $this->faker->lastName;
        $id_new = '111';
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/users/5', ['name' => $name, 'email' => 'testEdit@test.sk', 'password' => 'testEdit', 'isAdmin' => false, 'isActive' => true, 'isManager' => false, 'id' => $id_new]);
        $response->assertStatus(200);

        // check ids
        $content = json_decode(json_encode($response), true);
        $array = array_shift($content);
        $id = $array['original']['id'];
        $this->assertFalse($id == $id_new);

    }

    // edit user with invalid mail address
    public function testEditUserEmail()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // edit user
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/users/5', ['name' => 'TestEdit', 'email' => 'testEdittest.sk', 'password' => 'testEdit', 'isAdmin' => false, 'isActive' => true, 'isManager' => false]);
        $response->assertStatus(422);
    }

    // show profile for admin
    public function testShowProfileAdmin()
    {
        // login
        $email = 'lea@lea.sk';
        $password = 'lea';
        $id = 1;
        $response = $this->json('POST', '/api/login', ['email' => $email, 'password' => $password]);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // show profile for logged user and check ids and email addresses
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/profile');
        $response->assertStatus(200);
        $response->assertJson(['id' => $id, 'email' => $email]);
    }

    //  show profile for user
    public function testShowProfileUser()
    {
        // loggin
        $email = 'test@test.sk';
        $password = 'test';
        $id = 3;
        $response = $this->json('POST', '/api/login', ['email' => $email, 'password' => $password]);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // show profile for logged user and check ids and email addresses
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/profile');
        $response->assertStatus(200);
        $response->assertJson(['id' => $id, 'email' => $email]);
    }

    // success edit logged profile - user
    public function testEditProfileNoAdmin()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // edit profile for logged user and check edited name
        $name = $this->faker->firstName .' '. $this->faker->lastName;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PATCH', '/api/profile', ['name' => $name, 'email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $response->assertJson(['name' => $name]);
    }

    // success edit logged profile - admin
    public function testEditProfileAdmin()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // edit logged profile and check edited name
        $name = ' ' . $this->faker->lastName;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PATCH', '/api/profile', ['name' => 'Lea' . $name, 'email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $response->assertJson(['name' => 'Lea' . $name]);
    }

    // edit id for logged profile
    public function testEditProfileId()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // edit id and name for logged profile - admin
        $name = $this->faker->lastName;
        $id_new = '111';
        $token = $data['data']['token'];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PATCH', '/api/profile', ['name' => 'Lea' . $name, 'email' => 'lea@lea.sk', 'password' => 'lea', 'id' => $id_new]);
        $response->assertStatus(200);

        // check ids
        $content = json_decode(json_encode($response), true);
        $array = array_shift($content);
        $id = $array['original']['id'];
        $this->assertFalse($id == $id_new);
    }

    // edit logged profile with invalid email address
    public function testEditProfileEmail()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // edit logged profile
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PATCH', '/api/profile', ['name' => 'TestEdit', 'email' => 'testEdittest.sk', 'password' => 'testEdit', 'isAdmin' => false, 'isActive' => true, 'isManager' => false]);
        $response->assertStatus(422);
    }

    // success forgotten password
    public function testForgottenPassword()
    {
        $response = $this->json('POST', '/api/forgottenpassword', ['email' => 'testPassword@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
    }

    // forgotten password invalid email address
    public function testForgottenPasswordEmail()
    {
        $response = $this->json('POST', '/api/forgottenpassword', ['email' => 'testPasswordtest.sk', 'password' => 'test']);
        $response->assertStatus(422);
    }

    // forgotten password for user which does not exist
    public function testForgottenPasswordNoFind()
    {
        $name = str_random(8);
        $response = $this->json('POST', '/api/forgottenpassword', ['email' => $name . '@test.sk', 'password' => 'test']);
        $response->assertStatus(401);
    }

    // activate user - no admin = unauthorized
    public function testActivateUserNoAdmin()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // activate user
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PATCH', '/api/users/3/activate');
        $response->assertStatus(401);
    }

    // deactivate user - no admin = unauthorized
    public function testDeactivateUserNoAdmin()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // deatcivate user
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PATCH', '/api/users/3/deactivate');
        $response->assertStatus(401);
    }

    // success deactivate user - admin
    public function testDeactivateUser()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // deactivate user with id 3
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PATCH', '/api/users/3/deactivate');
        $response->assertStatus(200);

        // login user with id 3
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(403);
    }

    // success activate user - admin
    public function testActivateUser()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // activate user with id 3
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PATCH', '/api/users/3/activate');
        $response->assertStatus(200);

        // login user with id 3
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
    }

    // success delete user -> create and delete user
    public function testDeleteUser()
    {
        // login - admin
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // create user
        $name = $this->faker->firstName .' '. $this->faker->lastName;
        $email = $this->faker->safeEmail;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/users', ['name' => $name, 'email' => $email, 'password' => '123456', 'isAdmin' => false, 'isActive' => true, 'isManager' => false]);
        $response->assertStatus(201);

        // get id from created user
        $content = json_decode(json_encode($response), true);
        $array = array_shift($content);
        $id = $array['original']['id'];

        // delete created user and check ids
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE','/api/users/'.$id);
        $response->assertStatus(200);
        $content_new = json_decode(json_encode($response), true);
        $array_new = array_shift($content_new);
        $id_new = $array_new['original']['id'];
        $this->assertTrue($id == $id_new);

        // show deleted user
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/users/'.$id);
        $response->assertStatus(409);
    }

    // delete user - no admin = unauthorized
    public function testDeleteUserNoAdmin()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // delete user with id 3
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE','/api/users/3');
        $response->assertStatus(401);
    }

}
