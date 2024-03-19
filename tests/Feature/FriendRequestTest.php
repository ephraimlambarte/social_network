<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\FriendRequest;

class FriendRequestTest extends TestCase
{
    public function setUp() : void {
        parent::setUp();
        $this->actingAs($this->user); 
    }

    public function test_send_friend_request_success(): void
    {
        $user = User::factory()->create();

        $response = $this->post("/add-friend/{$user->id}");
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'sender' => [
                'id',
            ],
            'receiver' => [
                'id'
            ],                  
        ]);
        $data = $response->decodeResponseJson();
        $this->assertEquals($this->user->id, $data['sender']['id']);
        $this->assertEquals($user->id, $data['receiver']['id']);
    }

    public function test_send_friend_request_duplicate_fail() : void 
    {
        $user = User::factory()->create();
        $fr = FriendRequest::factory()->create([
            'user_sender_id' => $this->user->id,
            'user_receiver_id' => $user->id,
        ]);
        $response = $this->post("/add-friend/{$user->id}");
        $response->assertStatus(422);
    }

    public function test_send_friend_request_to_yourself_fail() : void 
    {
        $response = $this->post("/add-friend/{$this->user->id}");
        $response->assertStatus(422);
    }

    public function test_get_my_friend_requests_success() {
        $fr = FriendRequest::factory(5)->create([
            'user_sender_id' => $this->user->id,
        ]);
        $response = $this->get("/my-friend-requests");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'sender' => [
                    'id',
                ],
                'receiver' => [
                    'id',
                ],
            ],             
        ]);
        $data = $response->decodeResponseJson();
        $this->assertEquals(5, count($data));
    }
}
