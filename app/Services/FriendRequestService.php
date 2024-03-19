<?php

namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Models\FriendRequest;
use Illuminate\Database\Eloquent\Collection;

class FriendRequestService extends BaseModelService
{
    public function model() : Model {
       return new FriendRequest;
    }

    public function friendRequestExist($userReceiverId, $userSenderId) {
        return FriendRequest::where('user_sender_id', $userSenderId)
        ->where('user_receiver_id', $userReceiverId)
        ->first();
    }

    public function list(array $query): Collection {
        $sender = null;
        if (isset($query['sender'])) {
            $sender = $query['sender'];
        }
        return $this->model()
        ->with('sender', 'receiver')
        ->when($sender, function ($db, $s) {
            $db->where('user_sender_id', $s);
        })
        ->get();
    }
}
