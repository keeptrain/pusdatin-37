<?php

namespace App\Models;

use Carbon\Carbon;
use IntlDateFormatter;
use App\Trait\HasActivities;
use Spatie\ModelStates\HasStates;
use App\Enums\PublicRelationRequestPart;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Documents\DocumentUpload;
use App\States\PublicRelation\Completed;
use Illuminate\Database\Eloquent\Builder;
use App\States\PublicRelation\Pending;
use App\States\PublicRelation\PromkesQueue;
use App\States\PublicRelation\PusdatinQueue;
use App\States\PublicRelation\PromkesComplete;
use App\States\PublicRelation\PusdatinProcess;
use App\States\PublicRelation\PublicRelationStatus;

class PublicRelationRequest extends Model
{
    use HasActivities, HasStates, SoftDeletes;

    protected $table = "public_relation_requests";

    protected $casts = [
        'status' => PublicRelationStatus::class,
        'target' => 'array',
        'links' => 'array',
        'rating' => 'json'
    ];

    protected $fillable = [
        'user_id',
        'month_publication',
        'completed_date',
        'spesific_date',
        'theme',
        'target',
        'links',
        'active_checking',
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

    public static function resolveStatusClassFromString(string $status): string
    {
        return match ($status) {
            'all' => 'All',
            'permohonan_masuk' => Pending::class,
            'antrian_promkes' => PromkesQueue::class,
            'kurasi_promkes' => PromkesComplete::class,
            'antrian_pusdatin' => PusdatinQueue::class,
            'proses_pusdatin' => PusdatinProcess::class,
            'completed' => Completed::class,
            default => 'All',
        };
    }

    public static function resolveStatusClassFromArray(array $statuses): array
    {
        return array_map(fn($status) => static::resolveStatusClassFromString($status), $statuses);
    }

    public function resolveLinkLabel($key)
    {
        return PublicRelationRequestPart::tryFrom($key)->label() ?? 'Link Media Tidak Dikenal';
    }

    public function getCompletedDateAttribute($value)
    {
        return Carbon::parse($value)->format('d F Y');
    }

    public function getSpesificDateAttribute($value)
    {
        return Carbon::parse($value)->format('d F Y');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d F Y, H:i');
    }

    public function publicationPlan()
    {
        return Carbon::parse($this->spesific_date)->format('F');
    }

    public function proposedMonth()
    {
        return Carbon::parse($this->create_at)->format('F');
    }

    public function getTargetAttribute($value)
    {
        $arrayData = json_decode($value, true) ?? []; // 'true' for array associative, ?? [] for fallback

        return array_map(
            fn($item) => is_string($item) ? ucwords(str_replace('_', ' ', $item)) : $item,
            $arrayData
        );
    }

    public function getMonthPublicationAttribute($value)
    {
        $formatter = new IntlDateFormatter(
            'id_ID', // Locale Indonesia
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            null,
            null,
            'MMMM' // Format for full month name
        );

        // Convert month number to full month name in Indonesian
        return $formatter->format(mktime(0, 0, 0, $value, 1)) ?? 'Bulan Tidak Diketahui';
    }

    public function createdAtDMY()
    {
        return Carbon::parse($this->created_at)->format('d F Y');
    }

    public function spesificDate()
    {
        return Carbon::parse($this->spesific_Date)->format('d F');
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

    public function transitionStatusToPromkesComplete()
    {
        $this->update([
            'status' => $this->status->transitionTo(PromkesComplete::class),
            'active_checking' => 2
        ]);
    }

    public function transitionStatusToPusdatinQueue()
    {
        $this->update([
            'status' => $this->status->transitionTo(PusdatinQueue::class),
            'active_checking' => 5
        ]);
    }

    public function transitionStatusToPusdatinProcess()
    {
        $this->update([
            'status' => $this->status->transitionTo(PusdatinProcess::class),
        ]);
    }

    public function transitionStatusToCompleted($links)
    {
        $this->update([
            'status' => $this->status->transitionTo(Completed::class),
            'links' => $links
        ]);
    }

    public function handleRedirectNotification($user, $status)
    {
        if ($user->roles->pluck('name')->contains('user')) {
            return route('detail.request', [
                'type' => 'public-relation',
                'id' => $this->id
            ]);
        }
        return route('pr.show', ['id' => $this->id]);
    }

    public function getExportLinksAttribute($needHyperLink = true)
    {
        $links = $this->links;

        //$links valid array
        $linksArray = is_string($links) ? json_decode($links, true) : $links;

        $separator = $needHyperLink ? "<br><br>" : "\n";

        // Format every element in array
        return collect($linksArray)->map(function ($link, $index) use ($needHyperLink) {
            $mediaLabel = PublicRelationRequestPart::tryFrom($index)?->label() ?? 'Media Tidak Dikenal';
            $mediaHyperLink = "<a href=\"$link\" target=\"_blank\">$link</a>";

            if (!$needHyperLink) {
                return "Media $mediaLabel: $link";
            }

            return "Media $mediaLabel: $mediaHyperLink";
        })->implode($separator);
    }
}
