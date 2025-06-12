<?php

namespace App\Models\Documents;

use App\Enums\InformationSystemRequestPart;
use App\Enums\PublicRelationRequestPart;
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
                $partEnumCase = PublicRelationRequestPart::tryFrom($this->part_number);
                return $partEnumCase->label();
            case 'Letter':
                $partEnumCase = InformationSystemRequestPart::tryFrom($this->part_number);
                return $partEnumCase->label();
            default:
                return 'UNKNOWN_DOCUMENTABLE_TYPE_' . $this->part_number;
        }
    }

    public function createRevision(string $revisionNote)
    {
        // Increment version
        $nextVersion = $this->versions()->latest('version')->first()->version + 1;

        // Create new version
        $this->versions()->create([
            'version' => $nextVersion,
            'revision_note' => $revisionNote,
        ]);

        // Set need revision to true
        $this->update([
            'need_revision' => true,
        ]);
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
