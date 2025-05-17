<?php

namespace App\Models\Documents;

use App\Models\Letters\LetterUpload;
use Illuminate\Database\Eloquent\Model;

class UploadVersion extends Model
{
    protected $table = 'document_upload_versions';

    protected $fillable = [
        'document_upload_id',
        // 'part_number',
        'file_path',
        'version',
        'revision_note',
        'is_resolved'
    ];

    public function letterUpload()
    {
        return $this->belongsTo(LetterUpload::class);
    }

    public function documentUpload()
    {
        return $this->hasOne(LetterUpload::class,'document_upload_version_id');
    }
}
