<?php

namespace App\Models\Letter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class LetterUpload extends Model
{
    protected $table = 'letter_uploads';

    public $timestamps = false;

    public $fillable = [
        'file_name', 
        'file_path'
    ];

    public function letter(): MorphOne {
        return $this->morphOne(Letter::class, 'category_type');
    }

}
