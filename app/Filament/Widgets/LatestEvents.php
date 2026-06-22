<?php

namespace App\Filament\Widgets;

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
                TextColumn::make('title')->label('Titolo'),
                TextColumn::make('status')->badge()->label('Stato'),
                TextColumn::make('start_date')->date()->label('Data Inizio'),
            ])
            ->paginated(false);
    }
}
