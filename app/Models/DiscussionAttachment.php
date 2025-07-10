<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DiscussionAttachment extends Model
{
    protected $table = "discussion_attachments";

    protected $fillable = [
        'discussion_id',
        'user_id',
        'disk',
        'path',
        'original_filename',
        'mime_type',
    ];

    public function attachments()
    {
        $this->belongsTo(Discussion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUrl(): string
    {
        $disk = Storage::disk($this->disk);
        $path = $disk->path($this->path);
        return $path;
    }

    public function isImage(): bool
    {
        return Str::startsWith($this->mime_type, 'image/');
    }
}
