<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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
