<?php

namespace App\Models\Letters;

use App\Models\PublicRelationRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestStatusTrack extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'request_status_tracks';

    protected $fillable = [
        'statusable_id',
        'statusable_type',
        'action',
        'notes',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = auth()->user()->name;
        });
    }

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }

    public function publicRelation()
    {
        return $this->belongsTo(PublicRelationRequest::class);
    }

    public function statusable()
    {
        return $this->morphTo();
    }

    public function scopeFilterByUser(Builder $query, $userName)
    {
        return $query->where('created_by', $userName);
    }

    public function scopeSortBy(Builder $query, $sortBy)
    {
        return $query->orderBy('created_at', $sortBy === 'latest' ? 'desc' : 'asc');
    }

    public function scopeWithDeletedRecords(Builder $query, $deletedOption)
    {
        return match ($deletedOption) {
            'withDeleted' => $query->withTrashed(),
            'onlyDeleted' => $query->onlyTrashed(),
            default => $query, // 'withoutDeleted'
        };
    }
}
