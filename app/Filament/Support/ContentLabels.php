<?php

namespace App\Filament\Support;

use App\Models\News;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class ContentLabels
{
    public const TRANSLATIONS = [
        'draft' => 'Bozza',
        'missing' => 'Mancante',
        'reviewed' => 'Revisionata',
    ];

    public const CATEGORIES = [
        'comunita' => 'Comunità',
        'eccellenze_aziende' => 'Eccellenze · Aziende',
        'eccellenze_foodtruck' => 'Eccellenze · Food truck',
        'eccellenze_artigiani' => 'Eccellenze · Artigiani',
        'sapori_piatti' => 'Sapori · Piatti',
        'eventi_annuali' => 'Tradizioni annuali',
        'scopri_luoghi' => 'Scopri · Luoghi',
    ];

    public const PAGES = [
        'home' => 'Home',
        'pro-loco' => 'Pro Loco',
        'comunita' => 'Comunità',
        'eccellenze' => 'Eccellenze',
        'sapori' => 'Sapori',
        'scopri' => 'Scopri e Vivi',
        'notizie' => 'Notizie',
        'eventi' => 'Eventi',
        'galleria' => 'Galleria',
        'ringraziamenti-fotografici' => 'Ringraziamenti fotografici',
    ];

    public static function status(Model $record): string
    {
        if ($record instanceof News && $record->isScheduled()) {
            return 'Programmata';
        }

        return match ($record->status) {
            'draft' => 'Bozza',
            'published' => 'Pubblicato',
            'archived' => 'Archiviato',
            'cancelled' => 'Annullato',
            default => 'Non definito',
        };
    }

    public static function statusColor(Model $record): string
    {
        if ($record instanceof News && $record->isScheduled()) {
            return 'info';
        }

        return match ($record->status) {
            'published' => 'success',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }

    public static function mobileSummary(string $text): HtmlString
    {
        return new HtmlString('<span class="bo-mobile-summary">'.e($text).'</span>');
    }
}
