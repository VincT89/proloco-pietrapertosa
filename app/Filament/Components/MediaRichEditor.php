<?php

namespace App\Filament\Components;

use App\Models\Media;
use App\Services\MediaManager;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\Width;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaRichEditor extends RichEditor
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->fileAttachmentsAcceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
            ->fileAttachmentsMaxSize(10240);
        $this->registerActions([
            Action::make('attachFiles')->label('Inserisci immagine')
                ->modalHeading('Immagine nel testo')->modalWidth(Width::FourExtraLarge)
                ->modalSubmitActionLabel('Inserisci immagine')
                ->fillForm(fn (array $arguments) => [
                    'media_id' => isset($arguments['src']) ? Media::where('url', $arguments['src'])->value('id') : null,
                    'alt' => $arguments['alt'] ?? '',
                ])
                ->schema(fn (array $arguments) => [
                    MediaPicker::make('media_id')->label('Immagine')->required(blank($arguments['src'] ?? null)),
                    TextInput::make('alt')->label('Descrizione alternativa per questa immagine')->maxLength(1000),
                ])
                ->action(function (array $data, array $arguments, self $component) {
                    $media = isset($data['media_id']) ? Media::where('type', 'image')->find($data['media_id']) : null;
                    $attributes = [
                        'src' => $media?->url ?? ($arguments['src'] ?? null),
                        'id' => $media ? 'media:'.$media->id : ($arguments['id'] ?? null),
                        'alt' => $data['alt'] ?? '',
                    ];
                    if (! $attributes['src']) {
                        return;
                    }
                    $selection = $arguments['editorSelection'] ?? null;
                    if (filled($arguments['src'] ?? null)) {
                        if (isset($selection['type']) && $selection['type'] !== 'node') {
                            $selection['type'] = 'node';
                            $selection['anchor']--;
                            unset($selection['head']);
                        }
                        $command = EditorCommand::make('updateAttributes', arguments: ['image', $attributes]);
                    } else {
                        $command = EditorCommand::make('insertContent', arguments: [['type' => 'image', 'attrs' => $attributes]]);
                    }
                    $component->runCommands([$command], editorSelection: $selection);
                }),
        ]);
    }

    public function defaultSaveUploadedFileAttachment(TemporaryUploadedFile $file): mixed
    {
        return 'media:'.app(MediaManager::class)->upload($file)->id;
    }

    public function getDefaultFileAttachmentUrl(mixed $file): ?string
    {
        if (is_string($file) && preg_match('/^media:(\d+)$/', $file, $matches)) {
            return Media::find($matches[1])?->url;
        }

        return parent::getDefaultFileAttachmentUrl($file);
    }
}
