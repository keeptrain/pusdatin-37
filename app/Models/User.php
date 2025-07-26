<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Division;
use Carbon\Carbon;
use App\Models\Discussion;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

    /**
     * Get the user's information system requests.
     */
    public function informationSystemRequests()
    {
        return $this->hasMany(InformationSystemRequest::class);
    }

    /**
     * Get the user's public relation requests.
     */
    public function publicRelationRequests()
    {
        return $this->hasMany(PublicRelationRequest::class);
    }

    /**
     * Get the user's discussions.
     */
    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    /**
     * Get the user's initials (maximum 2 characters)
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->filter()
            ->take(2)
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    /**
     * Get the current user from the session.
     */
    public static function currentUser()
    {
        return Auth::user();
    }

    /**
     * Get the current user role id from the session.
     */
    public static function currentUserRoleId()
    {
        return auth()->user()->roles->pluck('id')->first();
    }

    /**
     * Get the sections.
     */
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

    /**
     * Set the user's section.
     */
    public function setSectionAttribute(string $value)
    {
        if (!array_key_exists($value, $this->sections)) {
            throw new \InvalidArgumentException("Section $value invalid");
        }
        $this->attributes['section'] = $value;
    }

    /**
     * Get the user's section label.
     */
    public function getSectionLabelAttribute()
    {
        return $this->sections[$this->section] ?? 'Unknown section';
    }

    /**
     * Get the user's created at attribute.
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d M Y, H:i:s');
    }

    /**
     * Get the user's updated at attribute.
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d M Y, H:i:s');
    }

    /**
     * Get the user's role label.
     */
    public function getRoleLabelAttribute()
    {
        return Division::tryFrom($this->roles->first()->id)->getRoleLabelFromId();
    }
}