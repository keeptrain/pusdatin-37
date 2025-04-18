<?php

namespace App\Models\Letters;

use Carbon\Carbon;
use App\Models\User;
use App\States\LetterStatus;
use Spatie\ModelStates\HasStates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Letter extends Model
{

    use HasStates,SoftDeletes;

    protected $table = "letters";

    protected $casts = [
        'status' => LetterStatus::class,
    ];
    
    public $fillable = [
        'user_id',
        'title',
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

    public function requestStatusTrack()
    {
        return $this->hasMany(RequestStatusTrack::class);
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