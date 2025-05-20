<?php

namespace App\Models;

use Carbon\Carbon;
use Spatie\ModelStates\HasStates;
use Illuminate\Database\Eloquent\Model;
use App\Models\Documents\DocumentUpload;
use App\States\PublicRelation\Completed;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Letters\RequestStatusTrack;
use App\States\PublicRelation\PromkesQueue;
use App\States\PublicRelation\PusdatinQueue;
use App\States\PublicRelation\PromkesComplete;
use App\States\PublicRelation\PusdatinProcess;
use App\States\PublicRelation\PublicRelationStatus;

class PublicRelationRequest extends Model
{
    use HasStates;

    protected $table = "public_relation_requests";

    protected $casts = [
        'status' => PublicRelationStatus::class,
        'links' => 'array'
    ];

    protected $fillable = [
        'user_id',
        'month_publication',
        'spesific_date',
        'theme',
        'target',
        'active_review',
        'links',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documentUploads()
    {
        return $this->morphMany(DocumentUpload::class, 'documentable');
    }

    public function requestStatusTrack()
    {
        return $this->morphMany(RequestStatusTrack::class, 'statusable');
    }

    private static function resolveStatusClassFromString($statusString)
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

    public function getPublicationMonthAttribute($value)
    {
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $monthNames[$value] ?? 'Bulan Tidak Diketahui';
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
}
