<?php

namespace App\Exports\Sheets;

use App\Models\Office;
use App\Services\ConsolidatedReportService as RS;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuarterlyReportSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(private int $year) {}

    public function title(): string
    {
        return 'QUARTERLY REPORT';
    }

    public function columnWidths(): array
    {
        return ['A' => 45, 'B' => 12, 'C' => 12, 'D' => 12, 'E' => 12, 'F' => 18];
    }

    public function array(): array
    {
        $rows    = [];
        $rows[]  = ['OFFICES', 'Q1', 'Q2', 'Q3', 'Q4', 'TOTAL Respondents'];

        $offices = Office::active()->with('services')->orderBy('name')->get();

        foreach ($offices as $office) {
            // Office header row
            $rows[] = [$office->name, '', '', '', '', ''];

            foreach ($office->services as $service) {
                $quarterTotals = [];
                $grandTotal    = 0;

                for ($q = 1; $q <= 4; $q++) {
                    $count = RS::query($this->year, $q, $office->id)
                        ->where('service_id', $service->id)
                        ->count();
                    $quarterTotals[] = $count;
                    $grandTotal += $count;
                }

                $rows[] = [
                    '  ' . $service->name,
                    ...$quarterTotals,
                    $grandTotal,
                ];
                $rows[] = ['', '', '', '', '', ''];  // spacer
            }
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }
}