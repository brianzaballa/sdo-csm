<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponse extends Model
{
    protected $fillable = [
        // INFO
        'office_id',
        'service_id',
        'age',
        'gender',
        'customer_type',
        // CC
        'cc1',
        'cc2',
        'cc3',
        // SQD
        'sqd0',
        'sqd1',
        'sqd2',
        'sqd3',
        'sqd4',
        'sqd5',
        'sqd6',
        'sqd7',
        'sqd8',
        // S/R
        'suggestion',
        // META
        'return_url',
        'is_complete',
    ];

    protected $casts = [
        'age' => 'integer',
        'cc1' => 'integer',
        'cc2' => 'integer',
        'cc3' => 'integer',
        'sqd0' => 'integer',
        'sqd1' => 'integer',
        'sqd2' => 'integer',
        'sqd3' => 'integer',
        'sqd4' => 'integer',
        'sqd5' => 'integer',
        'sqd6' => 'integer',
        'sqd7' => 'integer',
        'sqd8' => 'integer',
        'is_complete' => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // ── Helpers ────────────────────────────────────────────

    /**
     * All SQD keys in order.
     */
    public static function sqdKeys(): array
    {
        return [
            'sqd0', 'sqd1', 'sqd2',
            'sqd3', 'sqd4', 'sqd5',
            'sqd6', 'sqd7', 'sqd8',
        ];
    }

    /**
     * All SQD questions in order.
     */
    public static function sqdQuestions(): array
    {
        return [
            'sqd0' => 'I am satisfied with the service that I availed.',
            'sqd1' => 'I spent a reasonable amount of time for my transaction.',
            'sqd2' => 'The office followed the transaction\'s requirements and steps based on the information provided.',
            'sqd3' => 'The steps (including payment) I needed to do for my transaction were easy and simple.',
            'sqd4' => 'I easily found information about my transaction from the office or its website.',
            'sqd5' => 'I paid a reasonable amount of fees for my transaction.',
            'sqd6' => 'I am confident my transaction was secure.',
            'sqd7' => 'The office\'s online transaction system was accessible (if applicable).',
            'sqd8' => 'I got what I needed from the government office, or what I availed was the government office\'s service.',
        ];
    }

    /**
     * Human-readable label for a rating value.
     */
    public static function ratingLabel(int $value): string
    {
        return match ($value) {
            5 => 'Strongly Agree',
            4 => 'Agree',
            3 => 'Neither Agree nor Disagree',
            2 => 'Disagree',
            1 => 'Strongly Disagree',
            0 => 'Not Applicable',
            default => 'N/A',
        };
    }

    /**
     * Emoji for a rating value.
     */
    public static function ratingEmoji(int $value): string
    {
        return match ($value) {
            5 => '😊',
            4 => '🙂',
            3 => '😐',
            2 => '😕',
            1 => '😞',
            0 => '—',
            default => '—',
        };
    }

    /**
     * CC1 answer labels.
     */
    public static function cc1Labels(): array
    {
        return [
            1 => 'I know what a CC is and I was this office\'s CC',
            2 => 'I know what a CC is but I did NOT see this office\'s CC',
            3 => 'I learned of the CC only when I saw this office\'s CC',
            4 => 'I do not know what a CC is and I did not see one in this office',
        ];
    }

    /**
     * CC2 answer labels.
     */
    public static function cc2Labels(): array
    {
        return [
            1 => 'Easy to see',
            2 => 'Somewhat easy to see',
            3 => 'Difficult to see',
            4 => 'Not visible at all',
            5 => 'N/A',
        ];
    }

    /**
     * CC3 answer labels.
     */
    public static function cc3Labels(): array
    {
        return [
            1 => 'Helped very much',
            2 => 'Somewhat helped',
            3 => 'Did not help',
            4 => 'N/A',
        ];
    }

    // ── Computed Accessors ─────────────────────────────────

    /**
     * Average SQD score (excludes N/A = 0).
     */
    public function getAverageSqdAttribute(): ?float
    {
        $scores = collect(self::sqdKeys())
            ->map(fn ($key) => $this->$key)
            ->filter(fn ($v) => ! is_null($v) && $v > 0); // exclude null & N/A

        return $scores->count() > 0
            ? round($scores->avg(), 2)
            : null;
    }

    /**
     * Overall satisfaction label based on SQD0.
     */
    public function getSatisfactionLabelAttribute(): string
    {
        return self::ratingLabel($this->sqd0 ?? 0);
    }

    /**
     * Scopes
     */
    public function scopeComplete($query)
    {
        return $query->where('is_complete', true);
    }

    public function scopeForOffice($query, int $officeId)
    {
        return $query->where('office_id', $officeId);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('created_at', now()->year);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }
}
