<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrackingHistorie extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tracking_historie';

    protected $fillable = [
        'requestable_id',
        'requestable_type',
        'action',
        'notes',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->user()->name;
            }
        });
    }

    public function requestable()
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
