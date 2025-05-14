<?php

namespace App\Models\Letters;

use App\Models\letters\LettersMapping;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LetterUpload extends Model
{
    use HasFactory;

    protected $table = 'letter_uploads';

    public $fillable = [
        'part_name',
        'file_path',
        'version',
        'needs_revision',
        'revision_note'
    ];

    public function mapping()
    {
        return $this->morphOne(LettersMapping::class, 'letterable');
    }

    /**
     * Get the custom part number string.
     *
     * @param  int  $value
     * @return string
     */
    public function getPartNumberAttribute($value)
    {
        return match ($value) {
            1 => 'Nota Dinas',
            2 => 'SOP',
            3 => 'Pengesahan',
            default => 'UNKNOWN_PART_' . $value, // Default case for other numbers
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
