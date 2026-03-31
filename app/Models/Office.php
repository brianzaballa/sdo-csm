<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Office extends Model
{
    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function activeServices(): HasMany
    {
        return $this->hasMany(Service::class)
            ->where('is_active', true)
            ->orderBy('name');
    }

    public function surveyResponses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    // ── Scopes ─────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ── Accessors ──────────────────────────────────────────

    public function getDisplayNameAttribute(): string
    {
        return $this->code
            ? "{$this->code} - {$this->name}"
            : $this->name;
    }
}
