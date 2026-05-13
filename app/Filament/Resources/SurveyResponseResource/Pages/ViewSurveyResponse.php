<?php

namespace App\Filament\Resources\SurveyResponseResource\Pages;

use App\Filament\Resources\SurveyResponseResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewSurveyResponse extends ViewRecord
{
    protected static string $resource = SurveyResponseResource::class;

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Client Information')
                    ->icon('heroicon-o-user')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('office.display_name')
                            ->label('Office'),
                        \Filament\Infolists\Components\TextEntry::make('service.name')
                            ->label('Service'),
                        \Filament\Infolists\Components\TextEntry::make('age'),
                        \Filament\Infolists\Components\TextEntry::make('gender'),
                        \Filament\Infolists\Components\TextEntry::make('customer_type')
                            ->label('Customer Type'),
                    ])
                    ->columns(2),

                Section::make("Citizen's Charter")
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('cc1')
                            ->formatStateUsing(fn ($state) => \App\Models\SurveyResponse::cc1Labels()[$state] ?? ''),
                        \Filament\Infolists\Components\TextEntry::make('cc2')
                            ->formatStateUsing(fn ($state) => \App\Models\SurveyResponse::cc2Labels()[$state] ?? ''),
                        \Filament\Infolists\Components\TextEntry::make('cc3')
                            ->formatStateUsing(fn ($state) => \App\Models\SurveyResponse::cc3Labels()[$state] ?? ''),
                    ])
                    ->columns(2),

                Section::make('Service Quality Dimension')
                    ->icon('heroicon-o-star')
                    ->schema(function ($record) {
                        $entries = [];
                        foreach (\App\Models\SurveyResponse::sqdQuestions() as $key => $question) {
                            $entries[] = \Filament\Infolists\Components\TextEntry::make($key)
                                ->label($question)
                                ->formatStateUsing(fn ($state) => \App\Models\SurveyResponse::ratingLabel($state) . " ({$state})");
                        }
                        return $entries;
                    })
                    ->columns(2),

                Section::make('Suggestion / Remarks')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('suggestion')
                            ->label(false)
                            ->placeholder('No suggestions provided.'),
                    ]),

                Section::make('Meta')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('average_sqd')
                            ->label('Average SQD'),
                        \Filament\Infolists\Components\TextEntry::make('is_complete')
                            ->label('Status')
                            ->formatStateUsing(fn ($state) => $state ? 'Complete' : 'Partial'),
                        \Filament\Infolists\Components\TextEntry::make('created_at')
                            ->label('Submitted At')
                            ->dateTime('M d, Y g:i A'),
                    ])
                    ->columns(2),
            ]);
    }
}
