<?php

namespace App\Models\Letters;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LetterMessage extends Model
{
    protected $table = "letter_messages";

    protected $fillable = [
        "letter_id",
        "sender_id",
        "receiver_id",
        "body",
    ];

    public $timestamps = true;

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
