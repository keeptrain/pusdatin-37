<?php

namespace App\Models\Letters;

use Illuminate\Database\Eloquent\Model;

class LetterDirect extends Model
{
    protected $table = 'letter_directs';

    public $fillable = [
        'body'
    ];

    public $timestamps = false;

    public function mappings()
    {
        return $this->hasMany(LettersMapping::class, 'letter_id');
    }
}
