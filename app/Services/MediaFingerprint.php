<?php

namespace App\Services;

use App\Exceptions\MediaUploadException;
use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MediaFingerprint
{
    public function forUpload(UploadedFile $file): string
    {
        $path = $file->getRealPath();
        if (! $path || ! is_file($path) || ! is_readable($path)) {
            throw new MediaUploadException('Il file temporaneo non è più disponibile. Rimuovilo e selezionalo di nuovo.');
        }

        $hash = hash_file('sha256', $path);
        if ($hash === false) {
            throw new MediaUploadException('Impossibile leggere il file selezionato. Rimuovilo e selezionalo di nuovo.');
        }

        return $hash;
    }

    public function findUpload(UploadedFile $file): ?Media
    {
        return Media::where('content_hash', $this->forUpload($file))->oldest('id')->first();
    }

    public function localPath(string $url): ?string
    {
        $parts = parse_url($url);
        if ($parts === false || isset($parts['user']) || isset($parts['pass'])) {
            return null;
        }
        if (isset($parts['host']) && strcasecmp($parts['host'], parse_url(config('app.url'), PHP_URL_HOST) ?? '') !== 0) {
            return null;
        }

        $path = rawurldecode($parts['path'] ?? '');
        $path = '/'.ltrim($path, '/');
        $roots = ['/images/' => public_path('images'), '/storage/' => storage_path('app/public')];
        foreach ($roots as $prefix => $root) {
            if (! str_starts_with($path, $prefix)) {
                continue;
            }
            $base = realpath($root);
            $candidate = realpath($root.DIRECTORY_SEPARATOR.substr($path, strlen($prefix)));
            if ($base && $candidate && str_starts_with($candidate, $base.DIRECTORY_SEPARATOR) && is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    public function forUrl(string $url): string
    {
        if ($path = $this->localPath($url)) {
            $hash = hash_file('sha256', $path);
            if ($hash !== false) {
                return $hash;
            }
        }

        $parts = parse_url($url);
        $cloud = config('services.cloudinary.cloud_name');
        $allowedPath = $cloud && preg_match('~^/'.preg_quote($cloud, '~').'/(image|video|raw)/upload/~', $parts['path'] ?? '');
        if (($parts['scheme'] ?? '') !== 'https' || ($parts['host'] ?? '') !== 'res.cloudinary.com'
            || isset($parts['user']) || isset($parts['pass']) || isset($parts['port']) || ! $allowedPath) {
            throw new RuntimeException('Originale non disponibile in una posizione verificabile.');
        }

        $response = Http::connectTimeout(10)->timeout(90)
            ->withOptions(['stream' => true, 'allow_redirects' => false])->get($url)->throw();
        if (! $response->successful()) {
            throw new RuntimeException('Il server non ha restituito il file originale.');
        }
        $stream = $response->toPsrResponse()->getBody();
        $context = hash_init('sha256');
        $size = 0;
        try {
            while (! $stream->eof()) {
                $chunk = $stream->read(1024 * 1024);
                $size += strlen($chunk);
                if ($size > 105 * 1024 * 1024) {
                    throw new RuntimeException('Il file supera il limite previsto per la verifica.');
                }
                hash_update($context, $chunk);
            }
        } finally {
            $stream->close();
        }
        if ($size === 0) {
            throw new RuntimeException('Il file originale è vuoto.');
        }

        return hash_final($context);
    }

    public function index(Media $media): void
    {
        $media->update([
            'content_hash' => $this->forUrl($media->url),
            'original_name' => $media->original_name ?: ($media->metadata['original_name'] ?? null),
        ]);
    }
}
