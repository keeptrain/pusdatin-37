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

    public static function resolveStatusClassFromString(): array
    {
        return [
            'pending'  => \App\States\Pending::class,
            'process'  => \App\States\Process::class,
            'approved' => \App\States\Approved::class,
            'replied' => \App\States\Replied::class,
            'rejected' => \App\States\Rejected::class
        ];
    }

    /**
     * Get the created_at attribute in readable format.
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }

    public function createdAtDMY()
    {
        return Carbon::parse($this->created_at)->format('d F Y');
    }

    public function createdAtWithTime()
    {
        return Carbon::parse($this->created_at)->format('d F Y, H:i');
    }

    /**
     * Get the updated_at attribute in readable format.
     *
     * @param  string  $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d F Y, H:i');
    }

    public function transitionStatusOnly($newStatus)
    {
        $states = self::resolveStatusClassFromString();

        $currentStatusName = $this->status->getValue();

        if ($newStatus !== $currentStatusName) {
            if (isset($states[$newStatus])) {
                $this->status->transitionTo($states[$newStatus]);
            }
        }
    }

    public function transitionToStatus($newStatus, ?string $notes): void
    {
        $this->status->transitionTo($newStatus);

        $updatedState = $this->status;

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
            'action'       => $updatedState->trackingMessage(),
            'notes'        => $notes,
            'created_by'   => Auth::user()->name,
        ]);
    }

    private function isRepliedState($state): bool
    {
        return $state instanceof \App\States\Replied;
    }

    public function scopeFilterByStatus(Builder $query, ?string $filterStatus): Builder
    {
        $map = static::resolveStatusClassFromString();

        if ($filterStatus && isset($map[$filterStatus])) {
            return $query->where('status', $map[$filterStatus]);
        }

        return $query;
    }

}