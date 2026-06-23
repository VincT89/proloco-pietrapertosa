<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMedia extends EditRecord
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->using(function (\App\Models\Media $record, DeleteAction $action) {
                    if (! app(\App\Services\MediaManager::class)->delete($record)) {
                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('Impossibile eliminare')
                            ->body('Il media è in uso e non può essere cancellato.')
                            ->send();
                        $action->halt();
                    }
                }),
        ];
    }
}
