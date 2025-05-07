<?php

namespace App\Models\Letters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestStatusTrack extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'request_status_tracks';

    protected $fillable = [
        'letter_id',
        'action',
        'notes',
        'created_by'
    ];

    public function letter()
    {
        return $this->belongsTo(Letter::class);
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
