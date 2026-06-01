<?php

namespace App\Exports\Sheets;

use App\Models\Office;
use App\Models\SurveyResponse;
use App\Services\ConsolidatedReportService as RS;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class OfficeSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(
        private Office $office,
        private int $year,
        private ?int $quarter
    ) {}

    public function title(): string
    {
        // Sheet names max 31 chars, no special chars
        return substr(strtoupper($this->office->code ?? $this->office->name), 0, 31);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
        ];
    }

    public function array(): array
    {
        $services  = $this->office->services()->get();
        $sqdLabels = SurveyResponse::sqdQuestions();
        $rows      = [];

        // ── Header row ─────────────────────────────────────
        $serviceNames = $services->pluck('name')->toArray();
        $rows[]       = array_merge(
            [$this->office->name . ' — FY ' . $this->year, ''],
            $serviceNames
        );
        $rows[]       = ['', ''];  // spacer

        // ── For each service, pull data ─────────────────────
        $allServiceData = [];
        foreach ($services as $service) {
            $responses = RS::query($this->year, $this->quarter, $this->office->id)
                ->where('service_id', $service->id)
                ->get();

            $allServiceData[$service->id] = [
                'responses' => $responses,
                'rating'    => RS::computeNumericalRating($responses),
            ];
        }

        // Helper: build a row with label + per-service values
        $row = function (string $label, callable $valueFn) use ($services, $allServiceData): array {
            $r = [$label, ''];
            foreach ($services as $service) {
                $r[] = $valueFn($allServiceData[$service->id]);
            }
            return $r;
        };

        // ── Total Respondents ──────────────────────────────
        $rows[] = $row(
            'Total Number of Clients who completed the survey for FY ' . $this->year,
            fn ($d) => $d['responses']->count()
        );
        $rows[] = ['Brief Analysis', ''];
        $rows[] = ['', ''];

        // ── Demographic Profile ────────────────────────────
        $rows[] = ['Demographic Profile', '19 or lower',
            ...$services->map(fn ($s) =>
                $allServiceData[$s->id]['responses']->where('age', '<=', 19)->count()
            )->toArray()
        ];
        foreach (['20-34' => [20,34], '35-49' => [35,49], '50-64' => [50,64]] as $label => $range) {
            $rows[] = ['', $label,
                ...$services->map(fn ($s) =>
                    $allServiceData[$s->id]['responses']
                        ->whereBetween('age', $range)->count()
                )->toArray()
            ];
        }
        $rows[] = ['', '65-higher',
            ...$services->map(fn ($s) =>
                $allServiceData[$s->id]['responses']->where('age', '>=', 65)->count()
            )->toArray()
        ];

        // Sex
        foreach (['Male', 'Female'] as $gender) {
            $rows[] = [
                $gender === 'Male' ? 'Sex' : '',
                $gender,
                ...$services->map(fn ($s) =>
                    $allServiceData[$s->id]['responses']->where('gender', $gender)->count()
                )->toArray()
            ];
        }

        // Customer Type
        foreach (['Citizen', 'Business', 'Government'] as $i => $type) {
            $rows[] = [
                $i === 0 ? 'Customer Type' : '',
                $type,
                ...$services->map(fn ($s) =>
                    $allServiceData[$s->id]['responses']->where('customer_type', $type)->count()
                )->toArray()
            ];
        }

        $rows[] = ['Brief Analysis (results of the demographic profile)', ''];
        $rows[] = ['', ''];

        // ── Citizen's Charter ──────────────────────────────
        $cc1Labels = ['1. Yes', '2. No', '3. N/A', '4. --'];
        foreach ($cc1Labels as $i => $label) {
            $val = $i + 1;
            $rows[] = [
                $i === 0 ? "Citizen's Charter Awareness" : '',
                $label,
                ...$services->map(fn ($s) =>
                    $allServiceData[$s->id]['responses']->where('cc1', $val)->count()
                )->toArray()
            ];
        }

        $cc2Labels = ['1. Easy to Find', '2. Hard to Find', '3. Not Applicable', '4. --'];
        foreach ($cc2Labels as $i => $label) {
            $val = $i + 1;
            $rows[] = [
                $i === 0 ? "Citizen's Charter Visibility" : '',
                $label,
                ...$services->map(fn ($s) =>
                    $allServiceData[$s->id]['responses']->where('cc2', $val)->count()
                )->toArray()
            ];
        }

        $cc3Labels = ['1. Yes', '2. No', '3. --', '4. --'];
        foreach ($cc3Labels as $i => $label) {
            $val = $i + 1;
            $rows[] = [
                $i === 0 ? "Citizen's Charter Helpfulness" : '',
                $label,
                ...$services->map(fn ($s) =>
                    $allServiceData[$s->id]['responses']->where('cc3', $val)->count()
                )->toArray()
            ];
        }

        $rows[] = ['Brief Analysis (results of the CC responses)', ''];
        $rows[] = ['', ''];

        // ── SQD 0 (Overall Satisfaction) ───────────────────
        $sqd0Labels = ['Strongly Disagree', 'Disagree', 'Agree', 'Strongly Agree', 'N/A'];
        $sqd0Values = [1, 2, 4, 5, 0];
        foreach ($sqd0Labels as $i => $label) {
            $val = $sqd0Values[$i];
            $rows[] = [
                $i === 0 ? 'SQD 0' : '',
                $label,
                ...$services->map(fn ($s) =>
                    $allServiceData[$s->id]['responses']->where('sqd0', $val)->count()
                )->toArray()
            ];
        }

        // ── SQD 1 – 8 ─────────────────────────────────────
        $sqdDimensions = [
            'sqd1' => 'SQD 1 (Responsiveness)',
            'sqd2' => 'SQD 2 (Reliability)',
            'sqd3' => 'SQD 3 (Access and Facility)',
            'sqd4' => 'SQD 4 (Communication)',
            'sqd5' => 'SQD 5 (Costs)',
            'sqd6' => 'SQD 6 (Integrity)',
            'sqd7' => 'SQD 7 (Assurance)',
            'sqd8' => 'SQD 8 (Outcome)',
        ];

        $ratingLabels = [
            1 => 'Strongly Disagree',
            2 => 'Disagree',
            3 => 'Neither Agree or Disagree',
            4 => 'Agree',
            5 => 'Strongly Agree',
            0 => 'N/A',
        ];

        foreach ($sqdDimensions as $key => $dimLabel) {
            foreach ($ratingLabels as $val => $rLabel) {
                $isFirst = $val === 1;
                $rows[] = [
                    $isFirst ? $dimLabel : '',
                    $rLabel,
                    ...$services->map(fn ($s) =>
                        $allServiceData[$s->id]['responses']->where($key, $val)->count()
                    )->toArray()
                ];
            }
        }

        $rows[] = ['Brief analysis (result count of SQD questions)', ''];
        $rows[] = ['', ''];

        // ── Summary Rating Row ─────────────────────────────
        $rows[] = [
            'Numerical Rating',
            '',
            ...$services->map(fn ($s) =>
                $allServiceData[$s->id]['rating']
                    ? number_format($allServiceData[$s->id]['rating'], 2)
                    : 'N/A'
            )->toArray()
        ];

        $rows[] = [
            'Adjectival Rating',
            '',
            ...$services->map(fn ($s) =>
                RS::adjectivalRating($allServiceData[$s->id]['rating'])
            )->toArray()
        ];

        $rows[] = [
            'Avg Fill Time',
            '',
            ...$services->map(function ($s) use ($allServiceData) {
                $avg = $allServiceData[$s->id]['responses']
                    ->whereNotNull('duration_seconds')
                    ->avg('duration_seconds');

                return $avg
                    ? sprintf('%d:%02d', intdiv((int) round($avg), 60), (int) round($avg) % 60)
                    : '—';
            })->toArray()
        ];

        $rows[] = ['Most major / most common identified feedback / concern from clients', ''];
        $rows[] = ['', ''];

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        // Header row style
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0F766E'],
                ],
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }
}