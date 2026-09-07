<?php

namespace App\Filament\Resources\FinancialDocuments\Tables;

use App\Enums\DocumentType;
use App\Filament\Support\ContentLabels;
use App\Models\FinancialDocument;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class FinancialDocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Titolo')
                    ->searchable()->wrap()
                    ->description(fn ($record) => ContentLabels::mobileSummary(
                        $record->year.' · '.$record->type->getLabel().' · '.($record->is_published ? 'Pubblicato' : 'Bozza')
                    )),
                TextColumn::make('year')->label('Anno')
                    ->visibleFrom('md')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')->label('Tipo')
                    ->badge()->visibleFrom('md')->wrap(),
                IconColumn::make('is_published')->label('Pubblicato')
                    ->boolean()->visibleFrom('md'),
                TextColumn::make('sort_order')->label('Ordine')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('published_at')->label('Data Pubblicazione')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('year')->label('Anno')
                    ->options(function () {
                        return FinancialDocument::pluck('year', 'year')->toArray();
                    }),
                SelectFilter::make('type')->label('Tipo')
                    ->options(DocumentType::class),
                TernaryFilter::make('is_published')->label('Pubblicato'),
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
