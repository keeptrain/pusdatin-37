<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Enums\Division;
use App\States\InformationSystem\ApprovedKapusdatin;
use App\States\InformationSystem\ApprovedKasatpel;
use App\States\InformationSystem\Completed;
use App\States\InformationSystem\Disposition;
use App\States\InformationSystem\Pending;
use App\States\InformationSystem\Process;
use App\States\InformationSystem\Rejected;
use App\States\InformationSystem\Replied;
use Spatie\ModelStates\HasStates;
use Illuminate\Database\Eloquent\Model;
use App\Models\Documents\DocumentUpload;
use App\Trait\HasActivities;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\States\InformationSystem\InformationSystemStatus;
use App\States\InformationSystem\RepliedKapusdatin;
use Illuminate\Support\Collection;

class InformationSystemRequest extends Model
{
    use HasActivities, HasFactory, HasStates, SoftDeletes;

    protected $table = "information_system_requests";

    protected $casts = [
        'status' => InformationSystemStatus::class,
        'meetings' => 'json',
        'notes' => 'json',
        'rating' => 'json'
    ];

    public $fillable = [
        'user_id',
        'title',
        'reference_number',
        'status',
        'active_checking',
        'current_division',
        'active_revision',
        'need_review',
        'meetings',
        'notes',
        'rating'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documentUploads()
    {
        return $this->morphMany(DocumentUpload::class, 'documentable');
    }

    public function meetings()
    {
        return $this->hasMany(MeetingInformationSystemRequest::class, 'request_id', 'id');
    }

    public static function resolveStatusClassFromString($statusString)
    {
        return match ($statusString) {
            'all' => 'All',
            'pending' => Pending::class,
            'disposition' => Disposition::class,
            'replied' => Replied::class,
            'approved_kasatpel' => ApprovedKasatpel::class,
            'replied_kapusdatin' => RepliedKapusdatin::class,
            'approved_kapusdatin' => ApprovedKapusdatin::class,
            'process_request' => Process::class,
            'completed' => Completed::class,
            'rejected' => Rejected::class
        };
    }

    public static function resolveStatusClassFromArray(array $statuses): array
    {
        return array_map(fn($status) => static::resolveStatusClassFromString($status), $statuses);
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
        return Division::tryFrom($value)?->label() ?? 'Perlu disposisi';
    }

    public function getDivisionLabelAttribute(): string
    {
        return Division::tryFrom($this->current_division)?->label() ?? 'Perlu disposisi';
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

    public function getFormattedMeetingDate(): string
    {
        $details = $this->meetings; // Array
        if (isset($details['date']) && !empty($details['date'])) {
            return Carbon::parse($details['date'])->isoFormat('dddd, D MMMM YYYY');
        }
        return 'Invalid date';
    }

    public function scopeFilterCurrentDivisionByCurrentUser($query, $user)
    {
        // Periksa apakah pengguna memiliki role 'head_verifier'
        $isHeadVerifier = auth()->user()->roles->pluck('name')->contains('head_verifier');

        return $query->when(
            !$isHeadVerifier, // Kondisi: jika bukan head_verifier
            function ($query) use ($user) {
                // Filter berdasarkan current_division
                $query->where('current_division', $user);
            }
        );
    }

    private function transitionStatusFromString($newStatus)
    {
        $newStatus = self::resolveStatusClassFromString($newStatus);

        $this->status->transitionTo($newStatus);
    }

    public function transitionStatusFromPending($newStatus, $division, $notes)
    {
        $this->transitionStatusFromString($newStatus);

        $newNotes = [
            $notes
        ];

        if ($division) {
            $this->update([
                'active_checking' => $division,
                'current_division' => $division,
                'notes' => $newNotes
            ]);
        }
    }

    public function allowedParts()
    {
        return $this->documentUploads->filter(function ($documentUpload) {
            return $documentUpload->part_number !== 0;
        })
            ->map(function ($upload) {
                return [
                    'part_number' => $upload->part_number,
                    'part_number_label' => $upload->part_number_label,
                ];
            })
            ->values()
            ->toArray();
    }

    public function transitionStatusFromDisposition($newStatus)
    {
        $this->transitionStatusFromString($newStatus);

        match ($newStatus) {
            'approved_kasatpel' => $this->update(['active_checking' => Division::HEAD_ID->value]),
            'replied' => $this->update([
                'active_revision' => true
            ]),
            'approved_kapusdatin' => $this->update(['active_checking' => $this->current_division]),
            'replied_kapusdatin' => $this->update([
                'active_revision' => true
            ]),
            'rejected' => $this->update([
                'active_revision' => false,
                'need_review' => false,
            ])
        };
    }

    public function transitionStatusFromApprovedKapusdatin($newStatus)
    {
        $this->transitionStatusFromString($newStatus);

        $this->update([
            'active_checking' => $this->current_division,
        ]);
    }

    public function updatedForNeedReview()
    {
        $this->update([
            'active_revision' => false,
            'need_review' => true,
        ]);
    }

    public function updatedForCompletedReview()
    {
        $this->status->transitionTo(Disposition::class);
        $this->update([
            'active_revision' => false,
            'need_review' => false
        ]);
    }

    public function scopeFilterByStatus(Builder $query, ?string $filterStatus): Builder
    {
        $resolveStatus = static::resolveStatusClassFromString($filterStatus);

        if (isset($resolveStatus)) {
            return $query->where('status', $resolveStatus);
        }

        return $query;
    }

    public function scopeFilterByStatuses(Builder $query, ?array $filterStatuses): Builder
    {
        $resolveStatuses = static::resolveStatusClassFromArray($filterStatuses);
        if (empty($filterStatuses)) {
            return $query;
        }

        return $query->whereIn('status', $resolveStatuses);
    }

    public static function getTotalRequestsByRole($rolesId = null)
    {
        $query = self::select('id');
        if ($rolesId !== null) {
            $query->whereIn('current_division', $rolesId);
        }

        return $query->count('id');
    }

    public function handleRedirectNotification($user, $status)
    {
        if (!$user->hasRole('user')) {
            return route('is.show', ['id' => $this->id]);
        }

        return match ($status) {
            'Revisi Kasatpel' => route('is.edit', ['id' => $this->id]),
            default => route('detail.request', [
                'type' => 'information-system',
                'id' => $this->id
            ]),
        };
    }

    public function hasNonZeroPartNumber()
    {
        return $this->documentUploads->reject(fn($file) => $file->part_number !== 0);
    }

    protected function getFormattedMeetingsAttribute()
    {
        $meetings = $this->meetings;

        // $meetings adalah array valid
        $meetingsArray = is_string($meetings) ? json_decode($meetings, true) : $meetings;

        // Format setiap meeting menjadi blok teks terstruktur
        return collect($meetingsArray)->map(function ($meeting, $index) {
            $details = [];
            $details[] = "Meeting " . ($index + 1) . ":";
            if (isset($meeting['date'])) {
                $details[] = "- Tanggal: {$meeting['date']}";
            }
            if (isset($meeting['start']) && isset($meeting['end'])) {
                $details[] = "- Waktu: {$meeting['start']} - {$meeting['end']}";
            }
            if (isset($meeting['location'])) {
                $details[] = "- Lokasi: {$meeting['location']}";
            }
            if (isset($meeting['link'])) {
                $details[] = "- Online Meet";
            }
            if (isset($meeting['result'])) {
                $details[] = "- Hasil: {$meeting['result']}";
            }

            return implode("\n", $details); // Newline untuk memisahkan setiap detail
        })->implode("\n\n"); // Dua newline untuk memisahkan setiap meeting
    }

    public function getNearestMeetingFromCollection(Collection $meetings)
    {
        return $meetings->filter(fn($meeting) => $meeting->start_at <= Carbon::now())->sortBy('start_at')->first();
    }
}
