<?php

namespace App\Filament\Resources\DirectoryItems\Schemas;

use App\Models\Media;
use App\Services\CloudinaryService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Http\UploadedFile;

class DirectoryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Lingue')->tabs([
                    Tab::make('Italiano')->schema([
                        TextInput::make('title')->label('Titolo')->required(),
                        TextInput::make('subtitle')->label('Sottotitolo')->default(null),
                        RichEditor::make('description')->label('Descrizione')->default(null)->columnSpanFull(),
                        Textarea::make('contact_info')->label('Info Contatti')->default(null)->columnSpanFull(),
                    ]),
                    Tab::make('Inglese')->schema([
                        TextInput::make('title_en')->label('Titolo (EN)')->default(null)
                            ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('title_en', $get('title')))),
                        TextInput::make('subtitle_en')->label('Sottotitolo (EN)')->default(null)
                            ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('subtitle_en', $get('subtitle')))),
                        RichEditor::make('description_en')->label('Descrizione (EN)')->default(null)->columnSpanFull(),
                        Textarea::make('contact_info_en')->label('Info Contatti (EN)')->default(null)->columnSpanFull()
                            ->hintAction(Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('contact_info_en', $get('contact_info')))),
                    ]),
                ])->columnSpanFull(),
                Grid::make(2)->schema([
                    Select::make('category')->label('Categoria')
                        ->options([
                            'comunita' => 'Comunità',
                            'eccellenze_aziende' => 'Eccellenze - Aziende Agricole',
                            'eccellenze_foodtruck' => 'Eccellenze - Food Truck',
                            'eccellenze_artigiani' => 'Eccellenze - Artigiani',
                            'sapori_piatti' => 'Sapori - Piatti Tipici',
                            'eventi_annuali' => 'Eventi Annuali',
                            'scopri_luoghi' => 'Scopri - Luoghi',

                        ])
                        ->searchable()
                        ->required(),
                    Select::make('galleryMedia')->label('Scegli Immagini da Libreria Esistente')
                        ->relationship('galleryMedia', 'name')
                        ->multiple()
                        ->searchable()
                        ->getOptionLabelFromRecordUsing(fn (\App\Models\Media $record) => $record->alt ?: "Media #{$record->id}")
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
                        ->reactive(),
                    \Filament\Forms\Components\Placeholder::make('galleryMedia_preview')
                        ->label('Anteprima Selezionati')
                        ->content(function (\Filament\Schemas\Components\Utilities\Get $get) {
                            $mediaIds = $get('galleryMedia');
                            if (!$mediaIds || !is_array($mediaIds) || count($mediaIds) === 0) return null;
                            $media = \App\Models\Media::whereIn('id', $mediaIds)->get();
                            $html = '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
                            foreach ($media as $m) {
                                $html .= '<img src="'.$m->optimizedUrl('small').'" style="max-height: 80px; border-radius: 4px; object-fit: cover;">';
                            }
                            $html .= '</div>';
                            return new \Illuminate\Support\HtmlString($html);
                        }),
                    FileUpload::make('gallery_files')->label('Galleria Immagini/Video (File Locali)')
                        ->multiple()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'video/mp4', 'video/quicktime', 'video/webm'])
                        ->maxSize(10240)
                        ->helperText(new \Illuminate\Support\HtmlString(
                            'Prima del caricamento è consigliato comprimere le immagini o convertirle in JPG/WebP. 
                            Dimensione consigliata: 500 KB - 2 MB. Evitare file superiori a 10 MB. 
                            Puoi usare <a href="https://www.iloveimg.com/it" target="_blank" rel="noopener noreferrer" style="text-decoration: underline;">iLoveIMG</a>.'
                        ))
                        ->getUploadedFileUsing(function (string $file): ?array {
                            return [
                                'name' => basename($file),
                                'size' => 0,
                                'type' => preg_match('/\.(mp4|webm|mov)$/i', $file) ? 'video/mp4' : 'image/jpeg',
                                'url' => $file,
                            ];
                        })
                        ->saveUploadedFileUsing(function (UploadedFile $file) {
                            $media = app(\App\Services\MediaManager::class)->upload($file);
                            \Illuminate\Support\Facades\Cache::put('last_upload_'.$media->url, $media->id, 300);
                            return $media->url;
                        })
                        ->deleteUploadedFileUsing(function (string $file) {
                            $mediaId = \Illuminate\Support\Facades\Cache::get('last_upload_'.$file);
                            if (! $mediaId) return;

                            $media = Media::find($mediaId);
                            if ($media) {
                                app(\App\Services\MediaManager::class)->delete($media);
                            }
                            \Illuminate\Support\Facades\Cache::forget('last_upload_'.$file);
                        })
                        ->afterStateHydrated(function ($component, $record) {
                            if ($record) {
                                $component->state($record->galleryMedia->pluck('url')->toArray());
                            }
                        })
                        ->dehydrated(false)
                        ->saveRelationshipsUsing(function ($record, $state) {
                            $syncData = [];
                            if (is_array($state)) {
                                foreach (array_values($state) as $index => $url) {
                                    $media = Media::where('url', $url)->first();
                                    if ($media) {
                                        $syncData[$media->id] = ['order' => $index, 'collection' => 'gallery'];
                                    }
                                }
                            }
                            $record->galleryMedia()->sync($syncData);
                        })
                        ->columnSpanFull(),

                    KeyValue::make('stats')->label('Statistiche')->default(null)->columnSpanFull(),
                    TextInput::make('sort_order')->label('Ordine')->required()->numeric()->default(0),
                    Select::make('translation_status')->label('Stato Traduzione')->options(['draft' => 'Bozza', 'missing' => 'Mancante', 'reviewed' => 'Revisionato'])->default('missing')->required(),
                ]),
            ]);
    }
}
