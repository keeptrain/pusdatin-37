<?php

namespace App\Models\letters;

use App\Models\Documents\DocumentUpload;
use Illuminate\Database\Eloquent\Model;

class LettersMapping extends Model
{
    protected $table = 'letters_mappings';

    public $fillable = [
        'letter_id',
        'letterable_type',
        'letterable_id'
    ];

    public $timestamps = false;

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }

    public function letterable()
    {
        return $this->morphTo();
    }

    public function documentUploads()
    {
        return $this->hasMany(DocumentUpload::class);
    }
}
