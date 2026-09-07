<?php

namespace App\Filament\Widgets;

use App\Models\DirectoryItem;
use App\Models\Event;
use App\Models\Media;
use App\Models\News;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminDashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Notizie online', News::visibleToPublic()->count())
                ->description('Visibili sul sito')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary'),
            Stat::make('Eventi in programma', Event::currentAndUpcoming()->count())
                ->description('In corso e futuri, pubblicati')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('success'),
            Stat::make('Attività', DirectoryItem::count())
                ->description('Luoghi, tradizioni e realtà locali')
                ->descriptionIcon('heroicon-o-map-pin')
                ->color('warning'),
            Stat::make('Libreria media', Media::count())
                ->description('Immagini, video e documenti')
                ->descriptionIcon('heroicon-o-photo')
                ->color('info'),
        ];
    }
}
