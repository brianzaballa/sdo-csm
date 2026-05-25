<?php

namespace App\Filament\Pages;

use App\Exports\ConsolidatedReportExport;
use App\Models\Office;
use App\Services\ConsolidatedReportService as RS;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use BackedEnum;
use UnitEnum;

class ConsolidatedReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | BackedEnum | null $navigationIcon  = 'heroicon-o-document-chart-bar';
    protected static ?string                     $navigationLabel = 'Consolidated Report';
    protected static string | UnitEnum | null    $navigationGroup = 'Survey Results';
    protected static ?int                        $navigationSort  = 2;
    protected string                             $view            = 'filament.pages.consolidated-report';

    public ?string $year    = null;
    public ?string $quarter = null;

    // ── Active preview tab ────────────────────────────────
    public string $activeTab = 'office';

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('download')
                ->label('Download Report')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action('download'),
        ];
    }

    public function mount(): void
    {
        $this->year    = (string) now()->year;
        $this->quarter = '';
        $this->form->fill([
            'year'    => $this->year,
            'quarter' => $this->quarter,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('year')
                    ->label('Fiscal Year')
                    ->options($this->getYearOptions())
                    ->default((string) now()->year)
                    ->required()
                    ->live()
                    ->native(false),

                Select::make('quarter')
                    ->label('Quarter')
                    ->options([
                        ''  => 'All Quarters (Full Year)',
                        '1' => 'Q1 — January to March',
                        '2' => 'Q2 — April to June',
                        '3' => 'Q3 — July to September',
                        '4' => 'Q4 — October to December',
                    ])
                    ->default('')
                    ->live()
                    ->native(false),
            ])
            ->columns(2);
    }

    public function getHeading(): string
    {
        return 'Consolidated Report';
    }

    private function getFilters(): array
    {
        $year    = (int) ($this->year ?? now()->year);
        $quarter = $this->quarter !== '' && $this->quarter !== null
            ? (int) $this->quarter
            : null;

        return compact('year', 'quarter');
    }

    private function getYearOptions(): array
    {
        $years = [];
        for ($y = now()->year; $y >= now()->year - 5; $y--) {
            $years[(string) $y] = 'FY ' . $y;
        }
        return $years;
    }

    // ══════════════════════════════════════════════════════
    // STATS — used in blade view
    // ══════════════════════════════════════════════════════

    public function getStats(): array
    {
        $filters  = $this->getFilters();
        $query    = RS::query($filters['year'], $filters['quarter']);
        $total     = $query->count();
        $offices   = Office::active()->count();
        $responses = $query->get();

        $avgSqd       = RS::computeNumericalRating($responses);
        $satisfied    = $responses->whereIn('sqd0', [4, 5])->count();
        $satisfiedPct = $total > 0 ? round(($satisfied / $total) * 100, 1) : 0;

        return [
            'total'          => number_format($total),
            'offices'        => $offices,
            'avg_sqd'        => $avgSqd ? number_format($avgSqd, 2) : 'N/A',
            'avg_sqd_label'  => RS::adjectivalRating($avgSqd),
            'satisfied_pct'  => $satisfiedPct . '%',
            'satisfied_count'=> number_format($satisfied),
        ];
    }

    // ══════════════════════════════════════════════════════
    // CHART DATA — Responses by office
    // ══════════════════════════════════════════════════════

    public function getOfficeChartData(): array
    {
        $filters = $this->getFilters();

        $offices = Office::active()
            ->withCount(['surveyResponses' => function ($q) use ($filters) {
                $q->where('is_complete', true)->whereYear('created_at', $filters['year']);
                if ($filters['quarter']) {
                    foreach (RS::quarterMonths($filters['quarter']) as $month) {
                        $q->orWhereMonth('created_at', $month);
                    }
                }
            }])
            ->orderByDesc('survey_responses_count')
            ->limit(7)
            ->get();

        $max = $offices->max('survey_responses_count') ?: 1;

        return $offices->map(fn ($o) => [
            'label' => $o->code ?? substr($o->name, 0, 12),
            'count' => $o->survey_responses_count,
            'pct'   => round(($o->survey_responses_count / $max) * 100),
        ])->toArray();
    }

    // ══════════════════════════════════════════════════════
    // CHART DATA — SQD averages
    // ══════════════════════════════════════════════════════

    public function getSqdChartData(): array
    {
        $filters   = $this->getFilters();
        $responses = RS::query($filters['year'], $filters['quarter'])->get();

        $dims = [
            'sqd1' => 'SQD1 Responsive',
            'sqd2' => 'SQD2 Reliable',
            'sqd3' => 'SQD3 Access',
            'sqd4' => 'SQD4 Communic.',
            'sqd5' => 'SQD5 Costs',
            'sqd6' => 'SQD6 Integrity',
            'sqd7' => 'SQD7 Assurance',
            'sqd8' => 'SQD8 Outcome',
        ];

        return collect($dims)->map(function ($label, $key) use ($responses) {
            $scores = $responses
                ->map(fn ($r) => $r->$key)
                ->filter(fn ($v) => !is_null($v) && $v > 0);

            $avg = $scores->isNotEmpty() ? round($scores->avg(), 2) : 0;

            return [
                'label' => $label,
                'avg'   => $avg,
                'pct'   => round(($avg / 5) * 100),
            ];
        })->values()->toArray();
    }

    // ══════════════════════════════════════════════════════
    // PREVIEW TABLE DATA
    // ══════════════════════════════════════════════════════

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function getPreviewData(): array
    {
        $filters = $this->getFilters();

        return match ($this->activeTab) {
            'office'    => $this->previewOfficeSheet($filters['year'], $filters['quarter']),
            'quarterly' => $this->previewQuarterlySheet($filters['year']),
            'q1'        => $this->previewQuarterSheet($filters['year'], 1),
            'feedback'  => $this->previewFeedbackSheet($filters['year'], $filters['quarter']),
            default     => [],
        };
    }

    private function previewOfficeSheet(int $year, ?int $quarter): array
    {
        $office = Office::active()->with('services')->first();
        if (!$office) return ['headers' => [], 'rows' => []];

        $services = $office->services;
        $headers  = ['Category', 'Item', ...$services->pluck('name')->map(fn($n) => \Str::limit($n, 20))->toArray()];

        $rows = [];
        foreach ($services as $service) {
            $r = RS::query($year, $quarter, $office->id)
                ->where('service_id', $service->id)
                ->get();

            $rating = RS::computeNumericalRating($r);

            $rows[] = [
                'category' => 'Total',
                'item'     => 'Respondents',
                'values'   => [$r->count()],
                'type'     => 'data',
            ];
            $rows[] = [
                'category' => 'Rating',
                'item'     => 'Numerical',
                'values'   => [$rating ? number_format($rating, 2) : 'N/A'],
                'type'     => 'rating',
            ];
            $rows[] = [
                'category' => 'Rating',
                'item'     => 'Adjectival',
                'values'   => [RS::adjectivalRating($rating)],
                'type'     => 'adjectival',
            ];
        }

        return ['office' => $office->name, 'headers' => $headers, 'rows' => $rows, 'services' => $services];
    }

    private function previewQuarterlySheet(int $year): array
    {
        $offices = Office::active()->with('services')->orderBy('name')->get();
        $rows    = [];

        foreach ($offices as $office) {
            $qData = [];
            $total = 0;

            for ($q = 1; $q <= 4; $q++) {
                $count   = RS::query($year, $q, $office->id)->count();
                $qData[] = $count;
                $total  += $count;
            }

            $rows[] = [
                'office' => $office->name,
                'q1'     => $qData[0],
                'q2'     => $qData[1],
                'q3'     => $qData[2],
                'q4'     => $qData[3],
                'total'  => $total,
            ];
        }

        return ['rows' => $rows];
    }

    private function previewQuarterSheet(int $year, int $quarter): array
    {
        $offices = Office::active()->orderBy('name')->get();
        $rows    = [];

        foreach ($offices as $office) {
            $responses = RS::query($year, $quarter, $office->id)->get();
            $rating    = RS::computeNumericalRating($responses);

            $rows[] = [
                'office'     => $office->name,
                'total'      => $responses->count(),
                'numerical'  => $rating ? number_format($rating, 2) : 'N/A',
                'adjectival' => RS::adjectivalRating($rating),
            ];
        }

        // Overall
        $all     = RS::query($year, $quarter)->get();
        $overall = RS::computeNumericalRating($all);
        $rows[] = [
            'office'     => 'OVERALL',
            'total'      => $all->count(),
            'numerical'  => $overall ? number_format($overall, 2) : 'N/A',
            'adjectival' => RS::adjectivalRating($overall),
            'is_total'   => true,
        ];

        return ['quarter' => $quarter, 'rows' => $rows];
    }

    private function previewFeedbackSheet(int $year, ?int $quarter): array
    {
        $offices = Office::active()->orderBy('name')->get();
        $rows    = [];

        foreach ($offices as $office) {
            $suggestions = RS::query($year, $quarter, $office->id)
                ->whereNotNull('suggestion')
                ->where('suggestion', '!=', '')
                ->limit(3)
                ->pluck('suggestion')
                ->toArray();

            $rows[] = [
                'office'      => $office->name,
                'count'       => count($suggestions),
                'suggestions' => $suggestions,
            ];
        }

        return ['rows' => $rows];
    }

    // ══════════════════════════════════════════════════════
    // DOWNLOAD
    // ══════════════════════════════════════════════════════

    public function download(): BinaryFileResponse
    {
        $filters = $this->getFilters();

        $label    = $filters['quarter'] ? '_Q' . $filters['quarter'] : '_Full_Year';
        $filename = "CSM_Consolidated_Report_FY{$filters['year']}{$label}.xlsx";

        Notification::make()
            ->title('Generating report, please wait...')
            ->info()
            ->send();

        return Excel::download(
            new ConsolidatedReportExport($filters['year'], $filters['quarter']),
            $filename
        );
    }
}