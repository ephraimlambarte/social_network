<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInbox extends Model
{
    use HasFactory;

    public function sender() {
        return $this->belongsTo(User::class, 'user_sender_id');
    }
    public function receiver() {
        return $this->belongsTo(User::class, 'user_receiver_id');
    }
}
