<?php

namespace App\Filament\Components;

use Illuminate\Support\HtmlString;

class MediaUpload
{
    public static function make(
        string $name,
        string $collection = 'gallery',
        array $acceptedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'video/mp4', 'video/quicktime', 'video/webm'],
        int $maxSize = 102400,
        ?HtmlString $helperText = null
    ): MediaPicker {
        return MediaPicker::make($name)->acceptedFiles($acceptedTypes, $maxSize)
            ->collection($collection)->helperText($helperText);
    }
}
