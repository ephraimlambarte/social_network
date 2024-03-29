<?php

namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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

    public function getPeopleToAdd($query) {
        $searchInput = $query['search_input'];

        return User::leftJoin('friend_requests as sent_fr', function ($join) {
            $join->on('users.id', '=', 'sent_fr.user_sender_id')
            ->where('sent_fr.user_receiver_id', auth()->user()->id);
        })
        ->leftJoin('friend_requests as received_fr', function ($join) {
            $join->on('users.id', '=', 'received_fr.user_receiver_id')
            ->where('received_fr.user_sender_id', auth()->user()->id);
        })
        ->where(function ($query) {
            $query->whereNotIn('users.id', function ($query){
                $query
                ->select('user_id as id')
                ->from('friends')
                ->where('user_friend_id', auth()->user()->id)
                ->union(DB::table('friends')->select('user_friend_id as id')->where('user_id', auth()->user()->id));
            })
            ->where('users.id', '<>', auth()->user()->id);
        })
        ->where(function ($query) use ($searchInput) {
            $query->where('users.name', 'LIKE', "%$searchInput%")
            ->orWhere('users.email', 'LIKE', "%$searchInput%");
        })
        ->select(
            'users.name',
            'users.id',
            'users.email',
            'sent_fr.id as sent_friend_request',
            'received_fr.id as received_friend_request',
        )
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
