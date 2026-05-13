<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\ChartWidget;

class SqdBreakdownChart extends ChartWidget
{
    protected int | string | array $columnSpan = 4;

    protected ?string $heading = 'Average Score per SQD Question';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $questions = SurveyResponse::sqdQuestions();
        $keys = SurveyResponse::sqdKeys();
        $avgs = [];

        foreach ($keys as $key) {
            $avg = SurveyResponse::whereNotNull($key)
                ->where($key, '>', 0)
                ->avg($key);

            $avgs[] = $avg ? round((float) $avg, 2) : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Average Score',
                    'data' => $avgs,
                    'backgroundColor' => '#8b5cf6',
                ],
            ],
            'labels' => array_values($questions),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'scales' => [
                'x' => [
                    'min' => 0,
                    'max' => 5,
                ],
                'y' => [
                    'position' => 'left',
                    'offset' => true,
                    'ticks' => [
                        'crossAlign' => 'far',
                        'font' => [
                            'size' => 11,
                            'weight' => '500',
                        ],
                    ],
                ],
            ],
        ];
    }
}
