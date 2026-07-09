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
                    \App\Filament\Components\MediaUpload::make('gallery_files', 'gallery')
                        ->label('Galleria Immagini/Video (File Locali)')
                        ->columnSpanFull(),

                    KeyValue::make('stats')->label('Statistiche')->default(null)->columnSpanFull(),
                    TextInput::make('sort_order')->label('Ordine')->required()->numeric()->default(0),
                    Select::make('translation_status')->label('Stato Traduzione')->options(['draft' => 'Bozza', 'missing' => 'Mancante', 'reviewed' => 'Revisionato'])->default('missing')->required(),
                ]),
            ]);
    }
}
