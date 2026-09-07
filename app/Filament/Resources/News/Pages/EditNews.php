<?php

namespace App\Filament\Resources\News\Pages;

use App\Filament\Resources\News\NewsResource;
use App\Filament\Support\ContentActions;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNews extends EditRecord
{
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...ContentActions::forRecord($this->getRecord()),
            DeleteAction::make(),
        ];
    }
}
