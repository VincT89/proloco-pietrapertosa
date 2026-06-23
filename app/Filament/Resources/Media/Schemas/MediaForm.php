<?php

namespace App\Filament\Resources\Media\Schemas;

use App\Services\CloudinaryService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Closure;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('public_id'),
                Hidden::make('resource_type')->default('image'),
                Hidden::make('provider')->default('cloudinary'),
                Hidden::make('metadata'),
                Select::make('type')
                    ->label('Tipo Media')
                    ->options([
                        'image' => 'Immagine',
                        'video' => 'Video',
                    ])
                    ->default('image')
                    ->live()
                    ->required(),

                \Filament\Forms\Components\Placeholder::make('url_display')
                    ->label('File Attuale')
                    ->content(function ($record) {
                        if (!$record) return '-';
                        if ($record->type === 'video') {
                            return new \Illuminate\Support\HtmlString('<video src="'.$record->optimizedVideoUrl().'" controls style="max-height: 200px; border-radius: 8px;"></video>');
                        }
                        return new \Illuminate\Support\HtmlString('<img src="'.$record->optimizedUrl('card').'" style="max-height: 200px; border-radius: 8px;" />');
                    }),
                TextInput::make('alt')->label('Testo Alternativo')
                    ->default(null),
                TextInput::make('caption')->label('Didascalia')
                    ->default(null),
                TextInput::make('folder')->label('Cartella')
                    ->default(null),
            ]);
    }
}
