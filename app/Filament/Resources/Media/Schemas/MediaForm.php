<?php

namespace App\Filament\Resources\Media\Schemas;

use App\Services\MediaUsage;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('original_name')->label('Nome file originale')->disabled()->dehydrated(false),
                Hidden::make('public_id'),
                Hidden::make('resource_type')->default('image'),
                Hidden::make('provider')->default('cloudinary'),
                Hidden::make('metadata'),
                Select::make('type')
                    ->label('Tipo Media')
                    ->options([
                        'image' => 'Immagine',
                        'video' => 'Video',
                        'document' => 'Documento',
                    ])
                    ->default('image')
                    ->disabled()->dehydrated(false),

                Placeholder::make('url_display')
                    ->label('File Attuale')
                    ->content(fn ($record) => view('filament.components.media-details', ['item' => $record])),
                TextInput::make('alt')->label('Testo Alternativo')
                    ->default(null),
                TextInput::make('caption')->label('Didascalia')
                    ->default(null),
                Placeholder::make('usage')->label('Utilizzato in')
                    ->content(fn ($record) => view('filament.components.media-usage', [
                        'uses' => $record ? (app(MediaUsage::class)->forMedia(collect([$record]))[$record->id] ?? []) : [],
                    ])),
                TextInput::make('folder')->label('Cartella')
                    ->default(null),
            ]);
    }
}
