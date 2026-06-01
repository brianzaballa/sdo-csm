<?php

namespace App\Filament\Exports;

use App\Models\SurveyResponse;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class SurveyResponseExporter extends Exporter
{
    protected static ?string $model = SurveyResponse::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('office.name')
                ->label('Office'),

            ExportColumn::make('service.name')
                ->label('Service'),

            ExportColumn::make('age')
                ->label('Age'),

            ExportColumn::make('gender')
                ->label('Gender'),

            ExportColumn::make('customer_type')
                ->label('Customer Type'),

            ExportColumn::make('cc1')
                ->label('CC1')
                ->formatStateUsing(fn ($state) => SurveyResponse::cc1Labels()[$state] ?? $state),

            ExportColumn::make('cc2')
                ->label('CC2')
                ->formatStateUsing(fn ($state) => SurveyResponse::cc2Labels()[$state] ?? $state),

            ExportColumn::make('cc3')
                ->label('CC3')
                ->formatStateUsing(fn ($state) => SurveyResponse::cc3Labels()[$state] ?? $state),

            ExportColumn::make('sqd0')
                ->label('SQD0')
                ->formatStateUsing(fn ($state) => SurveyResponse::ratingLabel($state)),

            ExportColumn::make('sqd1')
                ->label('SQD1')
                ->formatStateUsing(fn ($state) => SurveyResponse::ratingLabel($state)),

            ExportColumn::make('sqd2')
                ->label('SQD2')
                ->formatStateUsing(fn ($state) => SurveyResponse::ratingLabel($state)),

            ExportColumn::make('sqd3')
                ->label('SQD3')
                ->formatStateUsing(fn ($state) => SurveyResponse::ratingLabel($state)),

            ExportColumn::make('sqd4')
                ->label('SQD4')
                ->formatStateUsing(fn ($state) => SurveyResponse::ratingLabel($state)),

            ExportColumn::make('sqd5')
                ->label('SQD5')
                ->formatStateUsing(fn ($state) => SurveyResponse::ratingLabel($state)),

            ExportColumn::make('sqd6')
                ->label('SQD6')
                ->formatStateUsing(fn ($state) => SurveyResponse::ratingLabel($state)),

            ExportColumn::make('sqd7')
                ->label('SQD7')
                ->formatStateUsing(fn ($state) => SurveyResponse::ratingLabel($state)),

            ExportColumn::make('sqd8')
                ->label('SQD8')
                ->formatStateUsing(fn ($state) => SurveyResponse::ratingLabel($state)),

            ExportColumn::make('average_sqd')
                ->label('Avg SQD')
                ->formatStateUsing(fn ($record) => $record->average_sqd),

            ExportColumn::make('suggestion')
                ->label('Suggestion'),

            ExportColumn::make('is_complete')
                ->label('Completed')
                ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),

            ExportColumn::make('is_flagged')
                ->label('Duplicate')
                ->formatStateUsing(fn ($state) => $state ? 'Possible Duplicate' : ''),

            ExportColumn::make('started_at')
                ->label('Started At')
                ->formatStateUsing(fn ($state) => $state?->format('M d, Y g:i A')),

            ExportColumn::make('ended_at')
                ->label('Ended At')
                ->formatStateUsing(fn ($state) => $state?->format('M d, Y g:i A')),

            ExportColumn::make('duration_seconds')
                ->label('Duration (seconds)'),

            ExportColumn::make('created_at')
                ->label('Submitted At')
                ->formatStateUsing(fn ($state) => $state?->format('M d, Y g:i A')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your survey response export has completed and ' . number_format($export->successful_rows) . ' rows exported.';

        if ($failedRows = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRows) . ' rows failed to export.';
        }

        return $body;
    }
}
