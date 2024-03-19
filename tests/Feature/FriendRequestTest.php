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

    public function test_send_friend_request_duplicate_with_ignored_fail() : void 
    {
        $user = User::factory()->create();
        $fr = FriendRequest::factory()->create([
            'user_sender_id' => $this->user->id,
            'user_receiver_id' => $user->id,
            'is_ignored' => true,
        ]);
        $response = $this->post("/add-friend/{$user->id}");
        $response->assertStatus(422);
    }

    public function test_send_friend_request_to_yourself_fail() : void 
    {
        $response = $this->post("/add-friend/{$this->user->id}");
        $response->assertStatus(422);
    }

    public function test_send_friend_request_to_an_already_friend_fail() : void {
        $user = User::factory()->create();
        $this->user->friends()->attach($user->id);

        $response = $this->post("/add-friend/{$user->id}");
        $response->assertStatus(422);
        $data = $response->decodeResponseJson();
        $this->assertEquals($data['errors'], 'you are already friends with this user');
    }

    public function test_get_my_friend_requests_success() {
        $fr = FriendRequest::factory(5)->create([
            'user_sender_id' => $this->user->id,
        ]);
        $fr2 = FriendRequest::factory(2)->create([
            'user_sender_id' => $this->user->id,
            'is_accepted' => true,
        ]);
        $fr3 = FriendRequest::factory(2)->create([
            'user_sender_id' => $this->user->id,
            'is_ignored' => true,
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

    public function test_accept_friend_request_fail() {
        $fr = FriendRequest::factory()->create([
            'is_accepted' => true,
        ]);
        $fr2 = FriendRequest::factory()->create([
            'is_ignored' => true,
        ]);
        $response = $this->post("/accept-friend-request/{$fr->id}");
        $response2 = $this->post("/accept-friend-request/{$fr2->id}");

        $response->assertStatus(404);
        $response2->assertStatus(404);
    }

    public function test_accept_friend_request_success() {
        $fr = FriendRequest::factory()->create();
        $response = $this->post("/accept-friend-request/{$fr->id}");

        $response->assertStatus(200);
        $this->assertDatabaseHas('friend_requests', [
            'id' => $fr->id,
            'is_accepted' => true, 
        ]);
        $this->assertDatabaseHas('friends', [
            'user_id' => $this->user->id,
            'user_friend_id' => $fr->receiver->id, 
        ]);
    }

    public function test_ignore_friend_request_fail() {
        $fr = FriendRequest::factory()->create([
            'is_accepted' => true,
        ]);
        $fr2 = FriendRequest::factory()->create([
            'is_ignored' => true,
        ]);
        $response = $this->post("/ignore-friend-request/{$fr->id}");
        $response2 = $this->post("/ignore-friend-request/{$fr2->id}");

        $response->assertStatus(404);
        $response2->assertStatus(404);
    }

    public function test_ignore_friend_request_success() {
        $fr = FriendRequest::factory()->create();
        $response = $this->post("/ignore-friend-request/{$fr->id}");

        $response->assertStatus(200);
        $this->assertDatabaseHas('friend_requests', [
            'id' => $fr->id,
            'is_ignored' => true, 
        ]);
    }
}
