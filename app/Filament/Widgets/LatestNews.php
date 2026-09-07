<?php

namespace App\Filament\Widgets;

use App\Filament\Support\ContentLabels;
use App\Models\News;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestNews extends TableWidget
{
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Ultime Notizie';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => News::query()->latest('published_at')->take(5))
            ->columns([
                TextColumn::make('title')->label('Titolo')->wrap()
                    ->description(fn ($record) => ContentLabels::mobileSummary(
                        ($record->published_at?->format('d/m/Y') ?? 'Senza data').' · '.ContentLabels::status($record)
                    )),
                TextColumn::make('status')->badge()->label('Stato')->visibleFrom('md')
                    ->formatStateUsing(fn ($record) => ContentLabels::status($record))
                    ->color(fn ($record) => ContentLabels::statusColor($record)),
                TextColumn::make('published_at')->date('d/m/Y')->label('Data Pubblicazione')->visibleFrom('md'),
            ])
            ->paginated(false);
    }
}
