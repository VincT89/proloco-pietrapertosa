<?php

namespace App\Filament\Resources\News\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Titolo')
                    ->searchable(),
                TextColumn::make('title_en')->label('Titolo (EN)')
                    ->searchable(),
                TextColumn::make('slug')->label('Slug')
                    ->searchable(),
                ImageColumn::make('cover_preview')
                    ->label('Copertina')
                    ->getStateUsing(fn ($record) => $record->cover ? $record->cover->optimizedUrl('thumb') : null),
                TextColumn::make('status')->label('Stato')
                    ->badge(),
                TextColumn::make('translation_status')->label('Stato Traduzione')
                    ->badge(),
                TextColumn::make('published_at')->label('Data di Pubblicazione')
                    ->dateTime()
                    ->sortable(),
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
                \Filament\Tables\Filters\SelectFilter::make('status')->label('Stato')->options([
                    'draft' => 'Bozza',
                    'published' => 'Pubblicato',
                    'archived' => 'Archiviato',
                ]),
                \Filament\Tables\Filters\SelectFilter::make('translation_status')->label('Traduzione')->options([
                    'draft' => 'Bozza',
                    'missing' => 'Mancante',
                    'reviewed' => 'Revisionato',
                ]),
                \Filament\Tables\Filters\Filter::make('published_at')->form([
                    \Filament\Forms\Components\DatePicker::make('published_from')->label('Dal'),
                    \Filament\Forms\Components\DatePicker::make('published_until')->label('Al'),
                ])->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                    return $query
                        ->when($data['published_from'], fn ($q, $date) => $q->whereDate('published_at', '>=', $date))
                        ->when($data['published_until'], fn ($q, $date) => $q->whereDate('published_at', '<=', $date));
                }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    \Filament\Actions\BulkAction::make('publish')->label('Pubblica')
                        ->action(fn (\Illuminate\Database\Eloquent\Collection $records) => $records->each->update(['status' => 'published']))
                        ->icon('heroicon-o-check-circle')->color('success'),
                    \Filament\Actions\BulkAction::make('hide')->label('Nascondi (Bozza)')
                        ->action(fn (\Illuminate\Database\Eloquent\Collection $records) => $records->each->update(['status' => 'draft']))
                        ->icon('heroicon-o-eye-slash')->color('warning'),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
