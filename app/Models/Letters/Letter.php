<?php

namespace App\Models\Letters;

use Carbon\Carbon;
use App\Models\User;
use App\States\LetterStatus;
use Spatie\ModelStates\HasStates;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Letter extends Model
{
    use HasStates, SoftDeletes;

    protected $table = "letters";

    protected $casts = [
        'status' => LetterStatus::class,
    ];

    public $fillable = [
        'user_id',
        'title',
        'letterable_type',
        'letterable_id',
        'status',
        'responsible_person',
        'reference_number'
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function letterable(): MorphTo
    {
        return $this->morphTo();
    }

    public function requestStatusTrack()
    {
        return $this->hasMany(RequestStatusTrack::class);
    }

    public static function getFilterableStates(): array
    {
        return [
            'pending'  => \App\States\Pending::class,
            'process'  => \App\States\Process::class,
            'approved' => \App\States\Approved::class,
            'replied' => \App\States\Replied::class,
            'rejected' => \App\States\Rejected::class,
        ];
    }

    public function transitionToStatus($newStatus): void
    {
        $this->status->transitionTo($newStatus);

        $updatedState = $this->status;

        if (method_exists($updatedState, 'message')) {
            $message = $updatedState->message();

            $this->requestStatusTrack()->create([
                'letter_id' => $this->id,
                'action' => $message
            ]);
        }
    }

    public function scopeFilterByStatus(Builder $query, ?string $filterStatus): Builder
    {
        $map = static::getFilterableStates();

        if ($filterStatus && isset($map[$filterStatus])) {
            return $query->where('status', $map[$filterStatus]);
        }

        return $query;
    }

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('F j, Y');
    }

    public static function queryForTable()
    {
        return Letter::select(['id', 'user_id', 'letterable_type', 'status',  'created_at'])
            ->with('user:id,name')
        ;
    }
}
