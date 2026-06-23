<?php

namespace App\Filament\Resources\Media\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('preview')
                    ->label('Anteprima')
                    ->getStateUsing(function ($record) {
                        $url = $record->thumbnail_url ?: ($record->type === 'image' ? $record->optimizedUrl('small') : null);
                        return $url ? (str_starts_with($url, 'http') ? $url : asset($url)) : null;
                    }),
                TextColumn::make('type')->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'image' => 'success',
                        'video' => 'info',
                        'document' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('mime_type')->label('Mime Type')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('provider')->label('Provider')
                    ->badge()
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
                    ->searchable(),
                TextColumn::make('caption')->label('Didascalia')
                    ->searchable(),
                TextColumn::make('folder')->label('Cartella')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->label('Creato il')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')->label('Aggiornato il')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->using(function (\App\Models\Media $record, DeleteAction $action) {
                        if (! app(\App\Services\MediaManager::class)->delete($record)) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Impossibile eliminare')
                                ->body('Il media è in uso e non può essere cancellato.')
                                ->send();
                            $action->halt();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function ($records, DeleteBulkAction $action) {
                            $failed = 0;
                            foreach ($records as $record) {
                                if (! app(\App\Services\MediaManager::class)->delete($record)) {
                                    $failed++;
                                }
                            }
                            
                            if ($failed > 0) {
                                \Filament\Notifications\Notification::make()
                                    ->warning()
                                    ->title('Attenzione')
                                    ->body("$failed media non eliminati perché attualmente in uso.")
                                    ->send();
                            } else {
                                \Filament\Notifications\Notification::make()
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
