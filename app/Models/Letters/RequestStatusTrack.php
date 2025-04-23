<?php

namespace App\Models\Letters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestStatusTrack extends Model
{
    use HasFactory;
    
    protected $table = 'request_status_tracks';

    protected $fillable = [
        'letter_id',
        'action',
        'notes',
        'created_by'
    ];

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }
}
