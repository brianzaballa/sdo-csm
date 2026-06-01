<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Survey Overview';

    protected ?string $description = 'Key metrics at a glance';

    protected function getStats(): array
    {
        $total = SurveyResponse::count();
        $monthly = SurveyResponse::thisMonth()->count();
        $completed = SurveyResponse::complete()->count();

        $keys = SurveyResponse::sqdKeys();
        $sumParts = collect($keys)->map(fn ($k) => "COALESCE(NULLIF($k, 0), 0)");
        $countParts = collect($keys)->map(fn ($k) => "CASE WHEN $k IS NOT NULL AND $k > 0 THEN 1 ELSE 0 END");

        $avgSqd = SurveyResponse::complete()
            ->selectRaw('AVG((' . $sumParts->join(' + ') . ') / NULLIF((' . $countParts->join(' + ') . '), 0)) as avg_sqd')
            ->value('avg_sqd');
        $avgSqd = $avgSqd ? round((float) $avgSqd, 2) : null;

        $dailyCounts = SurveyResponse::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        $sparkline = collect(range(29, 0, -1))
            ->map(fn ($d) => $dailyCounts[now()->subDays($d)->format('Y-m-d')] ?? 0)
            ->toArray();

        $lastMonth = SurveyResponse::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $monthlyDelta = $monthly - $lastMonth;
        $monthlyTrendColor = $monthlyDelta >= 0 ? 'success' : 'danger';
        $monthlyTrend = $monthlyDelta >= 0
            ? "↑ +{$monthlyDelta} vs last month"
            : "↓ {$monthlyDelta} vs last month";

        $lastMonthCompleted = SurveyResponse::complete()
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $lastMonthRate = $lastMonth > 0 ? round(($lastMonthCompleted / $lastMonth) * 100) : 0;
        $currentRate = $total > 0 ? round(($completed / $total) * 100) : 0;

        $avgDuration = SurveyResponse::complete()
            ->whereNotNull('duration_seconds')
            ->avg('duration_seconds');
        $avgDuration = $avgDuration ? round((float) $avgDuration) : null;
        $avgDurationLabel = $avgDuration
            ? sprintf('%d:%02d', intdiv($avgDuration, 60), $avgDuration % 60)
            : '—';

        return [
            Stat::make('Total Responses', number_format($total))
                ->description('All time')
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->chart($sparkline)
                ->chartColor('info')
                ->color('info'),

            Stat::make('This Month', number_format($monthly))
                ->description($monthlyTrend)
                ->descriptionIcon($monthlyDelta >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->chart($sparkline)
                ->chartColor('warning')
                ->color($monthlyTrendColor),

            Stat::make('Avg Satisfaction', $avgSqd ? number_format($avgSqd, 2) : '—')
                ->description('Across all complete responses')
                ->descriptionIcon('heroicon-o-star')
                ->color('success'),

            Stat::make('Avg Fill Time', $avgDurationLabel)
                ->description($avgDuration ? 'Across complete responses' : 'No data yet')
                ->descriptionIcon('heroicon-o-clock')
                ->color('info'),

            Stat::make('Completion Rate', $currentRate . '%')
                ->description($completed . ' of ' . $total . ' complete')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('primary'),
        ];
    }
}
