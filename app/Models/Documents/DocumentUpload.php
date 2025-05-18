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
        'part_number',
        'need_revision',
    ];

    public function mapping()
    {
        return $this->morphOne(LettersMapping::class, 'letterable');
    }

    public function activeVersion()
    {
        return $this->hasMany(UploadVersion::class, 'id', 'document_upload_version_id');
    }

    public function version()
    {
        return $this->hasMany(UploadVersion::class, 'document_upload_id');
    }

    /**
     * Get the custom part number string.
     *
     * @return string
     */
    public function getPartNumberLabelAttribute()
    {
        return match ($this->part_number) {
            1 => 'Nota Dinas',
            2 => 'SOP',
            3 => 'Pengesahan',
            default => 'UNKNOWN_PART_' . $this->part_number, // Default case for other numbers
        };
    }

    public static function getLetterUpload()
    {
        return Letter::with('id', 'name', 'category_type')
            ->get();
    }

    public static function getFilePathAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
