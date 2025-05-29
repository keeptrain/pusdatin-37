<?php

namespace App\Models\Documents;

use App\Models\letters\LettersMapping;
use Illuminate\Database\Eloquent\Model;
use App\Models\Documents\UploadVersion;
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
            case 'Letter':
                return match ($this->part_number) {
                    1 => 'SPBE',
                    2 => 'SOP',
                    3 => 'Pemanfaatan Aplikasi',
                    4 => 'RFC',
                    5 => 'NDA',
                    default => "OTHER_UNKNOWN_PART_$this->part_number",
                };
            default:
                return 'UNKNOWN_DOCUMENTABLE_TYPE_' . $this->part_number;
        }
    }

    public function getLatestUnapprovedRevision()
    {
        return $this->versions()
            ->where('is_resolved', false)
            ->orderByDesc('version')
            ->first();
    }

    public function hasUnapprovedRevision()
    {
        return $this->versions()
            ->where('is_resolved', false)
            ->exists();
    }

    public function formatForCurrentVersion()
    {
        $activeVersion = $this->activeVersion;

        return [
            'part_number' => $this->part_number,
            'part_number_label' => $this->part_number_label,
            'file_path' => $activeVersion?->file_path,
            'revision_note' => $activeVersion?->revision_note,
        ];
    }

    public function formatForLatestUnapprovedRevision()
    {
        $latestUnapprovedRevision = $this->getLatestUnapprovedRevision();

        return [
            'part_number' => $this->part_number,
            'part_number_label' => $this->part_number_label,
            'file_path' => $latestUnapprovedRevision?->file_path,
            'revision_note' => $latestUnapprovedRevision?->revision_note,
        ];
    }
}
