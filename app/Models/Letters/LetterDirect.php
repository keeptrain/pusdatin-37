<?php

namespace App\Models\Letters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class LetterDirect extends Model
{
    protected $table = 'letter_directs';

    public $timestamps = false;

    public $fillable = [
        'body'
    ];

    /**
     * @return MorphOne
     */
    public function letter(): MorphOne {
        return $this->morphOne(Letter::class, 'letterable');
    }

}