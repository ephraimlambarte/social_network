<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Friends;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Models\User;

class FriendController extends Controller
{
    public function __construct(
        private UserService $userService) {

    }

    public function myFriends() {
        return response()->json(UserResource::collection($this->userService->getFriends(auth()->user())), 200);
    }

    public function removeFriend(User $user) {
        return response()->json($this->userService->removeFriend(auth()->user(), $user), 200);
    }
}
