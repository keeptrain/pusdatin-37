<?php

namespace App\Models\Letters;

use Illuminate\Database\Eloquent\Model;

class LetterDirect extends Model
{
    protected $table = 'letter_directs';

    public $timestamps = false;

    public $fillable = [
        'body'
    ];

    public function letter()
    {
        return $this->morphOne(Letter::class, 'category_type');
    }

}