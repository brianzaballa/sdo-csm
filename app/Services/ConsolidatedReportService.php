<?php

namespace App\Services;

use App\Models\Office;
use App\Models\SurveyResponse;
use Illuminate\Support\Collection;

class ConsolidatedReportService
{
    // ── Adjectival Rating Scale ────────────────────────────
    public static function adjectivalRating(?float $score): string
    {
        if (is_null($score) || $score == 0) return 'N/A';

        return match (true) {
            $score >= 4.50 => 'Outstanding',
            $score >= 3.50 => 'Very Satisfactory',
            $score >= 2.50 => 'Satisfactory',
            $score >= 1.50 => 'Unsatisfactory',
            default        => 'Poor',
        };
    }

    // ── Quarter month ranges ───────────────────────────────
    public static function quarterMonths(int $quarter): array
    {
        return match ($quarter) {
            1 => [1, 2, 3],
            2 => [4, 5, 6],
            3 => [7, 8, 9],
            4 => [10, 11, 12],
            default => [],
        };
    }

    public static function quarterLabel(int $quarter): string
    {
        return match ($quarter) {
            1 => 'Q1 (January – March)',
            2 => 'Q2 (April – June)',
            3 => 'Q3 (July – September)',
            4 => 'Q4 (October – December)',
            default => '',
        };
    }

    // ── Base query builder ────────────────────────────────
    public static function query(int $year, ?int $quarter = null, ?int $officeId = null)
    {
        $query = SurveyResponse::with(['office', 'service'])
            ->where('is_complete', true)
            ->whereYear('created_at', $year);

        if ($quarter) {
            $months = self::quarterMonths($quarter);
            $query->where(function ($q) use ($months) {
                foreach ($months as $month) {
                    $q->orWhereMonth('created_at', $month);
                }
            });
        }

        if ($officeId) {
            $query->where('office_id', $officeId);
        }

        return $query;
    }

    // ── Compute average SQD score (SQD1-SQD8, exclude SQD0 & N/A) ──
    public static function computeNumericalRating(Collection $responses): ?float
    {
        $keys = ['sqd1','sqd2','sqd3','sqd4','sqd5','sqd6','sqd7','sqd8'];

        $scores = $responses->flatMap(function ($r) use ($keys) {
            return collect($keys)
                ->map(fn ($k) => $r->$k)
                ->filter(fn ($v) => !is_null($v) && $v > 0);
        });

        return $scores->isNotEmpty()
            ? round($scores->avg(), 2)
            : null;
    }

    // ── Age group breakdown ───────────────────────────────
    public static function ageGroups(Collection $responses): array
    {
        return [
            '19 or lower' => $responses->where('age', '<=', 19)->count(),
            '20-34'       => $responses->whereBetween('age', [20, 34])->count(),
            '35-49'       => $responses->whereBetween('age', [35, 49])->count(),
            '50-64'       => $responses->whereBetween('age', [50, 64])->count(),
            '65-higher'   => $responses->where('age', '>=', 65)->count(),
        ];
    }

    // ── SQD counts per question ───────────────────────────
    public static function sqdCounts(Collection $responses, string $key): array
    {
        return [
            'Strongly Disagree'        => $responses->where($key, 1)->count(),
            'Disagree'                 => $responses->where($key, 2)->count(),
            'Neither Agree or Disagree' => $responses->where($key, 3)->count(),
            'Agree'                    => $responses->where($key, 4)->count(),
            'Strongly Agree'           => $responses->where($key, 5)->count(),
            'N/A'                      => $responses->where($key, 0)->count(),
        ];
    }

