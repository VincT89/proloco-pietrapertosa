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

                FileUpload::make('url')
                    ->label('File')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'video/mp4', 'video/quicktime', 'video/webm'])
                    ->maxSize(102400)
                    ->required()
                    ->getUploadedFileUsing(function (string $file): ?array {
                        return [
                            'name' => basename($file),
                            'size' => 0,
                            'type' => preg_match('/\.(mp4|webm|mov)$/i', $file) ? 'video/mp4' : 'application/octet-stream',
                            'url' => $file,
                        ];
                    })
                    ->saveUploadedFileUsing(function (UploadedFile $file, callable $set) {
                        $upload = app(\App\Services\CloudinaryService::class)->uploadMedia($file);
                        
                        $set('public_id', $upload['public_id']);
                        $set('resource_type', $upload['resource_type'] ?? 'image');
                        $set('provider', 'cloudinary');
                        
                        $type = ($upload['resource_type'] ?? '') === 'raw' ? 'document' : 'image';
                        if ($type === 'image' && str_starts_with($file->getMimeType(), 'video/')) {
                            $type = 'video';
                        }
                        $set('type', $type);
                        
                        $set('metadata', [
                            'original_name' => $file->getClientOriginalName(),
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                            'format' => $upload['format'] ?? $file->getClientOriginalExtension(),
                        ]);

                        return $upload['url'];
                    })
                    ->deleteUploadedFileUsing(function (string $file, callable $get) {
                        $publicId = $get('public_id');
                        $resourceType = $get('resource_type') ?? 'image';
                        if ($publicId) {
                            app(\App\Services\CloudinaryService::class)->deleteMedia($publicId, $resourceType);
                        }
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
