<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Enums\Division;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

class Discussion extends Model
{
    protected $table = 'discussions';

    protected $fillable = [
        'discussable_id',
        'discussable_type',
        'body',
        'user_id',
        'parent_id',
        'closed_at'
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

    public function attachments()
    {
        return $this->hasMany(DiscussionAttachment::class);
    }

    public function repliesWithAttachments()
    {
        return $this->hasMany(Discussion::class, 'parent_id')
            ->with('attachments');
    }

    public function getFirstCreatedAtAttribute()
    {
        return Carbon::parse($this->created_at)->format('d M Y H:i');
    }

    public function getParentClosedAtAttribute()
    {
        return Carbon::parse($this->closed_at)->format('d M Y H:i');
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeReply($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeStatus(Builder $query, string $status)
    {
        return match ($status) {
            'closed' => $query->whereNotNull('closed_at'),
            'open' => $query->whereNull('closed_at'),
            default => $query
        };
    }

    public function scopeWithDiscussableDetails(Builder $query)
    {
        return $query->with([
            'discussable' => function ($query) {
                $query->when(
                    $query->getModel() instanceof InformationSystemRequest,
                    fn($q) => $q->select('id', 'title')
                )->when(
                        $query->getModel() instanceof PublicRelationRequest,
                        fn($q) => $q->select('id', 'theme')
                    );
            }
        ]);
    }

    public function scopeForUserRole(Builder $query, int $roleId)
    {
        $query->where(function ($q) use ($roleId) {
            // User can always see their own discussions
            $q->where('user_id', auth()->user()->id)
                ->orWhereRoleDiscussion($roleId);
        });
    }

    public function scopeForInformationSystemDivision(Builder $query, $roleId)
    {
        $query->where(function ($q) use ($roleId) {
            $q->whereHasMorph(
                'discussable',
                [InformationSystemRequest::class],
                fn($sub) => $sub->where('current_division', $roleId)
            )->orWhereRoleDiscussion($roleId);
        });
    }

    public function scopeForPublicRelationDivision(Builder $query, $roleId)
    {
        $query->where(function ($q) use ($roleId) {
            $q->whereHasMorph('discussable', [PublicRelationRequest::class])
                ->orWhereRoleDiscussion($roleId);
        });
    }

    public function scopeOrWhereRoleDiscussion(Builder $query, $roleId)
    {
        $query->orWhere(function ($q) use ($roleId) {
            $q->where('discussable_type', Role::class)
                ->where(function ($subQ) use ($roleId) {
                    $subQ->where('discussable_id', $roleId)
                        // Add PROMKES role if the current role is PROMKES
                        ->when($roleId === Division::PROMKES_ID->value, function ($q) {
                            $q->orWhere('discussable_id', Division::PR_ID->value);
                        });
                });
        });
    }

    public function scopeWithAttachmentCounts(Builder $query)
    {
        return $query->withCount('attachments')
            ->with(['replies' => fn($q) => $q->latest()->withCount('attachments')]);
    }

    public function scopeApplySort($query, $sortType)
    {
        return match ($sortType) {
            'Diskusi terbaru' => $query->withMax('replies as latest_reply_date', 'created_at')
                ->orderByDesc('latest_reply_date')
                ->orderByDesc('created_at'),
            'Update terbaru' => $query->orderByDesc('created_at'),
            default => $query->orderByDesc('created_at')
        };
    }

    public function scopeApplySearch($query, $searchTerm)
    {
        return $query->when(
            $searchTerm,
            fn($q) =>
            $q->where('body', 'like', '%' . $searchTerm . '%')
        );
    }

    public function getDiscussableContextAttribute()
    {
        return match ($this->discussable_type) {
            InformationSystemRequest::class => 'Sistem Informasi dan Data - ' . $this->discussable->title,
            PublicRelationRequest::class => 'Kehumasan - ' . $this->discussable->theme,
            default => 'Tidak terkait permohonan - Kasatpel ' . Division::tryFrom($this->discussable_id)->label(),
        };
    }

    public function close(): void
    {
        $this->update(['closed_at' => now()]);
    }

    public function reopen(): void
    {
        $this->update(['closed_at' => null]);
    }
}