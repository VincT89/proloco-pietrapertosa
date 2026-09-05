<?php

namespace App\Filament\Resources\News\Schemas;

use App\Filament\Components\MediaPicker;
use App\Filament\Components\MediaRichEditor as RichEditor;
use App\Filament\Components\MediaUpload;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Lingue')->tabs([
                    Tab::make('Italiano')->schema([
                        TextInput::make('title')->label('Titolo')->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if (empty($get('slug')) || $get('slug') === Str::slug($state)) {
                                    $set('slug', Str::slug($state));
                                }
                            }),
                        Textarea::make('excerpt')->label('Riassunto')->default(null)->columnSpanFull(),
                        RichEditor::make('content')->label('Contenuto')->required()->columnSpanFull(),
                        Textarea::make('seo_description')->label('Descrizione SEO')->default(null)->columnSpanFull(),
                    ]),
                    Tab::make('Inglese')->schema([
                        TextInput::make('title_en')->label('Titolo (EN)')->default(null)
                            ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('title_en', $get('title')))),
                        Textarea::make('excerpt_en')->label('Riassunto (EN)')->default(null)->columnSpanFull()
                            ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('excerpt_en', $get('excerpt')))),
                        RichEditor::make('content_en')->label('Contenuto (EN)')->default(null)->columnSpanFull(),
                        Textarea::make('seo_description_en')->label('Descrizione SEO (EN)')->default(null)->columnSpanFull()
                            ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('seo_description_en', $get('seo_description')))),
                    ]),
                ])->columnSpanFull(),
                Grid::make(2)->columnSpanFull()->schema([
                    TextInput::make('slug')->label('Slug (URL)')->required()->unique(ignoreRecord: true),
                    DateTimePicker::make('published_at')->label('Data di Pubblicazione'),
                    MediaPicker::make('cover_media_id')->label('Copertina'),
                    MediaUpload::make('gallery_files', 'gallery')
                        ->label('Galleria immagini e video')
                        ->columnSpanFull(),

                    MediaUpload::make(
                        'attachments_files',
                        'attachments',
                        ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/x-zip-compressed'],
                        10240,
                        new HtmlString('Allegati: massimo 10 MB per file. Consigliato formato PDF o ZIP.')
                    )
                        ->label('Allegati Scaricabili (PDF/Doc/Zip)')
                        ->columnSpanFull(),
                    Select::make('status')->label('Stato')->options(['draft' => 'Bozza', 'published' => 'Pubblicato', 'archived' => 'Archiviato'])->default('draft')->required(),
                    Select::make('translation_status')->label('Stato Traduzione')->options(['draft' => 'Bozza', 'missing' => 'Mancante', 'reviewed' => 'Revisionato'])->default('missing')->required(),
                ]),
            ]);
    }
}
