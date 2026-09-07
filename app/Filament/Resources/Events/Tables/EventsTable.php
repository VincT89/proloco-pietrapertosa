<?php

namespace App\Filament\Resources\Events\Tables;

use App\Filament\Support\ContentLabels;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('start_date', 'desc')
            ->columns([
                TextColumn::make('title')->label('Titolo')
                    ->searchable()->wrap()
                    ->description(fn ($record) => ContentLabels::mobileSummary(
                        ($record->start_date?->format('d/m/Y') ?? 'Data da definire').' · '.ContentLabels::status($record)
                    )),
                TextColumn::make('title_en')->label('Titolo (EN)')
                    ->searchable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('slug')->label('Slug')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('start_date')->label('Data Inizio')
                    ->dateTime('d/m/Y H:i')->visibleFrom('md')
                    ->sortable(),
                TextColumn::make('end_date')->label('Data Fine')
                    ->dateTime('d/m/Y H:i')->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('location')->label('Luogo')
                    ->searchable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('location_en')->label('Luogo (EN)')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('category')->label('Categoria')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('category_en')->label('Categoria (EN)')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('cover_preview')
                    ->label('Copertina')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->getStateUsing(fn ($record) => $record->cover ? $record->cover->optimizedUrl('thumb') : null),
                TextColumn::make('status')->label('Stato')
                    ->badge()->visibleFrom('md')
                    ->formatStateUsing(fn ($record) => ContentLabels::status($record))
                    ->color(fn ($record) => ContentLabels::statusColor($record)),
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
                SelectFilter::make('status')->label('Stato')->options([
                    'draft' => 'Bozza',
                    'published' => 'Pubblicato',
                    'cancelled' => 'Annullato',
                ]),
                SelectFilter::make('translation_status')->label('Traduzione')->options([
                    'draft' => 'Bozza',
                    'missing' => 'Mancante',
                    'reviewed' => 'Revisionato',
                ]),
                Filter::make('start_date')->form([
                    DatePicker::make('date_from')->label('Dal'),
                    DatePicker::make('date_until')->label('Al'),
                ])->query(function (Builder $query, array $data): Builder {
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
                    BulkAction::make('publish')->label('Pubblica')
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'published']))
                        ->icon('heroicon-o-check-circle')->color('success'),
                    BulkAction::make('hide')->label('Nascondi (Bozza)')
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'draft']))
                        ->icon('heroicon-o-eye-slash')->color('warning'),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
