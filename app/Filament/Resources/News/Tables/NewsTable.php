<?php

namespace App\Filament\Resources\News\Tables;

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

class NewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                TextColumn::make('title')->label('Titolo')
                    ->searchable()->wrap()
                    ->description(fn ($record) => ContentLabels::mobileSummary(
                        ($record->published_at?->format('d/m/Y') ?? 'Data non impostata').' · '.ContentLabels::status($record)
                    )),
                TextColumn::make('title_en')->label('Titolo (EN)')
                    ->searchable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('slug')->label('Slug')
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
                TextColumn::make('published_at')->label('Data di Pubblicazione')
                    ->dateTime('d/m/Y H:i')->visibleFrom('md')
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
                SelectFilter::make('status')->label('Stato')->options([
                    'draft' => 'Bozza',
                    'published' => 'Pubblicato',
                    'archived' => 'Archiviato',
                    'scheduled' => 'Programmata',
                ])->query(function ($query, array $data) {
                    return match ($data['value'] ?? null) {
                        'published' => $query->visibleToPublic(),
                        'scheduled' => $query->where('status', 'published')->where('published_at', '>', now()),
                        'draft', 'archived' => $query->where('status', $data['value']),
                        default => $query,
                    };
                }),
                SelectFilter::make('translation_status')->label('Traduzione')->options([
                    'draft' => 'Bozza',
                    'missing' => 'Mancante',
                    'reviewed' => 'Revisionato',
                ]),
                Filter::make('published_at')->form([
                    DatePicker::make('published_from')->label('Dal'),
                    DatePicker::make('published_until')->label('Al'),
                ])->query(function (Builder $query, array $data): Builder {
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
                    BulkAction::make('publish')->label('Pubblica o programma')
                        ->tooltip('Le date future saranno rispettate; le altre notizie saranno pubblicate subito.')
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
