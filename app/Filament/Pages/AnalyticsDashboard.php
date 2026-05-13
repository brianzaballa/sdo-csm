<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CustomerTypeDistribution;
use App\Filament\Widgets\ResponsesPerOfficeChart;
use App\Filament\Widgets\SatisfactionTrendChart;
use App\Filament\Widgets\SqdBreakdownChart;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class AnalyticsDashboard extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $navigationLabel = 'Analytics';

    protected static string | \UnitEnum | null $navigationGroup = 'Survey Results';

    protected static ?int $navigationSort = 1;

    protected static string $routePath = '/';

    protected ?string $heading = 'Analytics Dashboard';

    protected ?string $subheading = 'Overview of survey responses and satisfaction metrics';

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            ResponsesPerOfficeChart::class,
            SatisfactionTrendChart::class,
            CustomerTypeDistribution::class,
            SqdBreakdownChart::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 6;
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->columns(6)
                    ->schema($this->getWidgetsSchemaComponents($this->getWidgets())),
            ]);
    }
}
