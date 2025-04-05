<?php

namespace App\Models\Letters;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoryTypeNameAttribute()
    {
        $morphClass = $this->category_type;

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

    public static function getLetterForTable()
    {
        return Letter::select(['id', 'user_id', 'category_type', 'created_at'])
            ->with('user:id,name');
    }
}
