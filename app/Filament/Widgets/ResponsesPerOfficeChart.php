<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\ChartWidget;

class ResponsesPerOfficeChart extends ChartWidget
{
    protected int | string | array $columnSpan = 3;

    protected ?string $heading = 'Responses per Office';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $data = SurveyResponse::selectRaw('office_id, count(*) as count')
            ->with('office')
            ->groupBy('office_id')
            ->get()
            ->sortByDesc('count');

        return [
            'datasets' => [
                [
                    'label' => 'Responses',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => '#14b8a6',
                ],
            ],
            'labels' => $data->pluck('office.display_name')->toArray(),
        ];
    }
}
