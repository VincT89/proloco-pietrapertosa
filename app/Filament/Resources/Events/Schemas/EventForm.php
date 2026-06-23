<?php

namespace App\Filament\Resources\Events\Schemas;

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

class EventForm
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
                        RichEditor::make('description')->label('Descrizione')->default(null)->columnSpanFull(),
                        TextInput::make('location')->label('Luogo')->default(null),
                        TextInput::make('category')->label('Categoria')->default(null),
                        Textarea::make('seo_description')->label('Descrizione SEO')->default(null)->columnSpanFull(),
                    ]),
                    Tab::make('Inglese')->schema([
                        TextInput::make('title_en')->label('Titolo (EN)')->default(null)
                            ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('title_en', $get('title')))),
                        RichEditor::make('description_en')->label('Descrizione (EN)')->default(null)->columnSpanFull(),
                        TextInput::make('location_en')->label('Luogo (EN)')->default(null)
                            ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('location_en', $get('location')))),
                        TextInput::make('category_en')->label('Categoria (EN)')->default(null)
                            ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('category_en', $get('category')))),
                        Textarea::make('seo_description_en')->label('Descrizione SEO (EN)')->default(null)->columnSpanFull()
                            ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('seo_description_en', $get('seo_description')))),
                    ]),
                ])->columnSpanFull(),
                Grid::make(2)->schema([
                    TextInput::make('slug')->label('Slug (URL)')->required()->unique(ignoreRecord: true),
                    DateTimePicker::make('start_date')->label('Data Inizio')->required(),
                    DateTimePicker::make('end_date')->label('Data Fine'),
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

                    Select::make('status')->label('Stato')->options(['draft' => 'Bozza', 'published' => 'Pubblicato', 'cancelled' => 'Annullato'])->default('draft')->required(),
                    Select::make('translation_status')->label('Stato Traduzione')->options(['draft' => 'Bozza', 'missing' => 'Mancante', 'reviewed' => 'Revisionato'])->default('missing')->required(),
                ]),
            ]);
    }
}
