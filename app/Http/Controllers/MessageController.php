<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\MessageService;
use App\Services\UserService;
use App\Events\MessageSentEvent;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function __construct(
        private MessageService $service,
        private UserService $userService
    ) {

    }

    public function sendMessage(Request $request, User $user) {
        $request->validate([
            'message' => 'required',
        ]);

        if (!$this->userService->isFriends($user, auth()->user())) {
            return response()->json([
                'errors' => [
                        'cannot send a message to a person that is not your friend!',
                    ]
                ],
                422
            );
        }
        $message = $this->service->store([
            'message' => $request->message,
            'user_receiver_id' => $user->id,
            'user_sender_id' => auth()->user()->id,
        ]);
        MessageSentEvent::dispatch($message);

        return response()->json(
            $message,
            200
        );
    }

    public function getFriendMessagesPage(User $user) {
        return Inertia::render(
            'Messages',
            [
                'user' => $user,
            ]
        );
    }

    public function getMessages(Request $request, User $user) {
        return $this->service->getMessagesOfTwoUser(auth()->user(), $user);
    }

    public function getUserInbox(Request $request) {
        return $this->service->getUserInbox(auth()->user());
    }

    public function readMessages(Request $request) {
        $request->validate([
            'messages' => 'array|required',
            'messages.*' => 'exists:messages,id'
        ]);

        return $this->service->readMessages($request->messages);
    }
}
