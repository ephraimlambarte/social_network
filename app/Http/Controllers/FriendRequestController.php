<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FriendRequest;
use App\Services\FriendRequestService;
use App\Services\UserService;
use App\Http\Resources\FriendRequestResource;
use App\Events\FriendRequestSentEvent;
use App\Events\FriendRequestAcceptedEvent;

class FriendRequestController extends Controller
{
    public function __construct(
        private FriendRequestService $service,
        private UserService $userService,
    ) {

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

        if ($this->service->friendRequestIgnored($user->id, auth()->user()->id)) {
            return response()->json([
                'errors' => "Friend request ignored!",
            ], 422);
        }

        if ($this->userService->isFriends(auth()->user(), $user)) {
            return response()->json([
                'errors' => "you are already friends with this user",
            ], 422);
        }

        $fr = $this->service->store([
            'user_sender_id' => auth()->user()->id,
            'user_receiver_id' => $user->id,
        ]);
        
        FriendRequestSentEvent::dispatch($fr);

        return response()->json(new FriendRequestResource(
           $fr 
        ), 200);
    }

    public function myFriendRequests() {
        return response()->json(FriendRequestResource::collection(
            $this->service->list([
                'receiver' => auth()->user()->id,
                'is_accepted' => false,
                'is_ignored' => false,
            ])
        ), 200);
    }

    public function acceptFriendRequest(FriendRequest $friendRequest) {
        $friendRequestResource = new FriendRequestResource(
            $this->service->acceptFriendRequest($friendRequest)
        );
        FriendRequestAcceptedEvent::dispatch($friendRequestResource);
        $this->userService->addToFriends(auth()->user(), $friendRequest->sender);

        return response()->json($friendRequestResource, 200);
    }

    public function ignoreFriendRequest(FriendRequest $friendRequest) {
        return response()->json(new FriendRequestResource(
            $this->service->ignoreFriendRequest($friendRequest)
        ), 200);
    }

    public function searchPeople(Request $request) {
        $request->validate([
            'search_input' => 'present|nullable',
        ]);
        return response()->json(
            $this->service->getPeopleToAdd([
                'search_input' => $request->search_input,
            ])
        , 200);
    }
}
