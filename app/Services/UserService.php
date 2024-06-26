<?php

namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserService extends BaseModelService {
    public function model() : Model {
        return new User;
    }

    public function isFriends(User $user, User $user2) {
        if ($user->friends()->where('users.id', $user2->id)->first()) {
            return true;
        }
        if ($user->friendOf()->where('users.id', $user2->id)->first()) {
            return true;
        }
        return false;
    }

    public function getFriends(User $user) {
        $friends = $user->friends;
        $allFriends = $friends->merge($user->friendOf);
        return $allFriends;
    }

    public function addToFriends(User $user, User $user2) {
        $user->friends()->attach($user2->id);
        return $user;
    }

    public function removeFriend(User $user, User $user2) {
        $user->friends()->detach($user2->id);
        $user2->friends()->detach($user->id);
        return true;
    }
}