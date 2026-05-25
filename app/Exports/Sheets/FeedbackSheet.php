<?php

namespace App\Exports\Sheets;

use App\Models\Office;
use App\Services\ConsolidatedReportService as RS;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FeedbackSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(
        private int $year,
        private ?int $quarter
    ) {}

    public function title(): string
    {
        return 'FEEDBACK';
    }

    public function columnWidths(): array
    {
        return ['A' => 30, 'B' => 80];
    }

    public function array(): array
    {
        $rows    = [];
        $rows[]  = ['MOST COMMON FEEDBACK PER OFFICE — FY ' . $this->year, ''];
        $rows[]  = ['', ''];

        $offices = Office::active()->with('services')->orderBy('name')->get();

        foreach ($offices as $office) {
            $suggestions = RS::query($this->year, $this->quarter, $office->id)
                ->whereNotNull('suggestion')
                ->where('suggestion', '!=', '')
                ->pluck('suggestion')
                ->toArray();

            // Show top suggestions (up to 5)
            $topFeedback = implode("\n", array_slice($suggestions, 0, 5));

            $rows[] = [
                $office->name,
                $topFeedback ?: 'No feedback recorded.',
            ];
            $rows[] = ['', ''];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}