<?php

namespace App\Models\Letters;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Letter extends Model
{

    use SoftDeletes;

    public $table = "letters";

    public $fillable = [
        'user_id',
        'category_type',
        'category_id',
        'status',
        'responsible_person',
        'reference_number'
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoryType()
    {
        return $this->morphTo();
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

    public static function queryForTable()
    {
        return Letter::select(['id', 'user_id', 'category_type', 'status',  'created_at'])
            ->with('user:id,name')
            ->with(['categoryType' => function ($morphTo) {
                $morphTo->morphWith([
                    LetterUpload::class => ['letter_uploads:id'],
                    LetterDirect::class => ['letter_directs:id'],
                ]);
            }]);
    }
}
