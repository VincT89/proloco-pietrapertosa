<?php

namespace App\Filament\Components;

use App\Models\Media;
use App\Services\MediaManager;
use Filament\Forms\Components\FileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;

class MediaUpload
{
    public static function make(
        string $name, 
        string $collection = 'gallery', 
        array $acceptedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'video/mp4', 'video/quicktime', 'video/webm']
    ): FileUpload {
        return FileUpload::make($name)
            ->multiple()
            ->acceptedFileTypes($acceptedTypes)
            ->getUploadedFileUsing(function (string $file): ?array {
                return [
                    'name' => basename($file),
                    'size' => 0,
                    'type' => preg_match('/\.(mp4|webm|mov)$/i', $file) ? 'video/mp4' : 'application/octet-stream',
                    'url' => $file,
                ];
            })
            ->saveUploadedFileUsing(function (UploadedFile $file) {
                $manager = app(MediaManager::class);
                $media = $manager->upload($file);
                
                Cache::put('last_upload_'.$media->url, $media->id, 300);

                return $media->url;
            })
            ->deleteUploadedFileUsing(function (string $file) {
                $mediaId = Cache::get('last_upload_'.$file);
                if ($mediaId) {
                    $media = Media::find($mediaId);
                    if ($media) {
                        app(MediaManager::class)->delete($media);
                    }
                    Cache::forget('last_upload_'.$file);
                } else {
                    $media = Media::where('url', $file)->first();
                    if ($media) {
                        app(MediaManager::class)->delete($media);
                    }
                }
            })
            ->afterStateHydrated(function ($component, $record) use ($collection) {
                if ($record) {
                    $relation = $collection . 'Media';
                    if (method_exists($record, $relation)) {
                        $component->state($record->$relation->pluck('url')->toArray());
                    }
                }
            })
            ->dehydrated(false)
            ->saveRelationshipsUsing(function ($record, $state) use ($collection) {
                $syncData = [];
                if (is_array($state)) {
                    foreach (array_values($state) as $index => $url) {
                        $media = Media::where('url', $url)->first();
                        if ($media) {
                            $syncData[$media->id] = ['order' => $index, 'collection' => $collection];
                        }
                    }
                }
                $relation = $collection . 'Media';
                if (method_exists($record, $relation)) {
                    $record->$relation()->sync($syncData);
                }
            });
    }
}
