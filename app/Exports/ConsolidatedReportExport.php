<?php

namespace App\Exports;

use App\Models\Office;
use App\Models\SurveyResponse;
use App\Services\ConsolidatedReportService;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ConsolidatedReportExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(
        public int $year,
        public ?int $quarter = null  // null = all quarters (full year)
    ) {}

    public function sheets(): array
    {
        $sheets  = [];
        $offices = Office::active()->with('services')->orderBy('name')->get();

        // ── One sheet per office ───────────────────────────
        foreach ($offices as $office) {
            $sheets[] = new Sheets\OfficeSheet($office, $this->year, $this->quarter);
        }

        // ── Quarterly report summary ───────────────────────
        $sheets[] = new Sheets\QuarterlyReportSheet($this->year);

        // ── Q1 – Q4 sheets ─────────────────────────────────
        for ($q = 1; $q <= 4; $q++) {
            $sheets[] = new Sheets\QuarterSheet($this->year, $q);
        }

        // ── Feedback sheet ────────────────────────────────
        $sheets[] = new Sheets\FeedbackSheet($this->year, $this->quarter);

        return $sheets;
    }
}