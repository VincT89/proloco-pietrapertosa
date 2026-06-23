<?php

namespace App\Filament\Resources\News\Schemas;

use App\Models\Media;
use App\Services\CloudinaryService;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
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
                            ->afterStateUpdated(function ($state, \Filament\Schemas\Components\Utilities\Set $set, \Filament\Schemas\Components\Utilities\Get $get) {
                                if (empty($get('slug')) || $get('slug') === \Illuminate\Support\Str::slug($state)) {
                                    $set('slug', \Illuminate\Support\Str::slug($state));
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
                Grid::make(2)->schema([
                    TextInput::make('slug')->label('Slug (URL)')->required()->unique(ignoreRecord: true),
                    DateTimePicker::make('published_at')->label('Data di Pubblicazione'),
                    Select::make('cover_media_id')->label('Copertina')
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search) =>
                            \App\Models\Media::query()
                                ->where('type', 'image')
                                ->where(function ($q) use ($search) {
                                    $q->where('alt', 'like', "%{$search}%")
                                      ->orWhere('public_id', 'like', "%{$search}%");
                                })
                                ->limit(20)
                                ->get()
                                ->mapWithKeys(fn ($media) => [$media->id => $media->alt ?: "Media #{$media->id}"])
                                ->toArray()
                        )
                        ->getOptionLabelUsing(fn ($value) => 
                            ($media = \App\Models\Media::find($value)) 
                                ? ($media->alt ?: "Media #{$media->id}") 
                                : "Media"
                        )
                        ->createOptionForm([
                            FileUpload::make('file')->label('Carica File')->image()->storeFiles(false)->required(),
                            TextInput::make('alt')->label('Testo Alternativo'),
                        ])
                        ->createOptionUsing(function (array $data) {
                            $media = app(\App\Services\MediaManager::class)->upload($data['file']);
                            if (!empty($data['alt'])) {
                                $media->update(['alt' => $data['alt']]);
                            }
                            return $media->id;
                        })->reactive(),
                    \Filament\Forms\Components\Placeholder::make('cover_preview')
                        ->label('Anteprima Copertina')
                        ->content(function (\Filament\Schemas\Components\Utilities\Get $get) {
                            $mediaId = $get('cover_media_id');
                            if (!$mediaId) return null;
                            $m = \App\Models\Media::find($mediaId);
                            if (!$m) return null;
                            return new \Illuminate\Support\HtmlString('<img src="'.$m->optimizedUrl('small').'" style="max-height: 150px; border-radius: 8px; object-fit: contain;">');
                        }),
                    \App\Filament\Components\MediaUpload::make('gallery_files', 'gallery')
                        ->label('Galleria Immagini/Video (File Locali)')
                        ->columnSpanFull(),

                    \App\Filament\Components\MediaUpload::make('attachments_files', 'attachments', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/x-zip-compressed'])
                        ->label('Allegati Scaricabili (PDF/Doc/Zip)')
                        ->columnSpanFull(),
                    Select::make('status')->label('Stato')->options(['draft' => 'Bozza', 'published' => 'Pubblicato', 'archived' => 'Archiviato'])->default('draft')->required(),
                    Select::make('translation_status')->label('Stato Traduzione')->options(['draft' => 'Bozza', 'missing' => 'Mancante', 'reviewed' => 'Revisionato'])->default('missing')->required(),
                ]),
            ]);
    }
}
