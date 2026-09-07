<?php

namespace App\Filament\Resources\DirectoryItems\Pages;

use App\Filament\Resources\DirectoryItems\DirectoryItemResource;
use App\Filament\Support\ContentActions;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDirectoryItem extends EditRecord
{
    protected static string $resource = DirectoryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...ContentActions::forRecord($this->getRecord()),
            DeleteAction::make(),
        ];
    }
}
