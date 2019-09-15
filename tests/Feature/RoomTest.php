<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;

class RoomTest extends TestCase
{
    use WithFaker;

    /**
     * A basic test example.
     *
     * @return void
     */

    // success create and delete room - admin
    public function testCreateDeleteRoomAdmin()
    {
        //login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // create room
        $name = $this->faker->word;
        $capacity = random_int (1,50);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/rooms', ['name' => $name, 'capacity' => $capacity, 'isActive' => 1,'parameters' => [1,2] ]);
        $response->assertStatus(201);
        $response->assertJsonStructure(['parameters']);
        $response->assertJson(['parameters' => [['id' => 1], ['id' => 2]]]);

        // get id from created room
        $content = json_decode(json_encode($response), true);
        $array = array_shift($content);
        $id = $array['original']['id'];

        // delete room
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', '/api/rooms/' . $id);
        $response->assertStatus(200);

    }

    // success create and edit room - manager
    public function testCreateDeleteRoomManager()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'marek@marek.sk', 'password' => 'marek']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // create room
        $name = $this->faker->word;
        $capacity = random_int (1,50);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/rooms', ['name' => $name, 'capacity' => $capacity, 'isActive' => 1, 'parameters' => []]);
        $response->assertStatus(201);

        // get id from created room
        $content = json_decode(json_encode($response), true);
        $array = array_shift($content);
        $id = $array['original']['id'];

        // delete room
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', '/api/rooms/' . $id);
        $response->assertStatus(200);
    }

    // success create and edit room - user
    public function testCreateDeleteRoomUser()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // create room
        $name = $this->faker->word;
        $capacity = random_int (1,50);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/rooms', ['name' => $name, 'capacity' => $capacity, 'isActive' => 1, 'parameters' => []]);
        $response->assertStatus(401);

        $id = 2;

        // delete room
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', '/api/rooms/' . $id);
        $response->assertStatus(401);
    }

    // create room without name -> name is required
    public function testCreateRoomNameValid()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'marek@marek.sk', 'password' => 'marek']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // create room
        $capacity = random_int (1,50);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/rooms', ['capacity' => $capacity]);
        $response->assertStatus(422);
    }

    // success show room for admin
    public function testShowRoomAdmin()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // show room with id 1, shows all details with parameters and events
        $id = 1;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/rooms/'.$id);
        $response->assertStatus(200);
        $response->assertJson(['id' => $id]);
        $response->assertJsonStructure(['events', 'parameters']);
    }

    // success show room for manager
    public function testShowRoomManager()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'marek@marek.sk', 'password' => 'marek']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // show room with id 1, show all details with parameters and events
        $id = 1;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/rooms/'.$id);
        $response->assertStatus(200);
        $response->assertJson(['id' => $id]);
        $response->assertJsonStructure(['events', 'parameters']);
    }

    // success show room for user
    public function testShowRoomUser()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // show room with 1, show all details with parameters and events
        $id = 1;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/rooms/'.$id);
        $response->assertStatus(200);
        $response->assertJson(['id' => $id]);
        $response->assertJsonStructure(['events', 'parameters']);
    }

    // success room edit - admin
    public function testEditRoomAdmin()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // set all values for room
        $id = 4;
        $name = 'testRoom_A_'. $this->faker->word;
        $capacity = random_int (1,50);
        $parameters = [1];

        // edit room and check parameters
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/rooms/'.$id, ['name' => $name, 'capacity' => $capacity, 'isActive' => 1, 'parameters' => $parameters]);
        $response->assertStatus(200);
        $response->assertJson(['id' => $id]);
        $response->assertJson(['parameters' => [['id' => 1]]]);
    }

    // success room edit - manager
    public function testEditRoomManager()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'marek@marek.sk', 'password' => 'marek']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // values for room
        $id = 4;
        $name = $this->faker->word;
        $capacity = random_int (1,50);
        $parameters = [1,2];

        // edit room and check parameters
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/rooms/'.$id, ['name' => $name, 'capacity' => $capacity, 'isActive' => 1, 'parameters' => $parameters]);
        $response->assertStatus(200);
        $response->assertJson(['id' => $id]);
        $response->assertJson(['parameters' => [['id' => $parameters[0]], ['id' => $parameters[1]]]]);

    }

    // edit room - user = unauthorized
    public function testEditRoomUser()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // values for room
        $id = 4;
        $name = $this->faker->word;
        $capacity = random_int (1,50);
        $parameters = [1,2];

        // edit room
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/rooms/'.$id, ['name' => $name, 'capacity' => $capacity, 'isActive'=> 1, 'parameters' => $parameters]);
        $response->assertStatus(401);

    }

    // edit room - name validation
    public function testEditRoomNameValid()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'marek@marek.sk', 'password' => 'marek']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // create room
        $id = 4;
        $capacity = random_int (1,50);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/rooms/'.$id, ['capacity' => $capacity]);
        $response->assertStatus(422);
    }

    // success deactivate and activate room with show tests - admin
    public function testActiveDeactvieShowRoomAdmin()
    {
        $id = 1;

        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // deactivate room with id 1
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PATCH', '/api/rooms/'. $id . '/deactivate');
        $response->assertStatus(400);

        // show deactivated room
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/rooms/'.$id);
        $response->assertStatus(200);

        // activate room
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PATCH', '/api/rooms/'. $id . '/activate');
        $response->assertStatus(200);

        // show activated room and check ids
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/rooms/'.$id);
        $response->assertStatus(200);
        $response->assertJson(['id' => $id]);
    }

    // success deactivate and activate room with show tests - manager
    public function testActiveDeactiveShowRoomManager()
    {
        $id = 1;

        // login
        $response = $this->json('POST', '/api/login', ['email' => 'marek@marek.sk', 'password' => 'marek']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // deactivate room
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PATCH', '/api/rooms/'. $id . '/deactivate');
        $response->assertStatus(400);

        // show deactivated room
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/rooms/'.$id);
        $response->assertStatus(200);

        // activate room
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PATCH', '/api/rooms/'. $id . '/activate');
        $response->assertStatus(200);

        // show activated room and check ids
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/rooms/'.$id);
        $response->assertStatus(200);
        $response->assertJson(['id' => $id]);
    }


   /* // vyhľadanaie miestnosti na základe parametrov - admin
    public function testSearchRoomAdmin()
    {
        // kontorola parametru zasadačka v miestnosti zasadačka -> id = 1
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // vyhľadanie miestností a kontrola id
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/rooms/search', ['keyword' => 'dataprojektor']);
        $response->assertStatus(200);
        $response->assertJson([['id' => 1]]);
    }

    // vyhľadanaie miestnosti na základe parametrov - manager
    public function testSearchRoomManager()
    {
        // kontorola parametru zasadačka v miestnosti zasadačka -> id = 1
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'marek@marek.sk', 'password' => 'marek']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // vyhľadanie miestností a kontrola id
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/rooms/search', ['keyword' => 'dataprojektor']);
        $response->assertStatus(200);
        $response->assertJson([['id' => 1]]);
    }

    // vyhľadanaie miestnosti na základe parametrov - user
    public function testSearchRoomUser()
    {
        // kontorola parametru zasadačka v miestnosti zasadačka -> id = 1
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // vyhľadanie miestností a kontrola id
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/rooms/search', ['keyword' => 'dataprojektor']);
        $response->assertStatus(200);
        $response->assertJson([['id' => 1]]);
    }*/

}
