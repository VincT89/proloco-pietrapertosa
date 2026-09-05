<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Services\MediaFingerprint;
use App\Services\MediaUsage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class IndexMedia extends Command
{
    protected $signature = 'media:index {--import-embedded : Registra le immagini pubbliche presenti nei testi}
        {--import-public-images : Registra anche le immagini in public/images}
        {--limit= : Limita il numero di media esistenti da verificare}';

    protected $description = 'Indicizza i file esistenti per riconoscere i duplicati, senza eliminare o unire media.';

    public function handle(MediaFingerprint $fingerprints): int
    {
        $failed = 0;
        if ($this->option('limit') !== null && (! ctype_digit((string) $this->option('limit')) || (int) $this->option('limit') < 1)) {
            $this->error('Il limite deve essere un numero intero positivo.');

            return self::FAILURE;
        }
        if ($this->option('import-embedded')) {
            foreach (MediaUsage::CONTENT_FIELDS as $model => $fields) {
                $model::select(['id', ...$fields])->chunkById(100, function ($records) use ($fields, $fingerprints, &$failed) {
                    foreach ($records as $record) {
                        foreach ($fields as $field) {
                            foreach (MediaUsage::imageUrls($record->$field) as $url) {
                                $failed += $this->importUrl($url, $fingerprints) ? 0 : 1;
                            }
                        }
                    }
                });
            }
        }
        $done = 0;
        $limit = $this->option('limit') !== null ? (int) $this->option('limit') : PHP_INT_MAX;
        Media::whereNull('content_hash')->whereIn('provider', ['cloudinary', 'local'])
            ->chunkById(100, function ($records) use ($fingerprints, &$done, &$failed, $limit) {
                foreach ($records as $media) {
                    if ($done >= $limit) {
                        return false;
                    }
                    $done++;
                    try {
                        $fingerprints->index($media);
                        $this->line('Verificato media '.$media->id.': '.$media->displayName());
                    } catch (\Throwable $exception) {
                        $failed++;
                        $this->warn('Media '.$media->id.' non verificato: '.$exception->getMessage());
                    }
                }
            });

        if ($this->option('import-public-images') && is_dir(public_path('images'))) {
            foreach (File::allFiles(public_path('images')) as $file) {
                if (! in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                    continue;
                }
                $url = url('images/'.str_replace(DIRECTORY_SEPARATOR, '/', $file->getRelativePathname()));
                $failed += $this->importUrl($url, $fingerprints, reuseExisting: true) ? 0 : 1;
            }
        }

        $duplicates = Media::whereNotNull('content_hash')->select('content_hash')
            ->groupBy('content_hash')->havingRaw('COUNT(*) > 1')->get();
        foreach ($duplicates as $group) {
            $ids = Media::where('content_hash', $group->content_hash)->orderBy('id')->pluck('id')->implode(', ');
            $this->warn('File identici già presenti, media: '.$ids.'. Record e collegamenti conservati.');
        }
        $pending = Media::whereNull('content_hash')->whereIn('provider', ['cloudinary', 'local'])->count();
        $this->info('Verifica terminata. File ancora da verificare: '.$pending.'. Errori: '.$failed.'.');

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function importUrl(string $url, MediaFingerprint $fingerprints, bool $reuseExisting = false): bool
    {
        if (Media::where('url', $url)->exists()) {
            return true;
        }
        try {
            $hash = $fingerprints->forUrl($url);
            if ($reuseExisting && Media::where('content_hash', $hash)->exists()) {
                return true;
            }
            $path = $fingerprints->localPath($url);
            Media::create([
                'url' => $url, 'public_id' => null, 'type' => 'image', 'resource_type' => 'image',
                'provider' => $path ? 'local' : 'cloudinary', 'content_hash' => $hash,
                'original_name' => $path ? basename($path) : null,
                'metadata' => $path ? ['mime_type' => mime_content_type($path), 'size' => filesize($path)] : [],
            ]);

            return true;
        } catch (\Throwable $exception) {
            $this->warn('Immagine non importata: '.basename(parse_url($url, PHP_URL_PATH) ?: '').'. '.$exception->getMessage());

            return false;
        }
    }
}