    // ── CC counts ─────────────────────────────────────────
    public static function ccCounts(Collection $responses): array
    {
        return [
            'cc1' => [
                1 => $responses->where('cc1', 1)->count(),
                2 => $responses->where('cc1', 2)->count(),
                3 => $responses->where('cc1', 3)->count(),
                4 => $responses->where('cc1', 4)->count(),
            ],
            'cc2' => [
                1 => $responses->where('cc2', 1)->count(),
                2 => $responses->where('cc2', 2)->count(),
                3 => $responses->where('cc2', 3)->count(),
                4 => $responses->where('cc2', 4)->count(),
                5 => $responses->where('cc2', 5)->count(),
            ],
            'cc3' => [
                1 => $responses->whereNotNull('cc3')->where('cc3', 1)->count(),
                2 => $responses->whereNotNull('cc3')->where('cc3', 2)->count(),
                3 => $responses->whereNotNull('cc3')->where('cc3', 3)->count(),
                4 => $responses->whereNotNull('cc3')->where('cc3', 4)->count(),
            ],
        ];
    }

    // ── Build full office data for one office ─────────────
    public static function buildOfficeData(Office $office, int $year): array
    {
        $services  = $office->services()->with([])->get();
        $allData   = [];

        foreach ($services as $service) {
            $responses = self::query($year, null, $office->id)
                ->where('service_id', $service->id)
                ->get();

            $rating = self::computeNumericalRating($responses);

            $allData[$service->name] = [
                'total_respondents' => $responses->count(),
                'age_groups'        => self::ageGroups($responses),
                'gender'            => [
                    'Male'           => $responses->where('gender', 'Male')->count(),
                    'Female'         => $responses->where('gender', 'Female')->count(),
                    'Did not specify' => 0,
                ],
                'customer_type'     => [
                    'Citizen'    => $responses->where('customer_type', 'Citizen')->count(),
                    'Business'   => $responses->where('customer_type', 'Business')->count(),
                    'Government' => $responses->where('customer_type', 'Government')->count(),
                ],
                'cc'                => self::ccCounts($responses),
                'sqd'               => collect(SurveyResponse::sqdKeys())
                    ->mapWithKeys(fn ($k) => [$k => self::sqdCounts($responses, $k)])
                    ->toArray(),
                'sqd0_counts'       => [
                    'Strongly Disagree' => $responses->where('sqd0', 1)->count(),
                    'Disagree'          => $responses->where('sqd0', 2)->count(),
                    'Agree'             => $responses->where('sqd0', 4)->count(),
                    'Strongly Agree'    => $responses->where('sqd0', 5)->count(),
                    'N/A'               => $responses->where('sqd0', 0)->count(),
                ],
                'suggestions'       => $responses->pluck('suggestion')
                    ->filter()
                    ->values()
                    ->toArray(),
                'numerical_rating'  => $rating,
                'adjectival_rating' => self::adjectivalRating($rating),
                'quarterly'         => self::buildQuarterlyBreakdown(
                    $office->id, $service->id, $year
                ),
            ];
        }

        return $allData;
    }

    // ── Per-service quarterly breakdown ───────────────────
    public static function buildQuarterlyBreakdown(
        int $officeId,
        int $serviceId,
        int $year
    ): array {
        $quarters = [];
        for ($q = 1; $q <= 4; $q++) {
            $responses = self::query($year, $q, $officeId)
                ->where('service_id', $serviceId)
                ->get();

            $rating = self::computeNumericalRating($responses);

            $quarters[$q] = [
                'total'             => $responses->count(),
                'numerical_rating'  => $rating,
                'adjectival_rating' => self::adjectivalRating($rating),
            ];
        }

        return $quarters;
    }

    // ── Build all offices summary for a quarter ───────────
    public static function buildQuarterlySummary(int $year, int $quarter): array
    {
        $offices = Office::active()->with('services')->get();
        $summary = [];

        foreach ($offices as $office) {
            $responses = self::query($year, $quarter, $office->id)->get();
            $rating    = self::computeNumericalRating($responses);

            $summary[] = [
                'office'            => $office->name,
                'total'             => $responses->count(),
                'numerical_rating'  => $rating,
                'adjectival_rating' => self::adjectivalRating($rating),
            ];
        }

        return $summary;
    }
}