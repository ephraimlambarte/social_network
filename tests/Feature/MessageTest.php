<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Message;

class MessageTest extends TestCase
{
    public function setUp() : void {
        parent::setUp();
        $this->actingAs($this->user); 
    }

    public function test_fetch_user_inbox_success(): void
    {
        $users = User::factory(3)->create();

        Message::factory()->create([
            'user_sender_id' => $this->user->id,
            'user_receiver_id' => $users[0]->id,
            'message' => 'hello',
        ]);
        Message::factory()->create([
            'user_sender_id' => $users[0]->id,
            'user_receiver_id' => $this->user->id,
            'message' => 'hi',
        ]);

        Message::factory()->create([
            'user_sender_id' => $users[1]->id,
            'message' => 'tr',
            'user_receiver_id' => $this->user->id,
        ]);
        Message::factory()->create([
            'user_sender_id' => $users[1]->id,
            'user_receiver_id' => $this->user->id,
            'message' => 'td',
        ]);

        Message::factory()->create([
            'user_sender_id' => $this->user->id,
            'user_receiver_id' => $users[2]->id,
            'message' => 'km',
        ]);
        Message::factory()->create([
            'user_sender_id' => $this->user->id,
            'user_receiver_id' => $users[2]->id,
            'message' => 'mk',
        ]);

        $response = $this->get('/my-inbox');
        $response->assertStatus(200);
        $data = $response->decodeResponseJson();
        $this->assertEquals(3, count($data));
        $this->assertEquals('mk', $data[0]['message']);
        $this->assertEquals('td', $data[1]['message']);
        $this->assertEquals('hi', $data[2]['message']);
    }

    public function test_get_messages_success() : void {
        $user = User::factory()->create();
        Message::factory()->create([
            'user_sender_id' => $this->user->id,
            'user_receiver_id' => $user->id,
            'message' => 'km',
        ]);
        Message::factory()->create([
            'user_sender_id' => $user->id,
            'user_receiver_id' =>  $this->user->id,
            'message' => 'mk',
        ]);
        $response = $this->get("/messages/$user->id");
        $response->assertStatus(200);
        $data = $response->decodeResponseJson();
        $this->assertEquals(2, count($data));
    }

    public function test_send_message_to_a_non_friend_fail() : void {
        $user = User::factory()->create();
        $response = $this->post("/send-message/$user->id", [
            'message' => 'hi there',
        ]);
        $response->assertStatus(422);
        $data = $response->decodeResponseJson();
        $response->assertJsonStructure([
            'errors',
        ]);
    }

    public function test_send_message_to_a_friend_success() : void {
        $user = User::factory()->create();

        $this->user->friends()->attach($user->id);

        $response = $this->post("/send-message/$user->id", [
            'message' => 'hi there',
        ]);
        $response->assertStatus(200);
        $data = $response->decodeResponseJson();
        $response->assertJsonStructure([
            'message',
        ]);
        $this->assertDatabaseHas('messages', [
            'user_sender_id' => $this->user->id,
            'user_receiver_id' => $user->id,
            'message' => 'hi there', 
        ]);
    }
}
