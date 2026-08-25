<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobMatch extends Model
{
    protected $table = 'job_matches';

    protected $fillable = [
        'user_id', 'job_title', 'company_name', 'location', 'job_url', 'salary',
        'employment_type', 'job_description', 'match_score', 'skills_matched', 'fetched_at',
    ];

    protected function casts(): array
    {
        return [
            'skills_matched' => 'array',
            'fetched_at' => 'datetime',
            'match_score' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
