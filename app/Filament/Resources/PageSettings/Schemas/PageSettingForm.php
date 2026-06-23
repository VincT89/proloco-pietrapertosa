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
                            TextInput::make('data.discover_cta_text')->label('Testo Pulsante Scopri')->default('Borgo Racconta'),
                            TextInput::make('data.discover_cta_text_en')->label('Testo Pulsante Scopri (EN)')->default('Borgo Racconta'),
                            TextInput::make('data.discover_cta_url')->label('URL Pulsante Scopri')->default('https://www.borgoracconta.it/citta/pietrapertosa/'),
                        ]),
                        \Filament\Forms\Components\Repeater::make('data.discover_items')->label('Elementi Scopri Pietrapertosa')->schema([
                            TextInput::make('nome')->label('Titolo')->required(),
                            TextInput::make('nome_en')->label('Titolo (EN)'),
                            Select::make('img_media_id')->label('Immagine (Libreria)')
                                ->options(function () {
                                    return \App\Models\Media::where('type', 'image')->get()->mapWithKeys(function ($media) {
                                        $url = $media->thumbnail_url ?: ($media->type === 'image' ? $media->optimizedUrl('small') : null);
                                        $preview = $url ? '<img src="' . (str_starts_with($url, 'http') ? $url : asset($url)) . '" style="height: 30px; width: 30px; object-fit: cover; border-radius: 4px; display: inline-block; margin-right: 8px; vertical-align: middle;" />' : '';
                                        $name = $media->alt ?: ($media->name ?: "Media #{$media->id}");
                                        return [$media->id => '<div style="display: flex; align-items: center;">' . $preview . '<span>' . e($name) . '</span></div>'];
                                    });
                                })
                                ->allowHtml()
                                ->createOptionForm([
                                    FileUpload::make('file')->label('Carica Immagine')->image()->storeFiles(false)->required(),
                                    TextInput::make('alt')->label('Testo Alternativo'),
                                ])
                                ->createOptionUsing(function (array $data) {
                                    $media = app(\App\Services\MediaManager::class)->upload($data['file']);
                                    if (!empty($data['alt'])) {
                                        $media->update(['alt' => $data['alt']]);
                                    }
                                    return $media->id;
                                })
                                ->searchable(),
                        ])->columns(2)->collapsible()
                    ])
                ])->visible(fn ($record) => $record?->page_slug === 'home')->columnSpanFull(),
                Tabs::make('Ringraziamenti Fotografici')->tabs([
                    Tab::make('Contributori fotografici')->schema([
                        \Filament\Forms\Components\Repeater::make('data.photo_contributors')->label('Fornitori e Contributori')->schema([
                            TextInput::make('name')->label('Nome fornitore/contributore')->required(),
                            Textarea::make('description')->label('Descrizione / nota')->required(),
                            Textarea::make('description_en')->label('Descrizione / nota (EN)'),
                            TextInput::make('website_url')->label('Sito web / link')->url(),
                            Select::make('logo_media_id')->label('Logo Fornitore')
                                ->options(function () {
                                    return \App\Models\Media::where('type', 'image')->get()->mapWithKeys(function ($media) {
                                        $url = $media->thumbnail_url ?: ($media->type === 'image' ? $media->optimizedUrl('small') : null);
                                        $preview = $url ? '<img src="' . (str_starts_with($url, 'http') ? $url : asset($url)) . '" style="height: 30px; width: 30px; object-fit: contain; border-radius: 4px; display: inline-block; margin-right: 8px; vertical-align: middle; filter: brightness(0) invert(1);" />' : '';
                                        $name = $media->alt ?: ($media->name ?: "Media #{$media->id}");
                                        return [$media->id => '<div style="display: flex; align-items: center; background: #222; padding: 2px;">' . $preview . '<span>' . e($name) . '</span></div>'];
                                    });
                                })
                                ->allowHtml()
                                ->createOptionForm([
                                    FileUpload::make('file')->label('Carica Logo')->image()->storeFiles(false)->required(),
                                    TextInput::make('alt')->label('Testo Alternativo'),
                                ])
                                ->createOptionUsing(function (array $data) {
                                    $media = app(\App\Services\MediaManager::class)->upload($data['file']);
                                    if (!empty($data['alt'])) {
                                        $media->update(['alt' => $data['alt']]);
                                    }
                                    return $media->id;
                                })
                        ])->columns(1)->collapsible()
                    ])
                ])->visible(fn ($record) => $record?->page_slug === 'ringraziamenti-fotografici')->columnSpanFull(),
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
                            'ringraziamenti-fotografici' => 'Ringraziamenti fotografici',
                        ])
                        ->required()
                        ->unique(ignoreRecord: true),
                    Select::make('hero_media_id')->label('Immagine Hero')
                        ->relationship('heroMedia', 'name', fn ($query) => $query->where('type', 'image'))
                        ->allowHtml()
                        ->getOptionLabelFromRecordUsing(function (\App\Models\Media $record) {
                            $url = $record->thumbnail_url ?: ($record->type === 'image' ? $record->optimizedUrl('small') : null);
                            $preview = $url ? '<img src="' . (str_starts_with($url, 'http') ? $url : asset($url)) . '" style="height: 30px; width: 30px; object-fit: cover; border-radius: 4px; display: inline-block; margin-right: 8px; vertical-align: middle;" />' : '';
                            $name = $record->alt ?: ($record->name ?: "Media #{$record->id}");
                            return '<div style="display: flex; align-items: center;">' . $preview . '<span>' . e($name) . '</span></div>';
                        })
                        ->createOptionForm([
                            FileUpload::make('file')->label('Carica File')->image()->storeFiles(false)->required(),
                            TextInput::make('alt')->label('Testo Alternativo'),
                        ])
                        ->createOptionUsing(function (array $data) {
                            $media = app(\App\Services\MediaManager::class)->upload($data['file']);
                            if (!empty($data['alt'])) {
                                $media->update(['alt' => $data['alt']]);
                            }
                            return $media->getKey();
                        })
                        ->searchable()
                        ->preload(),
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
