<?php

namespace App\Filament\Resources\Media\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
                    ->getStateUsing(fn ($record) => $record->thumbnail_url ?: ($record->type === 'image' ? $record->url : null)),
                TextColumn::make('type')->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'image' => 'primary',
                        'video' => 'success',
                        default => 'gray',
                    }),
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
