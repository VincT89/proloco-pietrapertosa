<?php

namespace App\Filament\Resources\DirectoryItems\Pages;

use App\Filament\Resources\DirectoryItems\DirectoryItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDirectoryItems extends ListRecords
{
    protected static string $resource = DirectoryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
