<?php

namespace App\Filament\Widgets;

use App\Filament\Support\ContentLabels;
use App\Models\Event;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestEvents extends TableWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Ultimi Eventi';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Event::query()->latest('start_date')->take(5))
            ->columns([
                TextColumn::make('title')->label('Titolo')->wrap()
                    ->description(fn ($record) => ContentLabels::mobileSummary(
                        ($record->start_date?->format('d/m/Y') ?? 'Senza data').' · '.ContentLabels::status($record)
                    )),
                TextColumn::make('status')->badge()->label('Stato')->visibleFrom('md')
                    ->formatStateUsing(fn ($record) => ContentLabels::status($record))
                    ->color(fn ($record) => ContentLabels::statusColor($record)),
                TextColumn::make('start_date')->date('d/m/Y')->label('Data Inizio')->visibleFrom('md'),
            ])
            ->paginated(false);
    }
}
