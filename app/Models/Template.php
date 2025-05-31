<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table = "document_templates";

    protected $fillable = [
        'name',
        'part_number',
        'file_path',
        'is_active'
    ];

    public function getPartNumberLabelAttribute()
    {
        return match ($this->part_number) {
            1 => 'SPBE',
            2 => 'SOP',
            3 => 'Pemanfaatan Aplikasi',
            4 => 'RFC',
            5 => 'NDA',
            6 => 'Universal',
        };
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d F Y, H:i');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d F Y, H:i');
    }

    // public function getIsActiveAttribute($value) {
    //     return match ($value) {
    //         0 => 'Tidak Aktif',
    //         1 => 'Aktif',
    //     };
    // }

    public function scopeFilterByRole($query, $user)
    {
        if ($user->roles()->where('name', 'pr_verifier')->exists()) {
            return $query->where('part_number', 6);
        }

        return $query;
    }
}
