<?php

namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MessageService extends BaseModelService
{
    public function model() : Model {
       return new Message;
    }

    public function getMessagesOfTwoUser(User $user1, User $user2) {
        return $this->messageOf2UserQuery($user1, $user2)
        ->paginate(10);
    }

    public function messageOf2UserQuery(User $user1, User $user2) {
        return $this->model()
        ->where(function ($query) use ($user1, $user2) {
            $query->where('user_sender_id', $user1->id)
            ->where('user_receiver_id', $user2->id);
        })
        ->orWhere(function ($query) use ($user1, $user2) {
            $query->where('user_sender_id', $user2->id)
            ->where('user_receiver_id', $user1->id);
        })
        ->orderBy('id', 'desc');
        // ->orWhere('user_receiver_id', $user1->id)
        // ->orWhere('user_sender_id', $user2->id)
        // ->orWhere('user_receiver_id', $user2->id)
        
    }

    public function getUserInbox(User $user) {
        $res = DB::query()
        ->select('other_user_id', DB::raw('MAX(max_id) as max_id'))
        ->fromSub(
            DB::table('messages')
            ->select(
                DB::raw('user_sender_id as other_user_id'),
                DB::raw('MAX(id) as max_id'),
            )
            ->where('user_receiver_id', $user->id)
            ->groupBy('user_sender_id')
            ->union(
                DB::table('messages')
                ->select(
                    DB::raw('user_receiver_id as other_user_id'),
                    DB::raw('MAX(id) as max_id'),
                )
                ->where('user_sender_id', $user->id)
                ->groupBy('user_receiver_id')
            ),
            'sub'
        )
        ->groupBy('other_user_id');
        
        return $this->model()
        ->select(
            'm.user_sender_id',
            'm.user_receiver_id',
            'm.message',
            'm.created_at',
            'm.updated_at',
            'm.id'
        )
        ->from('messages as m')
        ->orderBy('m.id', 'desc')
        ->joinSub(
            $res,
            'm2',
            function ($join) {
                $join->on('m.id', '=', 'm2.max_id');
            }
        )
        ->get();
    }

    public function readMessages(array $messages) {
        foreach ($messages as $key => $message) {
            $message = Message::find($message)->first();
            $message->read = true;
            $message->save();
        }
        return Message::whereIn('id', $messages)->get();
    }
}
