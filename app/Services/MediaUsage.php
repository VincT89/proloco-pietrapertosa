<?php

namespace App\Services;

use App\Models\DirectoryItem;
use App\Models\Event;
use App\Models\FinancialDocument;
use App\Models\GalleryAlbum;
use App\Models\News;
use App\Models\PageSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MediaUsage
{
    private array $cache = [];

    public const CONTENT_FIELDS = [
        News::class => ['content', 'content_en'],
        Event::class => ['description', 'description_en'],
        DirectoryItem::class => ['description', 'description_en'],
        PageSetting::class => ['intro_text', 'intro_text_en'],
    ];

    public static function imageUrls(?string $html): array
    {
        preg_match_all('~<img\b[^>]*\ssrc\s*=\s*([\'"])(.*?)\1~is', $html ?? '', $matches);

        return array_values(array_unique(array_map(
            fn ($url) => html_entity_decode($url, ENT_QUOTES | ENT_HTML5, 'UTF-8'), $matches[2] ?? []
        )));
    }

    public function forMedia(Collection $media, bool $fresh = false): array
    {
        if ($media->isEmpty()) {
            return [];
        }
        $key = $media->pluck('id')->sort()->implode(',');
        if (! $fresh && isset($this->cache[$key])) {
            return $this->cache[$key];
        }
        $ids = $media->pluck('id')->all();
        $usage = array_fill_keys($ids, []);
        $add = function ($id, $record, string $detail) use (&$usage) {
            if (array_key_exists($id, $usage)) {
                $label = $this->label($record).' ('.$detail.')';
                $usage[$id][$label] = $label;
            }
        };

        $links = DB::table('mediables')->whereIn('media_id', $ids)->get()->groupBy('mediable_type');
        foreach ([News::class, Event::class, DirectoryItem::class, GalleryAlbum::class] as $model) {
            $modelLinks = $links->get((new $model)->getMorphClass(), collect());
            $records = $model::whereIn('id', $modelLinks->pluck('mediable_id'))->get()->keyBy('id');
            foreach ($modelLinks as $link) {
                if ($record = $records->get($link->mediable_id)) {
                    $add($link->media_id, $record, $link->collection === 'attachments' ? 'allegati' : 'galleria');
                }
            }
        }
        foreach ([News::class, Event::class] as $model) {
            foreach ($model::whereIn('cover_media_id', $ids)->get() as $record) {
                $add($record->cover_media_id, $record, 'copertina');
            }
        }
        foreach (FinancialDocument::whereIn('media_id', $ids)->get() as $record) {
            $add($record->media_id, $record, 'documento');
        }
        foreach (PageSetting::all() as $page) {
            $add($page->hero_media_id, $page, 'immagine principale');
            $walk = function (array $data) use (&$walk, $add, $page) {
                foreach ($data as $key => $value) {
                    if (in_array($key, ['img_media_id', 'logo_media_id'], true) && is_scalar($value)) {
                        $add($value, $page, $key === 'logo_media_id' ? 'logo contributore' : 'contenuti');
                    } elseif (is_array($value)) {
                        $walk($value);
                    }
                }
            };
            $walk($page->data ?? []);
        }

        $byUrl = $media->groupBy('url');
        foreach (self::CONTENT_FIELDS as $model => $fields) {
            $records = $model::where(function ($query) use ($fields, $byUrl) {
                foreach ($fields as $field) {
                    foreach ($byUrl->keys() as $url) {
                        if ($url) {
                            $query->orWhere($field, 'like', '%'.$url.'%')
                                ->orWhere($field, 'like', '%'.htmlspecialchars($url, ENT_QUOTES, 'UTF-8').'%');
                        }
                    }
                }
            })->get();
            foreach ($records as $record) {
                foreach ($fields as $field) {
                    foreach (self::imageUrls($record->$field) as $url) {
                        foreach ($byUrl->get($url, collect()) as $item) {
                            $add($item->id, $record, str_ends_with($field, '_en') ? 'testo inglese' : 'testo');
                        }
                    }
                }
            }
        }

        return $this->cache[$key] = array_map('array_values', $usage);
    }

    private function label($record): string
    {
        $section = match (get_class($record)) {
            News::class => 'Notizia',
            Event::class => 'Evento',
            GalleryAlbum::class => 'Album',
            FinancialDocument::class => 'Documento',
            PageSetting::class => 'Pagina',
            DirectoryItem::class => match ($record->category) {
                'comunita' => 'Comunità',
                'sapori_piatti' => 'Sapori',
                'scopri_luoghi' => 'Scopri',
                'eventi_annuali' => 'Eventi annuali',
                default => 'Eccellenze',
            },
            default => 'Contenuto',
        };

        return $section.': '.($record instanceof PageSetting ? $record->page_slug : $record->title);
    }
}
