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
                        TextInput::make('hero_cta_text')->label('Testo Pulsante (CTA) Hero')->default(null),
                        TextInput::make('hero_cta_url')->label('URL Pulsante (CTA) Hero')->default(null),
                        TextInput::make('intro_title')->label('Titolo Intro')->default(null),
                        RichEditor::make('intro_text')->label('Testo Intro')->default(null)->columnSpanFull(),
                    ]),
                    Tab::make('Inglese')->schema([
                        TextInput::make('hero_title_en')->label('Titolo Hero (EN)')->default(null)
                            ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('hero_title_en', $get('hero_title')))),
                        TextInput::make('hero_subtitle_en')->label('Sottotitolo Hero (EN)')->default(null)
                            ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('hero_subtitle_en', $get('hero_subtitle')))),
                        TextInput::make('hero_cta_text_en')->label('Testo Pulsante (CTA) Hero (EN)')->default(null)
                            ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('hero_cta_text_en', $get('hero_cta_text')))),
                        TextInput::make('intro_title_en')->label('Titolo Intro (EN)')->default(null)
                            ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('intro_title_en', $get('intro_title')))),
                        RichEditor::make('intro_text_en')->label('Testo Intro (EN)')->default(null)->columnSpanFull(),
                    ]),
                ])->columnSpanFull(),
                Tabs::make('Home Extra')->tabs([
                    Tab::make('Sezioni Extra Home')->schema([
                        Grid::make(2)->schema([
                            TextInput::make('data.events_title')->label('Titolo Sezione Eventi')->default('Prossimi Eventi'),
                            TextInput::make('data.events_title_en')->label('Titolo Sezione Eventi (EN)')->default('Upcoming Events'),
                            TextInput::make('data.news_title')->label('Titolo Sezione Notizie')->default('Ultime Notizie e Avvisi'),
                            TextInput::make('data.news_title_en')->label('Titolo Sezione Notizie (EN)')->default('Latest News'),
                            TextInput::make('data.discover_subtitle')->label('Sottotitolo Scopri')->default('ESPLORA IL TERRITORIO'),
                            TextInput::make('data.discover_subtitle_en')->label('Sottotitolo Scopri (EN)')->default('EXPLORE THE TERRITORY'),
                            TextInput::make('data.discover_title')->label('Titolo Scopri')->default('Scopri Pietrapertosa'),
                            TextInput::make('data.discover_title_en')->label('Titolo Scopri (EN)')->default('Discover Pietrapertosa'),
                            Textarea::make('data.discover_text')->label('Testo Scopri')->default('Dall\'Arabata al Castello Normanno-Svevo, fino all\'emozionante Volo dell\'Angelo. Scopri i luoghi che rendono unico il nostro borgo.')->columnSpanFull(),
                            Textarea::make('data.discover_text_en')->label('Testo Scopri (EN)')->columnSpanFull(),
                        ]),
                        \Filament\Forms\Components\Repeater::make('data.discover_items')->label('Elementi Scopri Pietrapertosa')->schema([
                            TextInput::make('nome')->label('Titolo')->required(),
                            TextInput::make('nome_en')->label('Titolo (EN)'),
                            \Filament\Forms\Components\Hidden::make('img_media_id'),
                            FileUpload::make('img')->label('Immagine (Cloudinary)')
                                ->image()
                                ->saveUploadedFileUsing(function (UploadedFile $file, callable $set) {
                                    $media = app(\App\Services\MediaManager::class)->upload($file);
                                    $set('img_media_id', $media->id);
                                    \Illuminate\Support\Facades\Cache::put('last_upload_'.$media->url, $media->id, 300);
                                    return $media->url;
                                })
                                ->deleteUploadedFileUsing(function (string $file, callable $set) {
                                    $mediaId = \Illuminate\Support\Facades\Cache::get('last_upload_'.$file);
                                    if (! $mediaId) {
                                        $media = \App\Models\Media::where('url', $file)->first();
                                        if ($media) $mediaId = $media->id;
                                    }
                                    $set('img_media_id', null);
                                    if ($mediaId) {
                                        $media = \App\Models\Media::find($mediaId);
                                        if ($media) {
                                            app(\App\Services\MediaManager::class)->delete($media);
                                        }
                                    }
                                    \Illuminate\Support\Facades\Cache::forget('last_upload_'.$file);
                                })
                                ->getUploadedFileUsing(function (string $file): ?array {
                                    return [
                                        'name' => basename($file),
                                        'size' => 0,
                                        'type' => 'image/jpeg',
                                        'url' => $file,
                                    ];
                                })
                        ])->columns(2)->collapsible()
                    ])
                ])->visible(fn ($record) => $record?->page_slug === 'home')->columnSpanFull(),
                Grid::make(2)->schema([
                    Select::make('page_slug')
                        ->label('Pagina')
                        ->options([
                            'home' => 'Home',
                            'pro-loco' => 'Pro Loco',
                            'comunita' => 'Comunità',
                            'territorio' => 'Territorio',
                            'sapori' => 'Sapori',
                            'scopri' => 'Scopri',
                            'storie' => 'Storie',
                            'il-borgo' => 'Il Borgo',
                            'contatti' => 'Contatti',
                            'notizie' => 'Notizie',
                            'eventi' => 'Eventi',
                            'galleria' => 'Galleria',
                        ])
                        ->required()
                        ->unique(ignoreRecord: true),
                    \Filament\Forms\Components\Hidden::make('hero_media_id'),
                    FileUpload::make('hero_media_upload')->label('Immagine Hero')
                        ->image()
                        ->maxSize(10240)
                        ->getUploadedFileUsing(function (string $file): ?array {
                            return [
                                'name' => basename($file),
                                'size' => 0,
                                'type' => 'image/jpeg',
                                'url' => $file,
                            ];
                        })
                        ->saveUploadedFileUsing(function (UploadedFile $file, callable $set) {
                            $media = app(\App\Services\MediaManager::class)->upload($file);
                            $set('hero_media_id', $media->id);
                            \Illuminate\Support\Facades\Cache::put('last_upload_'.$media->url, $media->id, 300);
                            return $media->url;
                        })
                        ->deleteUploadedFileUsing(function (string $file, callable $set) {
                            $mediaId = \Illuminate\Support\Facades\Cache::get('last_upload_'.$file);
                            if (! $mediaId) {
                                // Try fallback by url if editing
                                $media = Media::where('url', $file)->first();
                                if ($media) $mediaId = $media->id;
                            }
                            
                            $set('hero_media_id', null);

                            if ($mediaId) {
                                $media = Media::find($mediaId);
                                if ($media && \Illuminate\Support\Facades\DB::table('mediables')->where('media_id', $media->id)->count() === 0) {
                                    app(\App\Services\MediaManager::class)->delete($media);
                                }
                            }
                            \Illuminate\Support\Facades\Cache::forget('last_upload_'.$file);
                        })
                        ->afterStateHydrated(function ($component, $record) {
                            if ($record && $record->heroMedia) {
                                $component->state($record->heroMedia->url);
                            }
                        })
                        ->dehydrated(false),
                    TextInput::make('hero_overlay_opacity')->label('Opacità Sfondo Hero')
                        ->numeric()
                        ->default(0.4)
                        ->minValue(0)
                        ->maxValue(1)
                        ->step(0.1),
                    Select::make('translation_status')->label('Stato Traduzione')->options(['draft' => 'Bozza', 'missing' => 'Mancante', 'reviewed' => 'Revisionato'])->default('missing')->required(),
                ]),
            ]);
    }
}
