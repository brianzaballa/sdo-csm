<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\ChartWidget;

class CustomerTypeDistribution extends ChartWidget
{
    protected int | string | array $columnSpan = 2;

    protected ?string $heading = 'Customer Type Distribution';

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        $data = SurveyResponse::selectRaw('customer_type, count(*) as count')
            ->whereNotNull('customer_type')
            ->groupBy('customer_type')
            ->get();

        $colors = [
            'Business' => '#f59e0b',
            'Citizen' => '#10b981',
            'Government' => '#3b82f6',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Customers',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => $data->pluck('customer_type')->map(fn ($t) => $colors[$t] ?? '#6b7280')->toArray(),
                ],
            ],
            'labels' => $data->pluck('customer_type')->toArray(),
        ];
    }
}
