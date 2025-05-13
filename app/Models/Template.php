<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table="document_templates";

    protected $fillable=[
        'name',
        'type_number',
        'file_path',
        'is_active'
    ];

}
