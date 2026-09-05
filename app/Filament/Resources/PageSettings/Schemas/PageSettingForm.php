<?php

namespace App\Filament\Resources\PageSettings\Schemas;

use App\Filament\Components\MediaPicker;
use App\Filament\Components\MediaRichEditor as RichEditor;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

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
                        TextInput::make('hero_cta_url')->label('URL Pulsante (CTA) Hero')->default(null)->rule('regex:/^(https?:\/\/|\/)[a-zA-Z0-9\-\.\_\~\:\/\?\#\[\]\@\!\$\&\'\(\)\*\+\,\;\=\%]+$/i'),
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
                        Grid::make(2)->columnSpanFull()->schema([
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
                            TextInput::make('data.discover_cta_url')->label('URL Pulsante Scopri')->default('https://www.borgoracconta.it/citta/pietrapertosa/')->rule('regex:/^(https?:\/\/|\/)[a-zA-Z0-9\-\.\_\~\:\/\?\#\[\]\@\!\$\&\'\(\)\*\+\,\;\=\%]+$/i'),
                        ]),
                        Repeater::make('data.discover_items')->label('Elementi Scopri Pietrapertosa')->schema([
                            TextInput::make('nome')->label('Titolo')->required(),
                            TextInput::make('nome_en')->label('Titolo (EN)'),
                            MediaPicker::make('img_media_id')->label('Immagine'),
                        ])->columns(2)->collapsible(),
                    ]),
                ])->visible(fn ($record) => $record?->page_slug === 'home')->columnSpanFull(),
                Tabs::make('Ringraziamenti Fotografici')->tabs([
                    Tab::make('Contributori fotografici')->schema([
                        Repeater::make('data.photo_contributors')->label('Fornitori e Contributori')->schema([
                            TextInput::make('name')->label('Nome fornitore/contributore')->required(),
                            Textarea::make('description')->label('Descrizione / nota')->required(),
                            Textarea::make('description_en')->label('Descrizione / nota (EN)'),
                            TextInput::make('website_url')->label('Sito web / link')->url(),
                            MediaPicker::make('logo_media_id')->label('Logo fornitore'),
                        ])->columns(1)->collapsible(),
                    ]),
                ])->visible(fn ($record) => $record?->page_slug === 'ringraziamenti-fotografici')->columnSpanFull(),
                Grid::make(2)->columnSpanFull()->schema([
                    Select::make('page_slug')
                        ->label('Pagina')
                        ->options([
                            'home' => 'Home',
                            'pro-loco' => 'Pro Loco',
                            'comunita' => 'Comunità',
                            'eccellenze' => 'Eccellenze',
                            'sapori' => 'Sapori',
                            'scopri' => 'Scopri',
                            'notizie' => 'Notizie',
                            'eventi' => 'Eventi',
                            'galleria' => 'Galleria',
                            'ringraziamenti-fotografici' => 'Ringraziamenti fotografici',
                        ])
                        ->required()
                        ->unique(ignoreRecord: true),
                    MediaPicker::make('hero_media_id')->label('Immagine principale'),
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
