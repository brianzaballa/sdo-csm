<?php

namespace App\Exports\Sheets;

use App\Models\Office;
use App\Services\ConsolidatedReportService as RS;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class QuarterSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(
        private int $year,
        private int $quarter
    ) {}

    public function title(): string
    {
        return 'Q' . $this->quarter;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 50, 'B' => 10, 'C' => 10,
            'D' => 18, 'E' => 20,
        ];
    }

    public function array(): array
    {
        $rows    = [];
        $offices = Office::active()->with('services')->orderBy('name')->get();

        // ── Main table header ──────────────────────────────
        $rows[] = [
            'OFFICES',
            'Q' . $this->quarter,
            'Total Responses',
            'Numerical Rating',
            'Adjectival Rating',
        ];

        // ── Per office / service rows ──────────────────────
        foreach ($offices as $office) {
            $rows[] = [$office->name, '', '', '', ''];

            foreach ($office->services as $service) {
                $responses = RS::query($this->year, $this->quarter, $office->id)
                    ->where('service_id', $service->id)
                    ->get();

                $rating = RS::computeNumericalRating($responses);

                $rows[] = [
                    '  ' . $service->name,
                    '',
                    $responses->count(),
                    $rating ? number_format($rating, 2) : 'N/A',
                    RS::adjectivalRating($rating),
                ];
                $rows[] = ['', '', '', '', ''];
            }
        }

        // ── Summary table (right side) ─────────────────────
        $rows[] = ['', '', '', '', ''];
        $rows[] = [
            'Summary — ' . RS::quarterLabel($this->quarter),
            '', '', '', '',
        ];
        $rows[] = ['Office', 'Total Responses', 'Numerical Rating', 'Adjectival Rating', ''];

        foreach ($offices as $office) {
            $responses = RS::query($this->year, $this->quarter, $office->id)->get();
            $rating    = RS::computeNumericalRating($responses);

            $rows[] = [
                $office->name,
                $responses->count(),
                $rating ? number_format($rating, 2) : 'N/A',
                RS::adjectivalRating($rating),
                '',
            ];
        }

        // Overall
        $allResponses = RS::query($this->year, $this->quarter)->get();
        $overallRating = RS::computeNumericalRating($allResponses);

        $rows[] = [
            'Overall Rating',
            $allResponses->count(),
            $overallRating ? number_format($overallRating, 2) : 'N/A',
            RS::adjectivalRating($overallRating),
            '',
        ];

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0F766E'],
                ],
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }
}