<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Enums\Division;

class Discussion extends Model
{
    protected $table = 'discussions';

    protected $fillable = [
        'discussable_id',
        'discussable_type',
        'body',
        'user_id',
        'parent_id',
    ];

    protected $softDelete = true;

    public function discussable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Discussion::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Discussion::class, 'parent_id')
            ->with('user')
            ->orderBy('created_at');
    }

    public function getFirstCreatedAtAttribute()
    {
        return Carbon::parse($this->created_at)->format('d M Y H:i');
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeReply($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function getDiscussableContextAttribute()
    {
        return match ($this->discussable_type) {
            InformationSystemRequest::class => 'Sistem Informasi dan Data - ' . $this->discussable->title,
            PublicRelationRequest::class => 'Kehumasan - ' . $this->discussable->theme,
            default => 'Tidak terkait permohonan - Kasatpel ' . Division::tryFrom($this->discussable_id)->label(),
        };
    }
}