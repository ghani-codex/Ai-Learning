<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecommendedCourse extends Model
{
    protected $fillable = [
        'user_id', 'course_title', 'youtube_url', 'thumbnail_url', 'channel_name',
        'skill_target', 'duration', 'level', 'fetched_at',
    ];

    protected function casts(): array
    {
        return ['fetched_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
