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

    const PART_ATTACHMENT = ['part1', 'part2', 'part3'];

    public function mapping()
    {
        return $this->morphOne(LettersMapping::class, 'letterable');
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
