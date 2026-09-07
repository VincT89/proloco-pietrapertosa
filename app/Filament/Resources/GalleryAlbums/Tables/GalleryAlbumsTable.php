<?php

namespace App\Filament\Resources\GalleryAlbums\Tables;

use App\Filament\Support\ContentLabels;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GalleryAlbumsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort(fn ($query) => $query->orderedForDisplay())
            ->columns([
                TextColumn::make('title')->label('Titolo')
                    ->searchable()->wrap()
                    ->description(fn ($record) => ContentLabels::mobileSummary(
                        ($record->section_date?->format('d/m/Y') ?? 'Senza data').' · Ordine '.$record->sort_order
                    )),
                TextColumn::make('title_en')->label('Titolo (EN)')
                    ->searchable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('section_date')->label('Data album')
                    ->date('d/m/Y')->visibleFrom('md')
                    ->sortable(),
                TextColumn::make('sort_order')->label('Ordine')
                    ->numeric()->visibleFrom('md')
                    ->sortable(),
                TextColumn::make('translation_status')->label('Stato Traduzione')
                    ->badge()->toggleable(isToggledHiddenByDefault: true)
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
