<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Components\MediaPicker;
use App\Filament\Resources\Media\MediaResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListMedia extends ListRecords
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('upload')->label('Carica media')->modalSubmitActionLabel('Aggiungi alla libreria')
                ->schema(MediaPicker::uploadSchema([
                    'image/jpeg', 'image/png', 'image/webp', 'image/gif',
                    'video/mp4', 'video/quicktime', 'video/webm',
                    'application/pdf', 'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/zip', 'application/x-zip-compressed',
                ], 102400, true))
                ->action(function (array $data, Action $action) {
                    try {
                        MediaPicker::notifyUpload(MediaPicker::uploadFiles($data['files']));
                    } catch (\Throwable $exception) {
                        report($exception);
                        Notification::make()->danger()->title('Caricamento non riuscito')
                            ->body(MediaPicker::uploadErrorMessage($exception))->send();
                        $action->halt();
                    }
                }),
        ];
    }
}
