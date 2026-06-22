<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditMedia extends EditRecord
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['url'])) {
            $cached = Cache::pull('last_upload_'.$data['url']);
            if ($cached) {
                $data['public_id'] = $cached['public_id'];
                $data['resource_type'] = $cached['resource_type'];
            }
        }

        return $data;
    }
}
