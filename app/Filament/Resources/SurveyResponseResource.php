<?php

namespace App\Filament\Resources;

use App\Filament\Exports\SurveyResponseExporter;
use App\Filament\Resources\SurveyResponseResource\Pages;
use App\Models\SurveyResponse;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SurveyResponseResource extends Resource
{
    protected static ?string $model = SurveyResponse::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Responses';

    protected static string | \UnitEnum | null $navigationGroup = 'Survey Results';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'id';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->width(60),

                Tables\Columns\TextColumn::make('office.display_name')
                    ->label('Office')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('age')
                    ->label('Age')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('gender')
                    ->label('Gender')
                    ->badge()
                    ->color(fn ($state) => $state === 'Male' ? 'info' : 'danger')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('email_address')
                    ->label('Email')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\TextColumn::make('complete_name')
                    ->label('Name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Business' => 'warning',
                        'Citizen' => 'success',
                        'Government' => 'info',
                        default => 'gray',
                    })
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('cc1')
                    ->label('CC1')
                    ->formatStateUsing(fn ($state) => SurveyResponse::cc1Labels()[$state] ?? '')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('cc2')
                    ->label('CC2')
                    ->formatStateUsing(fn ($state) => SurveyResponse::cc2Labels()[$state] ?? '')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('cc3')
                    ->label('CC3')
                    ->formatStateUsing(fn ($state) => SurveyResponse::cc3Labels()[$state] ?? '')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('average_sqd')
                    ->label('Avg SQD')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('is_complete')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'warning')
                    ->formatStateUsing(fn ($state) => $state ? 'Complete' : 'Partial')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M d, Y g:i A')
                    ->sortable()
                    ->toggleable(),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('office_id')
                    ->label('Office')
                    ->relationship('office', 'name')
                    ->searchable()
                    ->native(false),

                Tables\Filters\SelectFilter::make('customer_type')
                    ->label('Customer Type')
                    ->options([
                        'Business' => 'Business',
                        'Citizen' => 'Citizen',
                        'Government' => 'Government',
                    ])
                    ->native(false),

                Tables\Filters\SelectFilter::make('gender')
                    ->label('Gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ])
                    ->native(false),

                Tables\Filters\TernaryFilter::make('is_complete')
                    ->label('Status')
                    ->trueLabel('Complete only')
                    ->falseLabel('Partial only')
                    ->native(false),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label('From'),
                        \Filament\Forms\Components\DatePicker::make('until')
                            ->label('Until'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                        ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
                    ),
            ])

            ->actions([
                Actions\ViewAction::make(),
                Actions\DeleteAction::make(),
            ])

            ->headerActions([
                ExportAction::make()
                    ->exporter(SurveyResponseExporter::class)
                    ->label('Export')
                    ->icon('heroicon-o-arrow-down-tray'),
            ])

            ->bulkActions([
                ExportBulkAction::make()
                    ->exporter(SurveyResponseExporter::class)
                    ->label('Export Selected'),
                Actions\DeleteBulkAction::make(),
            ])

            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveyResponses::route('/'),
            'view' => Pages\ViewSurveyResponse::route('/{record}'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['id'];
    }
}
