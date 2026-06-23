<?php

namespace App\Filament\Resources\FinancialDocuments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FinancialDocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('title')->label('Titolo')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('year')->label('Anno')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('type')->label('Tipo')
                    ->badge(),
                \Filament\Tables\Columns\IconColumn::make('is_published')->label('Pubblicato')
                    ->boolean(),
                \Filament\Tables\Columns\TextColumn::make('sort_order')->label('Ordine')
                    ->numeric()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('published_at')->label('Data Pubblicazione')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                \Filament\Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('year')->label('Anno')
                    ->options(function () {
                        return \App\Models\FinancialDocument::pluck('year', 'year')->toArray();
                    }),
                \Filament\Tables\Filters\SelectFilter::make('type')->label('Tipo')
                    ->options(\App\Enums\DocumentType::class),
                \Filament\Tables\Filters\TernaryFilter::make('is_published')->label('Pubblicato'),
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
