<?php

namespace App\Models\Letter;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    public $table = "letters";

    public $fillable = [
        'user_id',
        'category_type',
        'category_id',
        'responsible_person',
        'reference_number'
    ];

    public $timestamps = true;
}
