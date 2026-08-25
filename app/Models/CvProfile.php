<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvProfile extends Model
{
    protected $fillable = [
        'user_id',
        'file_path',
        'raw_text',
        'parsed_data',
        'parsed_at',
        'status',
        'upgraded_data',
        'upgrade_status',
        'upgraded_at',
    ];

    protected function casts(): array
    {
        return [
            'parsed_data'   => 'array',
            'parsed_at'     => 'datetime',
            'upgraded_data' => 'array',
            'upgraded_at'   => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool { return $this->status === 'pending'; }
    public function isParsing(): bool { return $this->status === 'parsing'; }
    public function isParsed(): bool { return $this->status === 'parsed'; }
    public function isFailed(): bool { return $this->status === 'failed'; }

    public function hasUpgrade(): bool    { return $this->upgrade_status === 'ready' && filled($this->upgraded_data); }
    public function isUpgrading(): bool   { return $this->upgrade_status === 'generating'; }
    public function upgradeReady(): bool  { return $this->hasUpgrade(); }
    public function upgradeFailed(): bool { return $this->upgrade_status === 'failed'; }

    public function getSkillsAttribute(): array { return $this->parsed_data['skills'] ?? []; }
    public function getNicheAttribute(): ?string { return $this->parsed_data['target_niche'] ?? null; }
    public function getPreferredLocationAttribute(): ?string { return $this->parsed_data['preferred_location'] ?? null; }
}
