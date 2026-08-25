<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function cvProfile(): HasOne
    {
        return $this->hasOne(CvProfile::class);
    }

    public function jobMatches(): HasMany
    {
        return $this->hasMany(JobMatch::class);
    }

    public function skillGaps(): HasMany
    {
        return $this->hasMany(SkillGap::class);
    }

    public function recommendedCourses(): HasMany
    {
        return $this->hasMany(RecommendedCourse::class);
    }

    /**
     * URL to the user's avatar — falls back to a generated initials avatar
     * (UI Avatars) so the topbar/profile never show a broken image.
     */
    public function getProfileImageUrlAttribute(): string
    {
        if ($this->profile_image && file_exists(public_path('uploads/avatars/' . $this->profile_image))) {
            return asset('uploads/avatars/' . $this->profile_image) . '?v=' . filemtime(public_path('uploads/avatars/' . $this->profile_image));
        }

        $name = urlencode($this->name ?: 'User');
        return "https://ui-avatars.com/api/?name={$name}&background=6366f1&color=fff&bold=true&size=256";
    }
}
