<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UploadVersion extends Model
{
    protected $table = 'document_upload_versions';

    protected $fillable = [
        'document_upload_version_id',
        'file_path',
        'version',
        'revision_note',
        'is_resolved'
    ];

    public function documentUpload()
    {
        return $this->hasOne(DocumentUpload::class, 'document_upload_version_id');
    }

    public function getUrl()
    {
        $disk = Storage::disk('public');
        $filePath = $disk->url($this->file_path);

        return $filePath;
    }
    
    public function markAsResolved()
    {
        $this->update([
            'is_resolved' => true,
        ]);
    }
}
