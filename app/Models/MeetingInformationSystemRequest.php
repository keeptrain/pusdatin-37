<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MeetingInformationSystemRequest extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    public $table = "meeting_information_system_requests";

    public $fillable = [
        'id',
        'request_id',
        'topic',
        'place',
        'start_at',
        'end_at',
        'recipients',
        'result'
    ];

    public $timestamps = false;

    public function informationSystemRequest()
    {
        return $this->belongsTo(InformationSystemRequest::class, 'request_id', 'id');
    }

    public function getPlaceAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setPlaceAttribute($value)
    {
        $this->attributes['place'] = json_encode($value);
    }

    public function getRecipientsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setRecipientsAttribute($value)
    {
        $this->attributes['recipients'] = json_encode($value);
    }

    public function getDateAttribute($value)
    {
        return Carbon::parse($this->start_at)->format('d M Y');
    }

    public function getStartAtTimeAttribute($value)
    {
        return Carbon::parse($this->start_at)->format('H:i');
    }

    public function getEndAtTimeAttribute($value)
    {
        return Carbon::parse($this->end_at)->format('H:i');
    }
}
