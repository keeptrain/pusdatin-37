<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'section',
        'contact',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $sections = [
        'pusdatin' => 'Pusdatin',
        'promkes' => 'Promosi Kesehatan',
        'kepegawaian' => 'Kepegawaian',
        'kesehatan' => 'Kesehatan',
        'tenaga_kesehatan' => 'Tenaga Kesehatan',
        'umum' => 'Umum',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function informationSystemRequests()
    {
        return $this->hasMany(InformationSystemRequest::class);
    }

    public function publicRelationRequests()
    {
        return $this->hasMany(PublicRelationRequest::class);
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    /**
     * Get the current user from the session.
     *
     */
    public static function currentUser()
    {
        return Auth::user();
    }

    /**
     * Get the current user role id from the session.
     *
     */
    public static function currentUserRoleId()
    {
        return auth()->user()->roles->pluck('id')->first();
    }

    public static function getSections()
    {
        return [
            'pusdatin' => 'Pusdatin',
            'promkes' => 'Promosi Kesehatan',
            'kepegawaian' => 'Kepegawaian',
            'kesehatan' => 'Kesehatan',
            'tenaga_kesehatan' => 'Tenaga Kesehatan',
            'umum' => 'Umum',
        ];
    }

    public function setSectionAttribute($value)
    {
        if (!array_key_exists($value, $this->sections)) {
            throw new \InvalidArgumentException("Key $value invalid");
        }
        $this->attributes['section'] = $value;
    }

    public function getSectionLabelAttribute()
    {
        return $this->sections[$this->section] ?? 'Unknown section';
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d M Y, H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d M Y, H:i:s');
    }
}