<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('my-inbox.{userId}', function (User $user, int $userId) {
    return $user->id === $userId;
});
Broadcast::channel('messages.{userId}', function (User $user, int $userId) {
    return $user->id !== $userId;
});
Broadcast::channel('friend-request-received.{userId}', function (User $user, int $userId) {
    return $user->id === $userId;
});
Broadcast::channel('friend-request-accepted.{userId}', function (User $user, int $userId) {
    return $user->id === $userId;
});