<?php

namespace App\Filament\Resources\PageSettings\Tables;

use Filament\Actions\BulkActionGroup;
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
                TextColumn::make('page_slug')->label('Slug Pagina')
                    ->searchable(),
                TextColumn::make('hero_title')->label('Titolo Hero')
                    ->searchable(),
                TextColumn::make('hero_title_en')->label('Titolo Hero (EN)')
                    ->searchable(),
                TextColumn::make('hero_subtitle')->label('Sottotitolo Hero')
                    ->searchable(),
                TextColumn::make('hero_subtitle_en')->label('Sottotitolo Hero (EN)')
                    ->searchable(),
                ImageColumn::make('heroMedia.url')->label('Immagine Hero')->square(),
                TextColumn::make('intro_title')->label('Titolo Intro')
                    ->searchable(),
                TextColumn::make('intro_title_en')->label('Titolo Intro (EN)')
                    ->searchable(),
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
                //
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
