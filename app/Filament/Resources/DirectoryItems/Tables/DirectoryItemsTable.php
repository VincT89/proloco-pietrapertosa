<?php

namespace App\Filament\Resources\DirectoryItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DirectoryItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category')->label('Categoria')
                    ->searchable(),
                TextColumn::make('title')->label('Titolo')
                    ->searchable(),
                TextColumn::make('title_en')->label('Titolo (EN)')
                    ->searchable(),
                TextColumn::make('subtitle')->label('Sottotitolo')
                    ->searchable(),
                TextColumn::make('subtitle_en')->label('Sottotitolo (EN)')
                    ->searchable(),
                TextColumn::make('sort_order')->label('Ordine')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('translation_status')->label('Stato Traduzione')
                    ->badge(),
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
                \Filament\Tables\Filters\SelectFilter::make('category')
                    ->label('Filtra per Categoria')
                    ->options([
                        'comunita' => 'Comunità',
                        'territorio_aziende' => 'Territorio - Aziende',
                        'territorio_foodtruck' => 'Territorio - Food Truck',
                        'territorio_artigiani' => 'Territorio - Artigiani',
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
