<?php

namespace App\Filament\Resources\DirectoryItems\Tables;

use App\Filament\Support\ContentLabels;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DirectoryItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('title')->label('Titolo')
                    ->searchable()->wrap()
                    ->description(fn ($record) => ContentLabels::mobileSummary(
                        (ContentLabels::CATEGORIES[$record->category] ?? $record->category).' · Ordine '.$record->sort_order
                    )),
                TextColumn::make('category')->label('Sezione')
                    ->searchable()->wrap()->visibleFrom('md')
                    ->formatStateUsing(fn (string $state) => ContentLabels::CATEGORIES[$state] ?? $state),
                TextColumn::make('title_en')->label('Titolo (EN)')
                    ->searchable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('subtitle')->label('Sottotitolo')
                    ->searchable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('subtitle_en')->label('Sottotitolo (EN)')
                    ->searchable()->wrap()->toggleable(isToggledHiddenByDefault: true),
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
                SelectFilter::make('category')
                    ->label('Filtra per Categoria')
                    ->options([
                        'comunita' => 'Comunità',
                        'eccellenze_aziende' => 'Eccellenze - Aziende',
                        'eccellenze_foodtruck' => 'Eccellenze - Food Truck',
                        'eccellenze_artigiani' => 'Eccellenze - Artigiani',
                        'sapori_piatti' => 'Sapori - Piatti',
                        'eventi_annuali' => 'Eventi Annuali',
                        'scopri_luoghi' => 'Scopri - Luoghi',

                    ]),
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
