<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'sender' => new UserResource($this->sender),
            'receiver' => new UserResource($this->receiver),
            'created_at' => $this->created_at,
            'user_sender_id' => $this->user_sender_id,
            'user_receiver_id' => $this->user_receiver_id,
            'read' => $this->read,
        ];
    }
}
