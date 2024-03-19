<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FriendRequest;
use App\Services\FriendRequestService;
use App\Http\Resources\FriendRequestResource;

class FriendRequestController extends Controller
{
    public function __construct(private FriendRequestService $service) {

    }

    public function addFriend(Request $request, User $user) {
        if ($user->id === auth()->user()->id) {
            return response()->json([
                'errors' => "cannot add yourself as friend!",
            ], 422);
        }

        if ($this->service->friendRequestExist($user->id, auth()->user()->id)) {
            return response()->json([
                'errors' => "Friend request already sent!",
            ], 422);
        }

        return response()->json(new FriendRequestResource(
            $this->service->store([
                'user_sender_id' => auth()->user()->id,
                'user_receiver_id' => $user->id,
            ])
        ), 200);
    }

    public function myFriendRequests() {
        return response()->json(FriendRequestResource::collection(
            $this->service->list([
                'sender' => auth()->user()->id,
            ])
        ), 200);
    }
}
