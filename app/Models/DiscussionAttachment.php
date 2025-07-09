<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
