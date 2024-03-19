<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    use HasFactory;

    public function receiver() {
        return $this->belongsTo(User::class, 'user_receiver_id');
    }

    public function sender() {
        return $this->belongsTo(User::class, 'user_sender_id');
    }
}
