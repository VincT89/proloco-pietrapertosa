<?php

namespace App\Filament\Widgets;

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
                TextColumn::make('title')->label('Titolo'),
                TextColumn::make('status')->badge()->label('Stato'),
                TextColumn::make('published_at')->date()->label('Data Pubblicazione'),
            ])
            ->paginated(false);
    }
}
