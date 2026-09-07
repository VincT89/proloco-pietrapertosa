<?php

namespace App\Filament\Resources\Media\Tables;

use App\Models\Media;
use App\Services\MediaManager;
use App\Services\MediaUsage;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('original_name')->label('Nome file')
                    ->getStateUsing(fn ($record) => $record->displayName())
                    ->searchable(query: fn ($query, $search) => $query->searchLibrary($search))->wrap(),
                ImageColumn::make('preview')
                    ->label('Anteprima')
                    ->visibleFrom('md')
                    ->getStateUsing(function ($record) {
                        $url = $record->thumbnail_url ?: ($record->type === 'image' ? $record->optimizedUrl('small') : null);

                        return $url ? (str_starts_with($url, 'http') ? $url : asset($url)) : null;
                    }),
                TextColumn::make('type')->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'image' => 'Immagine', 'video' => 'Video', 'document' => 'Documento', default => $state,
                    })->toggleable(isToggledHiddenByDefault: true)
                    ->color(fn (string $state): string => match ($state) {
                        'image' => 'success',
                        'video' => 'info',
                        'document' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('metadata.mime_type')->label('Formato file')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('provider')->label('Provider')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color(fn (string $state): string => match ($state) {
                        'cloudinary' => 'primary',
                        'facebook' => 'info',
                        'instagram' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('public_id')->label('ID Pubblico')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('url')->label('URL')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('resource_type')->label('Tipo Risorsa')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('alt')->label('Testo Alternativo')
                    ->searchable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('caption')->label('Didascalia')
                    ->searchable()->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('usage')->label('Utilizzato in')
                    ->visibleFrom('md')
                    ->getStateUsing(function ($record, $livewire) {
                        $records = $livewire->getTableRecords();
                        $records = $records instanceof Collection ? $records : $records->getCollection();

                        return app(MediaUsage::class)->forMedia($records)[$record->id] ?? [];
                    })->listWithLineBreaks()->wrap()->placeholder('Nessun utilizzo nei contenuti salvati'),
                TextColumn::make('folder')->label('Cartella')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->label('Creato il')
                    ->dateTime('d/m/Y H:i')->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('updated_at')->label('Aggiornato il')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')->label('Tipo di file')
                    ->options(['image' => 'Immagini', 'video' => 'Video', 'document' => 'Documenti']),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make()
                        ->using(function (Media $record, DeleteAction $action) {
                            if (! app(MediaManager::class)->delete($record)) {
                                Notification::make()
                                    ->danger()
                                    ->title('Impossibile eliminare')
                                    ->body('Il media è in uso e non può essere cancellato.')
                                    ->send();
                                $action->halt();
                            }
                        }),
                ])->label('Azioni'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function ($records, DeleteBulkAction $action) {
                            $failed = 0;
                            foreach ($records as $record) {
                                if (! app(MediaManager::class)->delete($record)) {
                                    $failed++;
                                }
                            }

                            if ($failed > 0) {
                                Notification::make()
                                    ->warning()
                                    ->title('Attenzione')
                                    ->body("$failed media non eliminati perché attualmente in uso.")
                                    ->send();
                            } else {
                                Notification::make()
                                    ->success()
                                    ->title('Eliminati')
                                    ->body('I media selezionati sono stati eliminati.')
                                    ->send();
                            }
                            $action->deselectRecordsAfterCompletion();
                        }),
                ]),
            ]);
    }
}
