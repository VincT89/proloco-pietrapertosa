<?php

namespace App\Filament\Resources\PageSettings\Pages;

use App\Filament\Resources\PageSettings\PageSettingResource;
use App\Filament\Support\ContentActions;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPageSetting extends EditRecord
{
    protected static string $resource = PageSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...ContentActions::forRecord($this->getRecord()),
            DeleteAction::make(),
        ];
    }
}
