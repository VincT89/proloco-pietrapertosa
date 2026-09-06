<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class MediaUploadRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile) {
            return;
        }

        $path = $value->getRealPath();
        if (! $path || ! is_file($path) || ! is_readable($path)) {
            $fail('Il file temporaneo non è più disponibile. Rimuovilo e selezionalo di nuovo.');

            return;
        }

        // Apply the same per-type limits used by Cloudinary before submitting the action.
        $maxMb = str_starts_with($value->getMimeType() ?? '', 'video/') ? 100 : 10;
        if ($value->getSize() > $maxMb * 1024 * 1024) {
            $fail('Il file supera il limite di '.$maxMb.' MB. Scegli una versione più leggera.');
        }
    }
}
