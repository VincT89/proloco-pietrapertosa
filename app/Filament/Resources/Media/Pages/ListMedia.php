<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMedia extends ListRecords
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('upload')
                ->label('Carica Media')
                ->form([
                    \Filament\Forms\Components\FileUpload::make('file')
                        ->label('File')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'video/mp4', 'video/quicktime', 'video/webm'])
                        ->maxSize(102400)
                        ->required()
                        ->disk('public')
                        ->directory('temp_uploads'),
                ])
                ->action(function (array $data, \Filament\Actions\Action $action) {
                    $filePath = storage_path('app/public/' . $data['file']);
                    if (!file_exists($filePath)) {
                        $action->halt();
                    }
                    
                    $file = new \Illuminate\Http\UploadedFile(
                        $filePath, 
                        basename($filePath), 
                        mime_content_type($filePath) ?: 'application/octet-stream', 
                        null, 
                        true // test mode to avoid is_uploaded_file check failure
                    );
                    try {
                        app(\App\Services\MediaManager::class)->upload($file);
                        unlink($filePath);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Media Caricato')
                            ->body('Il media è stato caricato con successo.')
                            ->send();
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('Errore Caricamento')
                            ->body('Si è verificato un errore: ' . $e->getMessage())
                            ->send();
                        $action->halt();
                    }
                }),
        ];
    }
}
