<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use WithFaker;

    /**
     * A basic test example.
     *
     * @return void
     */

    // create event - admin
    public function testCreateEditLogOutDeleteEventAdmin()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // create event
        $name = $this->faker->word;
        $start = '2019-05-12 12:00';
        $end = '2019-05-12 13:30';
        $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/events',['name'=> $name,'start' => $start, 'end' => $end, 'room_id' => 1, 'invitees' => [1,4]]);
     /*   $content = json_decode(json_encode($response), true);
        var_dump($content);*/
        $response->assertStatus(201);
        $response->assertJson(['room_id' => 1, 'users' => [['id' => 1], ['id' => 4]]]);

        // get id from created event
        $content = json_decode(json_encode($response), true);
        $array = array_shift($content);
        $id = $array['original']['id'];

       // edit event
        $newName = $this->faker->word;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/events/'.$id,['name'=> $newName, 'invitees' => [1,2,3], 'room_id' => 2, 'start' => $start, 'end' => $end]);
        $response->assertStatus(200);
       // $response->assertJson(['name'=> $newName, 'users' => [['id' => 1], ['id' => 2], ['id' => 3]]]);

       // log out from event
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', '/api/removeFromEvent/'.$id);
        $response->assertStatus(204);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/events/'.$id);
        $response->assertStatus(200);

       // delete event
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', '/api/events/'.$id);
        $response->assertStatus(200);
    }

    // create event with collision - manager
    public function testCreateCollisionLogoutEditEventManager()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'marek@marek.sk', 'password' => 'marek']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // create event
        $name = $this->faker->word;
        $start = '2019-05-12 13:45';
        $end = '2019-05-12 15:30';
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/events',['name'=> $name,'start' => $start, 'end' => $end, 'room_id' => 1, 'invitees' => [1,2,4]]);
        /*   $content = json_decode(json_encode($response), true);
           var_dump($content);*/
        $response->assertStatus(201);
        $response->assertJson(['room_id' => 1, 'users' => [['id' => 1], ['id' => 2], ['id' => 4]]]);

        // get id from created event
        $content = json_decode(json_encode($response), true);
        $array = array_shift($content);
        $id = $array['original']['id'];

        // make collision
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/events',['name'=> $name,'start' => $start, 'end' => $end, 'room_id' => 1, 'invitees' => [1,4]]);
        /*   $content = json_decode(json_encode($response), true);
           var_dump($content);*/
        $response->assertStatus(409);

        // edit event
        $newName = $this->faker->word;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/events/'.$id,['name'=> $newName, 'invitees' => [1,2,3], 'room_id' => 2, 'start' => $start, 'end' => $end]);
        $response->assertStatus(200);
        // $response->assertJson(['name'=> $newName, 'users' => [['id' => 1], ['id' => 2], ['id' => 3]]]);

        // log out from event
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', '/api/removeFromEvent/'.$id);
        $response->assertStatus(204);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/events/'.$id);
        $response->assertStatus(200);

        // delete event
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', '/api/events/'.$id);
        $response->assertStatus(200);

    }

    // create events user - unauthorized
    public function testCreateEditLogoutEventUser()
    {
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // create event
        $name = $this->faker->word;
        $start = '2019-05-13 12:45';
        $end = '2019-05-13 14:30';
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/events',['name'=> $name,'start' => $start, 'end' => $end, 'room_id' => 1, 'invitees' => [1,4]]);
        /*   $content = json_decode(json_encode($response), true);
           var_dump($content);*/
        $response->assertStatus(401);

        // delete event
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', '/api/events/1');
        $response->assertStatus(401);

        // edit event
        $newName = $this->faker->word;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/events/1',['name'=> $newName, 'invitees' => [1,2,3], 'room_id' => 2]);
        $response->assertStatus(401);

    }

    // show event for admin
    public function testShowEventAdmin(){
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // show event with id 1
        $id = 1;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/events/'.$id);
        $response->assertStatus(200);

        // compare ids
        $content = json_decode(json_encode($response), true);
        $array = array_shift($content);
        $idJson = $array['original']['id'];
        $this->assertTrue($id == $idJson);

        // show event with id 2 - not invited
        $id = 2;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/myevents/'.$id);
        $response->assertStatus(401);
    }


    // show event for manager
    public function testShowEventManager(){
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'marek@marek.sk', 'password' => 'marek']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // show event with id 1
        $id = 1;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/events/'.$id);
        $response->assertStatus(200);

        // compare ids
        $content = json_decode(json_encode($response), true);
        $array = array_shift($content);
        $idJson = $array['original']['id'];
        $this->assertTrue($id == $idJson);

        // show event with id 2 - not invited
        $id = 2;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/myevents/'.$id);
        $response->assertStatus(401);

    }

    public function testLogOutFromEventUser(){
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // log out from event
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', '/api/removeFromEvent/1');
        $response->assertStatus(204);

        // show event with id 1
        $id = 1;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/myevents/'.$id);
        $response->assertStatus(401);

    }

    // show event for user
    public function testShowEventUser(){
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // show event with id 2
        $id = 2;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/events/'.$id);
        $response->assertStatus(200);

        // compare ids
        $content = json_decode(json_encode($response), true);
        $array = array_shift($content);
        $idJson = $array['original']['id'];
        $this->assertTrue($id == $idJson);


    }

    public function testShowEventsAdmin(){
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'lea@lea.sk', 'password' => 'lea']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // show all events
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/events');
        $response->assertStatus(200);

        // show own events
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/myevents');
        $response->assertStatus(200);
    }

    // show all events for admin with some parameters
    public function testShowEventsManager(){
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'marek@marek.sk', 'password' => 'marek']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // show all events
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/events');
        $response->assertStatus(200);

      /*  // show events with end date
        $time = '2019-03-29';
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/events/all', ['end_time' => $time]);
        $response->assertStatus(200);

        // show events with invitees
        $invitees = [1,2];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/events/all', ['invitees' => $invitees]);
        $response->assertStatus(200);
        $response->assertJson([['users' => [['id' => 1],['id' => 2]]]]);*/

        // show own events
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/events');
        $response->assertStatus(200);

    }

    // show all events for user = unauthorized
    public function testShowEventsUser(){
        // login
        $response = $this->json('POST', '/api/login', ['email' => 'test@test.sk', 'password' => 'test']);
        $response->assertStatus(200);
        $data = $response->json();
        $token = $data['data']['token'];

        // show all events
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/events');
        $response->assertStatus(401);

        // show own events
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', '/api/myevents');
        $response->assertStatus(200);
    }




}
