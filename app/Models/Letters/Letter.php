<?php

namespace App\Models\Letters;

use App\Enums\Division;
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
        'meeting' => 'array',
        'notes' => 'array'
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
        'meeting',
        'notes'
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
            'disposition' => \App\States\Disposition::class,
            'process' => \App\States\Process::class,
            'replied' => \App\States\Replied::class,
            'approved_kasatpel' => \App\States\ApprovedKasatpel::class,
            'replied_kapusdatin' => \App\States\RepliedKapusdatin::class,
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
        $details = $this->meeting; // Array
        if (isset($details['date']) && !empty($details['date'])) {
            return Carbon::parse($details['date'])->isoFormat('dddd, D MMMM YYYY');
        }
        return 'Invalid date';
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

    public function transitionStatusFromProcess($newStatus)
    {
        $this->transitionStatusFromString($newStatus);

        match ($newStatus) {
            'approved_kapusdatin' => $this->update(['active_checking' => $this->current_division]),
            'replied_kapusdatin' => $this->update([
                'active_revision' => true
            ]),
            'approved_kasatpel' => $this->update(['active_checking' => Division::HEAD_ID->value]),
            'replied' => $this->update([
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
        $this->update([
            'active_revision' => false,
            'need_review' => false,
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

    public static function getTotalRequestsByRole($rolesId = null)
    {
        $query = Letter::select('id');
        if ($rolesId !== null) {
            $query->whereIn('current_division', $rolesId);
        }

        return $query->count('id');
    }

    public function handleRedirectNotification($user)
    {
        if ($user->hasRole('user')) {
            return route('history.detail', [
                'type' => 'information-system',
                'id' => $this->id
            ]);
        }
        return route('is.show', ['id' => $this->id]);
    }

    public function hasNonZeroPartNumber()
    {
        return $this->documentUploads()
            ->whereHas('versions', function ($query) {
                $query->where('version', '!=', 0);
            })
            ->exists();
    }

    protected function getFormattedMeetingsAttribute()
    {
        $meetings = $this->meeting;

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


    public static function getNearMeetingsByDate()
    {
        $now = Carbon::now();
        $userId = auth()->id();

        // Ambil semua meeting dari user
        $allMeetings = self::where('user_id', $userId)
            ->get()
            ->pluck('meeting')->flatMap(function ($meeting) {
                return $meeting;
            })->filter();

        // Filter meeting dalam 3 hari ke depan
        $filteredMeetings = $allMeetings->filter(function ($meeting) use ($now) {
            if (!isset($meeting['date'])) return false;
            $meetingDate = Carbon::parse($meeting['date'])->startOfDay();
            $todayStart = $now->copy()->startOfDay();
            $twoDaysLater = $todayStart->copy()->addDays(2)->endOfDay();

            return $meetingDate->between($todayStart, $twoDaysLater);
        });

        // Group by tanggal
        $groupedMeetings = $filteredMeetings->groupBy('date')->map(function ($meetings, $date) {
            $dateCarbon = Carbon::parse($date)->locale('id');

            return [
                'date_number' => $dateCarbon->day,
                'date_day' => $dateCarbon->translatedFormat('l'),
                'date_month' => $dateCarbon->translatedFormat('F'),
                'is_today' => $dateCarbon->isToday(),
                'meetings' => collect($meetings)->map(function ($meeting) {
                    return [
                        'start' => $meeting['start'],
                        'end' => $meeting['end'],
                        'link_location' => [
                            'type' => isset($meeting['link']) ? 'link' : (isset($meeting['location']) ? 'location' : null),
                            'value' => $meeting['link'] ?? $meeting['location'] ?? null,
                        ],
                    ];
                })->sortBy('start')->values()->all(),
                'has_meetings' => $meetings->isNotEmpty(),
            ];
        });

        // Generate 3 hari terdekat (hari ini + 2 hari)
        $datesToCheck = [
            $now->copy()->format('Y-m-d'),
            $now->copy()->addDay()->format('Y-m-d'),
            $now->copy()->addDays(2)->format('Y-m-d'),
        ];

        // Merge dengan tanggal yang tidak memiliki meeting
        $result = collect($datesToCheck)->map(function ($date) use ($groupedMeetings) {
            $dateParse = Carbon::parse($date);
            return $groupedMeetings[$date] ?? [
                'date_number' => $dateParse->day,
                'date_day' => $dateParse->locale('id')->translatedFormat('l'),
                'date_month' => $dateParse->locale('id')->translatedFormat('F'),
                'is_today' => $dateParse->isToday(),
                'meetings' => [],
                'has_meetings' => false,
            ];
        })->values();

        return $result;
    }
}
