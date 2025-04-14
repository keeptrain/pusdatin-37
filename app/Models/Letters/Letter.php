<?php

namespace App\Models\Letters;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Letter extends Model
{

    use SoftDeletes;

    public $table = "letters";

    public $fillable = [
        'user_id',
        'letterable_type',
        'letterable_id',
        'status',
        'responsible_person',
        'reference_number'
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function letterable() : MorphTo
    {
        return $this->morphTo();
    }

    public function getCategoryTypeNameAttribute()
    {
        $morphClass = $this->letterable_type;

        // Pisahkan string berdasarkan backslash
        $parts = explode('\\', $morphClass);
        $className = end($parts);

        // Hapus awalan "Letter" jika ada
        if (str_starts_with($className, 'Letter')) {
            return substr($className, 6); // Menghapus "Letter"
        }

        return $className;
    }

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('F j, Y');
    }

    public static function queryForTable()
    {
        return Letter::select(['id', 'user_id', 'letterable_type', 'status',  'created_at'])
            ->with('user:id,name')
            ;
    }
}
