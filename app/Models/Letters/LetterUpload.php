<?php

namespace App\Models\Letters;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\LetterUploadFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LetterUpload extends Model
{
    use HasFactory;

    protected $table = 'letter_uploads';

    public $timestamps = false;

    public $fillable = [
        'part_name',
        'file_path',
        'version',
        'needs_revision',
        'revision_note'
    ];

    const PART_ATTACHMENT = ['part1', 'part2', 'part3'];

    public function letter(): MorphOne
    {
        return $this->morphOne(Letter::class, 'letterable');
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
