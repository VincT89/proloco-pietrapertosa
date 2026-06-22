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
            Stat::make('Notizie', News::count())
                ->description('Ultime pubblicazioni')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary'),
            Stat::make('Eventi', Event::count())
                ->description('Eventi in programma')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('success'),
            Stat::make('Directory', DirectoryItem::count())
                ->description('Aziende e luoghi')
                ->descriptionIcon('heroicon-o-map-pin')
                ->color('warning'),
            Stat::make('Media File', Media::count())
                ->description('Immagini su Cloudinary')
                ->descriptionIcon('heroicon-o-photo')
                ->color('info'),
        ];
    }
}
