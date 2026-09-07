<?php

namespace App\Filament\Support;

use App\Models\DirectoryItem;
use App\Models\Event;
use App\Models\GalleryAlbum;
use App\Models\News;
use App\Models\PageSetting;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class ContentActions
{
    public static function forRecord(Model $record): array
    {
        $actions = [];
        if ($record instanceof News || $record instanceof Event) {
            $actions[] = Action::make('previewSaved')
                ->label('Anteprima salvata')
                ->tooltip('Mostra l’ultima versione salvata, anche se è in bozza. Salva prima le modifiche che vuoi vedere.')
                ->url(route('admin.content-preview', [
                    'locale' => 'it', 'type' => $record instanceof News ? 'news' : 'event',
                    'record' => $record->getKey(),
                ]))->openUrlInNewTab();
        }

        if ($url = self::publicUrl($record)) {
            $actions[] = Action::make('openPublic')->label('Apri sul sito')
                ->url($url)->openUrlInNewTab()->color('gray');
        }

        return $actions;
    }

    public static function publicUrl(Model $record): ?string
    {
        if ($record instanceof News) {
            return $record->isVisibleToPublic() ? route('news.show.it', $record->slug) : null;
        }
        if ($record instanceof Event) {
            return $record->status === 'published' ? route('events.show.it', $record->slug) : null;
        }
        if ($record instanceof PageSetting) {
            $route = match ($record->page_slug) {
                'home' => 'home', 'pro-loco' => 'proLoco', 'comunita' => 'community',
                'eccellenze' => 'excellences', 'sapori' => 'tastes', 'scopri' => 'discover',
                'notizie' => 'news', 'eventi' => 'events', 'galleria' => 'gallery',
                'ringraziamenti-fotografici' => 'photo-thanks', default => null,
            };

            return $route ? route($route.'.it') : null;
        }
        if ($record instanceof DirectoryItem) {
            if ($record->category === 'eventi_annuali') {
                return route('traditions.show.it', $record->id);
            }
            $route = match ($record->category) {
                'comunita' => 'community', 'sapori_piatti' => 'tastes',
                'scopri_luoghi' => 'discover',
                'eccellenze_aziende', 'eccellenze_foodtruck', 'eccellenze_artigiani' => 'excellences',
                default => null,
            };

            return $route ? route($route.'.it').'#place-'.$record->id : null;
        }
        if ($record instanceof GalleryAlbum) {
            $position = GalleryAlbum::orderedForDisplay()->pluck('id')->search($record->id);
            if ($position === false) {
                return null;
            }

            return route('gallery.it', ['page' => intdiv($position, 12) + 1]).'#album-'.$record->id;
        }

        return null;
    }
}
