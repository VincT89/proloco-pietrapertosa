<?php

namespace App\Filament\Resources\DirectoryItems;

use App\Filament\Resources\DirectoryItems\Pages\CreateDirectoryItem;
use App\Filament\Resources\DirectoryItems\Pages\EditDirectoryItem;
use App\Filament\Resources\DirectoryItems\Pages\ListDirectoryItems;
use App\Filament\Resources\DirectoryItems\Schemas\DirectoryItemForm;
use App\Filament\Resources\DirectoryItems\Tables\DirectoryItemsTable;
use App\Models\DirectoryItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DirectoryItemResource extends Resource
{
    protected static ?string $model = DirectoryItem::class;

    protected static ?string $modelLabel = 'Attività';

    protected static ?string $pluralModelLabel = 'Attività';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DirectoryItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DirectoryItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDirectoryItems::route('/'),
            'create' => CreateDirectoryItem::route('/create'),
            'edit' => EditDirectoryItem::route('/{record}/edit'),
        ];
    }
}
