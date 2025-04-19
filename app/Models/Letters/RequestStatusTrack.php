<?php

namespace App\Models\Letters;

use Illuminate\Database\Eloquent\Model;

class RequestStatusTrack extends Model
{

    protected $table = 'request_status_tracks';

    protected $fillable = [
        'letter_id',
        'action',
    ];

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }
}
