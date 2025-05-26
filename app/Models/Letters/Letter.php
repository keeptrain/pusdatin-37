<?php

namespace App\Models\Letters;

use Carbon\Carbon;
use App\Models\User;
use App\States\LetterStatus;
use Spatie\ModelStates\HasStates;
use App\Models\letters\LettersMapping;
use Illuminate\Database\Eloquent\Model;
use App\Models\Documents\DocumentUpload;
use App\Trait\HasActivities;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Letter extends Model
{
    use HasActivities, HasFactory, HasStates, SoftDeletes;

    protected $table = "letters";

    protected $casts = [
        'status' => LetterStatus::class,
    ];

    public $fillable = [
        'user_id',
        'title',
        'reference_number',
        'status',
        'active_checking',
        'current_division',
        'active_revision',
        'need_review'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documentUploads()
    {
        return $this->morphMany(DocumentUpload::class, 'documentable');
    }

    public function mapping()
    {
        return $this->morphMany(LettersMapping::class, 'letterable');
    }

    public static function resolveStatusClassFromString($statusString)
    {
        return match ($statusString) {
            'disposition'  => \App\States\Disposition::class,
            'process'  => \App\States\Process::class,
            'replied' => \App\States\Replied::class,
            'approved_kasatpel' => \App\States\ApprovedKasatpel::class,
            'approved_kapusdatin' => \App\States\ApprovedKapusdatin::class,
            'rejected' => \App\States\Rejected::class
        };
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

    public function kasatpelName($value)
    {
        return match ($value) {
            3 => 'Sistem Informasi',
            4 => 'Pengelolaan Data',
            5 => 'Hubungan Masyarakat',
            default => 'Perlu disposisi', // Default case for other numbers
        };
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

    public function scopeFilterByCurrentUser($query)
    {
        $user = auth()->user();

        // Periksa apakah pengguna memiliki role 'head_verifier'
        $isHeadVerifier = $user->roles()->where('name', 'head_verifier')->exists();

        return $query->when(
            !$isHeadVerifier, // Kondisi: jika bukan head_verifier
            function ($query) use ($user) {
                // Filter berdasarkan current_division
                $query->where('current_division', $user->roles()->pluck('id'));
            }
        );
    }

    public function transitionStatusToProcess($division)
    {
        $this->status->transitionTo(\App\States\Process::class);
    }

    public function transitionStatusFromPending($newStatus, $division)
    {
        $newStatus = self::resolveStatusClassFromString($newStatus);

        $this->status->transitionTo($newStatus);

        if ($division) {
            $this->update([
                'active_checking' => $division,
                'current_division' => $division,
            ]);
        }
    }

    public function transitionStatusFromProcess($newStatus)
    {
        $resolveNewStatus = self::resolveStatusClassFromString($newStatus);

        $this->status->transitionTo($resolveNewStatus);

        match ($newStatus) {
            'approved_kapusdatin' => [],
            'approved_kasatpel' => $this->update(['active_checking' => 2]),
            'replied' => $this->update([
                'active_revision' => true
            ]),
            'rejected' => $this->update([
                'active_revision' => false,
                'need_review' => false,
            ])
        };
    }

    public function scopeFilterByStatus(Builder $query, ?string $filterStatus): Builder
    {
        $resolveStatus = static::resolveStatusClassFromString($filterStatus);

        if (isset($resolveStatus)) {
            return $query->where('status', $resolveStatus);
        }

        return $query;
    }
}
