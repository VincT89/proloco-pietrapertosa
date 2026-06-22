<?php

namespace App\Filament\Widgets;

use App\Models\DirectoryItem;
use App\Models\Event;
use App\Models\News;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Totale Notizie', News::count())
                ->description('Ultime pubblicate')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success'),
            Stat::make('Totale Eventi', Event::count())
                ->description('Eventi in programma')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),
            Stat::make('Directory', DirectoryItem::count())
                ->description('Attività e servizi')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('info'),
        ];
    }
}
