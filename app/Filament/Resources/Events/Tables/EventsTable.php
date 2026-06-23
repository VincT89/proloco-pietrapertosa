<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventsTable
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
                TextColumn::make('start_date')->label('Data Inizio')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_date')->label('Data Fine')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('location')->label('Luogo')
                    ->searchable(),
                TextColumn::make('location_en')->label('Luogo (EN)')
                    ->searchable(),
                TextColumn::make('category')->label('Categoria')
                    ->searchable(),
                TextColumn::make('category_en')->label('Categoria (EN)')
                    ->searchable(),
                ImageColumn::make('cover_preview')
                    ->label('Copertina')
                    ->getStateUsing(fn ($record) => $record->cover ? $record->cover->optimizedUrl('thumb') : null),
                TextColumn::make('status')->label('Stato')
                    ->badge(),
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
                \Filament\Tables\Filters\SelectFilter::make('status')->label('Stato')->options([
                    'draft' => 'Bozza',
                    'published' => 'Pubblicato',
                    'cancelled' => 'Annullato',
                ]),
                \Filament\Tables\Filters\SelectFilter::make('translation_status')->label('Traduzione')->options([
                    'draft' => 'Bozza',
                    'missing' => 'Mancante',
                    'reviewed' => 'Revisionato',
                ]),
                \Filament\Tables\Filters\Filter::make('start_date')->form([
                    \Filament\Forms\Components\DatePicker::make('date_from')->label('Dal'),
                    \Filament\Forms\Components\DatePicker::make('date_until')->label('Al'),
                ])->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                    return $query
                        ->when($data['date_from'], fn ($q, $date) => $q->whereDate('start_date', '>=', $date))
                        ->when($data['date_until'], fn ($q, $date) => $q->whereDate('start_date', '<=', $date));
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
