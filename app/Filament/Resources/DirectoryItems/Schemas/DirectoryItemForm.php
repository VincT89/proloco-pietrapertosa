<?php

namespace App\Filament\Resources\DirectoryItems\Schemas;

use App\Filament\Components\MediaRichEditor as RichEditor;
use App\Filament\Components\MediaUpload;
use Filament\Actions\Action;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

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
                            ->hintAction(Action::make('copy')->label('Copia dall’italiano')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('title_en', $get('title')))),
                        TextInput::make('subtitle_en')->label('Sottotitolo (EN)')->default(null)
                            ->hintAction(Action::make('copy')->label('Copia dall’italiano')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('subtitle_en', $get('subtitle')))),
                        RichEditor::make('description_en')->label('Descrizione (EN)')->default(null)->columnSpanFull(),
                        Textarea::make('contact_info_en')->label('Info Contatti (EN)')->default(null)->columnSpanFull()
                            ->hintAction(Action::make('copy')->label('Copia dall’italiano')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('contact_info_en', $get('contact_info')))),
                    ]),
                ])->columnSpanFull(),
                Grid::make(2)->columnSpanFull()->schema([
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
                    MediaUpload::make('gallery_files', 'gallery')
                        ->label('Galleria immagini e video')
                        ->columnSpanFull(),

                    KeyValue::make('stats')->label('Statistiche')->default(null)->columnSpanFull(),
                    TextInput::make('sort_order')->label('Ordine')->required()->integer()->default(0)
                        ->helperText('I numeri più bassi compaiono prima all’interno della stessa sezione del sito.'),
                    Select::make('translation_status')->label('Stato Traduzione')->options(['draft' => 'Bozza', 'missing' => 'Mancante', 'reviewed' => 'Revisionato'])->default('missing')->required(),
                ]),
            ]);
    }
}
