<?php

namespace App\Filament\Resources\PageSettings\Tables;

use App\Filament\Support\ContentLabels;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PageSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('page_slug')->label('Pagina')
                    ->searchable()->wrap()
                    ->formatStateUsing(fn (string $state) => ContentLabels::PAGES[$state] ?? $state)
                    ->description(fn ($record) => ContentLabels::mobileSummary(strip_tags($record->hero_title ?? ''))),
                TextColumn::make('hero_title')->label('Titolo della testata')
                    ->searchable()->wrap()->visibleFrom('md')
                    ->formatStateUsing(fn (string $state) => strip_tags($state)),
                TextColumn::make('hero_title_en')->label('Titolo della testata (EN)')
                    ->searchable()->wrap()->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn (string $state) => strip_tags($state)),
                TextColumn::make('hero_subtitle')->label('Sottotitolo')
                    ->searchable()->wrap()->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn (string $state) => strip_tags($state)),
                TextColumn::make('hero_subtitle_en')->label('Sottotitolo (EN)')
                    ->searchable()->wrap()->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn (string $state) => strip_tags($state)),
                ImageColumn::make('heroMedia.url')->label('Immagine della testata')->square()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('intro_title')->label('Titolo introduttivo')
                    ->searchable()->wrap()->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn (string $state) => strip_tags($state)),
                TextColumn::make('intro_title_en')->label('Titolo introduttivo (EN)')
                    ->searchable()->wrap()->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn (string $state) => strip_tags($state)),
                TextColumn::make('translation_status')->label('Stato Traduzione')
                    ->badge()->visibleFrom('md')
                    ->formatStateUsing(fn (string $state) => ContentLabels::TRANSLATIONS[$state] ?? $state),

                TextColumn::make('created_at')->label('Creato il')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Aggiornato il')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ])->label('Azioni'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
