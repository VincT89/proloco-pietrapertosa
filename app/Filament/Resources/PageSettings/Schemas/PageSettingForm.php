<?php

namespace App\Filament\Resources\PageSettings\Schemas;

use App\Models\Media;
use App\Services\CloudinaryService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Http\UploadedFile;

class PageSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Lingue')->tabs([
                    Tab::make('Italiano')->schema([
                        TextInput::make('hero_title')->label('Titolo Hero')->default(null),
                        TextInput::make('hero_subtitle')->label('Sottotitolo Hero')->default(null),
                        TextInput::make('intro_title')->label('Titolo Intro')->default(null),
                        RichEditor::make('intro_text')->label('Testo Intro')->default(null)->columnSpanFull(),
                    ]),
                    Tab::make('Inglese')->schema([
                        TextInput::make('hero_title_en')->label('Titolo Hero (EN)')->default(null)
                            ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('hero_title_en', $get('hero_title')))),
                        TextInput::make('hero_subtitle_en')->label('Sottotitolo Hero (EN)')->default(null)
                            ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('hero_subtitle_en', $get('hero_subtitle')))),
                        TextInput::make('intro_title_en')->label('Titolo Intro (EN)')->default(null)
                            ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('intro_title_en', $get('intro_title')))),
                        RichEditor::make('intro_text_en')->label('Testo Intro (EN)')->default(null)->columnSpanFull(),
                    ]),
                ])->columnSpanFull(),
                Grid::make(2)->schema([
                    TextInput::make('page_slug')->label('Slug Pagina')->required(),
                    FileUpload::make('hero_media_url')->label('Immagine Hero')
                        ->image()
                        ->getUploadedFileUsing(function (string $file): ?array {
                            return [
                                'name' => basename($file),
                                'size' => 0,
                                'type' => 'image/jpeg',
                                'url' => $file,
                            ];
                        })
                        ->saveUploadedFileUsing(function (UploadedFile $file) {
                            $cloudinary = app(CloudinaryService::class);
                            $upload = $cloudinary->uploadMedia($file);
                            $media = Media::create([
                                'url' => $upload['url'],
                                'public_id' => $upload['public_id'],
                                'alt' => $file->getClientOriginalName(),
                            ]);
                            \Illuminate\Support\Facades\Cache::put('last_upload_'.$upload['url'], true, 300);

                            return $upload['url'];
                        })
                        ->deleteUploadedFileUsing(function (string $file) {
                            if (\Illuminate\Support\Facades\Cache::get('last_upload_'.$file)) {
                                Media::where('url', $file)->delete();
                                \Illuminate\Support\Facades\Cache::forget('last_upload_'.$file);
                            }
                        })
                        ->afterStateHydrated(function ($component, $record) {
                            if ($record && $record->heroMedia) {
                                $component->state($record->heroMedia->url);
                            }
                        })
                        ->dehydrated(false)
                        ->saveRelationshipsUsing(function ($record, $state) {
                            if ($state) {
                                $media = Media::where('url', $state)->first();
                                if ($media) {
                                    $record->hero_media_id = $media->id;
                                    $record->saveQuietly();
                                }
                            } else {
                                $record->hero_media_id = null;
                                $record->saveQuietly();
                            }
                        }),
                    Select::make('translation_status')->label('Stato Traduzione')->options(['draft' => 'Bozza', 'missing' => 'Mancante', 'reviewed' => 'Revisionato'])->default('missing')->required(),
                ]),
            ]);
    }
}
