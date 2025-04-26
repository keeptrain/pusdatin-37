<?php

namespace App\Models\Letters;

use App\Models\letters\LettersMapping;
use Carbon\Carbon;
use App\Models\User;
use App\States\LetterStatus;
use Spatie\ModelStates\HasStates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Letter extends Model
{
    use HasFactory, HasStates, SoftDeletes;

    protected $table = "letters";

    protected $casts = [
        'status' => LetterStatus::class,
    ];

    public $fillable = [
        'user_id',
        'title',
        'responsible_person',
        'reference_number',
        'status',
        'current_revision',
        'active_revision'
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mapping()
    {
        return $this->hasMany(LettersMapping::class);
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

    public function transitionToStatus($newStatus, string $notes): void
    {
        $this->status->transitionTo($newStatus);

        $updatedState = $this->status;

        if (!method_exists($updatedState, 'message')) {
            return;
        }

        $isReplied = $this->isRepliedState($updatedState);

        if ($isReplied && $this->active_revision === true) {
            return;
        }

        if ($isReplied) {
            $this->update(['active_revision' => true]);
        } else {
            $this->update(['active_revision' => false]);
        }

        $this->requestStatusTrack()->create([
            'letter_id'    => $this->id,
            'action'       => $updatedState->message(),
            'notes'        => $this->shouldIncludeNotes($updatedState) ? $notes : null,
            'created_by'   => Auth::user()->name,
        ]);
    }

    private function isRepliedState($state): bool
    {
        return $state instanceof \App\States\Replied;
    }

    private function shouldIncludeNotes($state): bool
    {
        return $state instanceof \App\States\Approved;
    }

    public function getCategoryTypeNameAttribute()
    {
        $morphClass = $this->letterable_type;

        $parts = explode('\\', $morphClass);
        $className = end($parts);

        if (str_starts_with($className, 'Letter')) {
            return substr($className, 6);
        }

        return $className;
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
