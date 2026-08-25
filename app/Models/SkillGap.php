<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillGap extends Model
{
    protected $fillable = ['user_id', 'skill_name', 'reason', 'importance'];

    protected function casts(): array
    {
        return ['importance' => 'integer'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
