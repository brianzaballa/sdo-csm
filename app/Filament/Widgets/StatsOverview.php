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
        $avgSqd = SurveyResponse::complete()
            ->get()
            ->avg(fn ($r) => $r->average_sqd);

        return [
            Stat::make('Total Responses', number_format($total))
                ->description('All time')
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->chartColor('info')
                ->color('info'),

            Stat::make('This Month', number_format($monthly))
                ->description('Current month')
                ->descriptionIcon('heroicon-o-calendar')
                ->chartColor('warning')
                ->color('warning'),

            Stat::make('Avg Satisfaction', $avgSqd ? number_format($avgSqd, 2) : '—')
                ->description('Across all complete responses')
                ->descriptionIcon('heroicon-o-star')
                ->chartColor('success')
                ->color('success'),

            Stat::make('Completion Rate', $total > 0 ? round(($completed / $total) * 100) . '%' : '0%')
                ->description($completed . ' of ' . $total . ' complete')
                ->descriptionIcon('heroicon-o-check-circle')
                ->chartColor('primary')
                ->color('primary'),
        ];
    }
}
