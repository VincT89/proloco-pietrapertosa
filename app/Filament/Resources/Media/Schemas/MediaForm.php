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
                Select::make('type')
                    ->label('Tipo Media')
                    ->options([
                        'image' => 'Immagine',
                        'video' => 'Video',
                    ])
                    ->default('image')
                    ->live()
                    ->required(),

                FileUpload::make('url')
                    ->label('File')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'video/mp4', 'video/quicktime', 'video/webm'])
                    ->required()
                    ->getUploadedFileUsing(function (string $file): ?array {
                        return [
                            'name' => basename($file),
                            'size' => 0,
                            'type' => 'application/octet-stream',
                            'url' => $file,
                        ];
                    })
                    ->saveUploadedFileUsing(function (UploadedFile $file) {
                        $cloudinary = app(CloudinaryService::class);
                        $upload = $cloudinary->uploadMedia($file);

                        Cache::put('last_upload_'.$upload['url'], [
                            'public_id' => $upload['public_id'],
                            'resource_type' => $upload['resource_type'],
                        ], 300);

                        return $upload['url'];
                    })
                    ->deleteUploadedFileUsing(function (string $file) {
                        $cloudinary = app(CloudinaryService::class);
                        $cloudinary->cleanupTemporaryUpload($file);
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
