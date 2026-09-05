<?php

namespace App\Filament\Resources\FinancialDocuments\Schemas;

use App\Enums\DocumentType;
use App\Filament\Components\MediaPicker;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class FinancialDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Translations')
                    ->tabs([
                        Tab::make('Italiano')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Titolo')
                                    ->required(),
                                Textarea::make('description')
                                    ->label('Descrizione')
                                    ->default(null)
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('English')
                            ->schema([
                                TextInput::make('title_en')
                                    ->label('Title (EN)')
                                    ->default(null)
                                    ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('title_en', $get('title')))),
                                Textarea::make('description_en')
                                    ->label('Description (EN)')
                                    ->default(null)
                                    ->columnSpanFull()
                                    ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('description_en', $get('description')))),
                            ]),
                    ])->columnSpanFull(),

                Grid::make(2)->columnSpanFull()
                    ->schema([
                        TextInput::make('year')
                            ->label('Anno')
                            ->numeric()
                            ->required()
                            ->default(date('Y')),
                        Select::make('type')
                            ->label('Tipo Documento')
                            ->options(DocumentType::class)
                            ->required(),
                        MediaPicker::make('media_id')
                            ->label('File PDF')->acceptedFiles(['application/pdf'])->required(),
                        Toggle::make('is_published')
                            ->label('Pubblicato')
                            ->default(true)
                            ->required()
                            ->columnSpanFull(),
                        DateTimePicker::make('published_at')
                            ->label('Data di pubblicazione'),
                        TextInput::make('sort_order')
                            ->label('Ordine')
                            ->numeric()
                            ->required()
                            ->default(0),
                    ]),
            ]);
    }
}
