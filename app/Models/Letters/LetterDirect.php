<?php

namespace App\Models\Letters;

use Illuminate\Database\Eloquent\Model;

class LetterDirect extends Model
{
    protected $table = 'letter_directs';

    public $fillable = [
        'body'
    ];

    public function mappings()
    {
        return $this->hasMany(LettersMapping::class, 'letter_id');
    }
}
