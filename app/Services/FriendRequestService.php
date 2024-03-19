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
        ->where('is_accepted', false)
        ->where('is_ignored', false)
        ->first();
    }

    public function friendRequestIgnored($userReceiverId, $userSenderId) {
        return FriendRequest::where('user_sender_id', $userSenderId)
        ->where('user_receiver_id', $userReceiverId)
        ->where('is_accepted', false)
        ->where('is_ignored', true)
        ->first();
    }

    public function list(array $query): Collection {
        $sender = null;
        $isAccepted = null;
        $isIgnored = null;

        if (isset($query['sender'])) {
            $sender = $query['sender'];
        }

        if (isset($query['is_accepted'])) {
            $isAccepted = $query['is_accepted'];
        }

        if (isset($query['is_ignored'])) {
            $isIgnored = $query['is_ignored'];
        }

        return $this->model()
        ->with('sender', 'receiver')
        ->when($sender, function ($db, $val) {
            $db->where('user_sender_id', $val);
        })
        ->when($isAccepted !==  null, function ($db) use ($isAccepted) {
            $db->where('is_accepted', $isAccepted);
        })
        ->when($isIgnored !== null, function ($db) use ($isIgnored) {
            $db->where('is_ignored', $isIgnored);
        })
        ->get();
    }

    public function acceptFriendRequest(FriendRequest $friendRequest) {
        $friendRequest->is_accepted = true;
        $friendRequest->save();
        return $friendRequest;
    }

    public function ignoreFriendRequest(FriendRequest $friendRequest) {
        $friendRequest->is_ignored = true;
        $friendRequest->save();
        return $friendRequest;
    }
}
