<?php

namespace App\Models;

use App\Enums\PublicRelationRequestPart;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Trait\HasActivities;
use IntlDateFormatter;
use Spatie\ModelStates\HasStates;
use Illuminate\Database\Eloquent\Model;
use App\Models\Documents\DocumentUpload;
use App\States\PublicRelation\Completed;
use Illuminate\Database\Eloquent\Builder;
use App\States\PublicRelation\PromkesQueue;
use App\States\PublicRelation\PusdatinQueue;
use App\States\PublicRelation\PromkesComplete;
use App\States\PublicRelation\PusdatinProcess;
use App\States\PublicRelation\PublicRelationStatus;

class PublicRelationRequest extends Model
{
    use HasActivities, HasStates;

    protected $table = "public_relation_requests";

    protected $casts = [
        'status' => PublicRelationStatus::class,
        'links' => 'array'
    ];

    protected $fillable = [
        'user_id',
        'month_publication',
        'completed_date',
        'spesific_date',
        'theme',
        'target',
        'links',
        'active_checking'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documentUploads()
    {
        return $this->morphMany(DocumentUpload::class, 'documentable');
    }

    public static function resolveStatusClassFromString($statusString)
    {
        return match ($statusString) {
            'all' => 'All',
            'antrian_promkes' => PromkesQueue::class,
            'kurasi_promkes' => PromkesComplete::class,
            'antrian_pusdatin' => PusdatinQueue::class,
            'proses_pusdatin' => PusdatinProcess::class,
            'completed' => Completed::class,
        };
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d F Y');
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
        return Str::headline($value);
    }

    public function getMonthPublicationAttribute($value)
    {
        $formatter = new IntlDateFormatter(
            'id_ID', // Locale Indonesia
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            null,
            null,
            'MMMM' // Format untuk nama bulan penuh
        );

        // Konversi angka bulan menjadi nama bulan dalam bahasa Indonesia
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

    public function transitionStatusToPromkesComplete()
    {
        $this->update([
            'status' => $this->status->transitionTo(PromkesComplete::class),
            'active_checking' => 2
        ]);
    }

    public function transitionStatusToPusdatinQueue()
    {
        $this->update(['status' => $this->status->transitionTo(PusdatinQueue::class)]);
    }

    public function transitionStatusToPusdatinProcess()
    {
        $this->update([
            'status' => $this->status->transitionTo(PusdatinProcess::class),
            'active_checking' => 5
        ]);
    }

    public function transitionStatusToCompleted($links)
    {
        $this->update([
            'status' => $this->status->transitionTo(Completed::class),
            'links' => $links
        ]);
    }

    public function handleRedirectNotification($user)
    {
        if ($user->can('queue pr pusdatin') && $this->status instanceof PromkesComplete) {
            $this->transitionStatusToPusdatinQueue();
            $this->logStatus(null);
        }

        if ($user->hasRole('user')) {
            return route('history.detail', [
                'type' => 'public-relation',
                'id' => $this->id
            ]);
        }
        return route('pr.show', ['id' => $this->id]);
    }

    public function getExportLinksAttribute($needHyperLink = true)
    {
        $links = $this->links;

        // Pastikan $links adalah array valid
        $linksArray = is_string($links) ? json_decode($links, true) : $links;

        $separator = $needHyperLink ? "<br><br>" : "\n";

        // Format setiap isi array links
        return collect($linksArray)->map(function ($link, $index) use ($needHyperLink) {
            $mediaLabel = PublicRelationRequestPart::tryFrom($index)?->label() ?? 'Media Tidak Dikenal';
            $mediaHyperLink = "<a href=\"$link\" target=\"_blank\">$link</a>";

            if (!$needHyperLink) {
                return "Media $mediaLabel: $link";
            }

            // Label media dan link sebagai hyperlink aktif
            return "Media $mediaLabel: $mediaHyperLink";
        })->implode($separator);
    }
}
