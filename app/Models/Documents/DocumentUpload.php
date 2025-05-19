<?php

namespace App\Models\Documents;

use App\Models\letters\LettersMapping;
use Illuminate\Database\Eloquent\Model;
use App\Models\Documents\UploadVersion;
use App\Models\PublicRelationRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentUpload extends Model
{
    use HasFactory;

    protected $table = 'document_uploads';

    public $fillable = [
        'document_upload_version_id',
        'documentable_id',
        'documentable_type',
        'part_number',
        'need_revision',
    ];

    protected $appends = ['part_number_label'];

    public function mapping()
    {
        return $this->morphOne(LettersMapping::class, 'letterable');
    }

    public function documentable()
    {
        return $this->morphTo();
    }

    public function activeVersion()
    {
        return $this->belongsTo(UploadVersion::class, 'document_upload_version_id', 'id');
    }

    public function versions()
    {
        return $this->hasMany(UploadVersion::class, 'document_upload_id', 'id');
    }

    /**
     * Get the custom part number string.
     *
     * @return string
     */
    public function getPartNumberLabelAttribute()
    {
        $documentableType = $this->documentable_type;

        $baseClassName = class_basename($documentableType);

        switch ($baseClassName) {
            case 'PublicRelationRequest':
                return match ($this->part_number) {
                    1 => 'Audio',
                    2 => 'Infografis',
                    3 => 'Poster',
                    4 => 'Media',
                    default => 'PR_UNKNOWN_PART_' . $this->part_number,
                };
            case 'AnotherDocumentableModel':
                return match ($this->part_number) {
                1 => 'Formulir A (Other)',
                    2 => 'Laporan B (Other)',
                    default => 'OTHER_UNKNOWN_PART_' . $this->part_number,
                };
            default:
                return 'UNKNOWN_DOCUMENTABLE_TYPE_' . $this->part_number;
        }
    }

    public static function getFilePathAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
