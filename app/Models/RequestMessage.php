<?php

namespace App\Models\Letters;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class RequestMessage extends Model
{
    protected $table = "request_messages";

    protected $fillable = [
        "request_id",
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
