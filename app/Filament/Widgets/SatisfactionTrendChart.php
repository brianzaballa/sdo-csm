<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SatisfactionTrendChart extends ChartWidget
{
    protected int | string | array $columnSpan = 3;

    protected ?string $heading = 'Monthly Satisfaction Trend';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $driver = DB::connection()->getDriverName();
        $dateExpr = $driver === 'mysql'
            ? "DATE_FORMAT(created_at, '%Y-%m')"
            : "strftime('%Y-%m', created_at)";

        $months = SurveyResponse::selectRaw("{$dateExpr} as month, avg(sqd0) as avg_sqd")
            ->whereNotNull('sqd0')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Avg SQD',
                    'data' => $months->pluck('avg_sqd')->map(fn ($v) => round((float) $v, 2))->toArray(),
                    'borderColor' => '#14b8a6',
                    'backgroundColor' => 'rgba(20, 184, 166, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $months->pluck('month')->toArray(),
        ];
    }
}
