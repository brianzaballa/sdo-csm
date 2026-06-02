<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'office_id',
        'name',
        'is_active',
        'is_external',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_external' => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
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

    public function scopeExternal($query)
    {
        return $query->where('is_external', true);
    }

    public function scopeInternal($query)
    {
        return $query->where('is_external', false);
    }
}
