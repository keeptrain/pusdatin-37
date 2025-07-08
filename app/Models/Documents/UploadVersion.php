<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

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
        return $this->hasOne(DocumentUpload::class,'document_upload_version_id');
    }
}
