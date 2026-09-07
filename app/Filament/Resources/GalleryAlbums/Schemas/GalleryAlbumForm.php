<?php

namespace App\Filament\Resources\GalleryAlbums\Schemas;

use App\Filament\Components\MediaUpload;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class GalleryAlbumForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Lingue')->tabs([
                    Tab::make('Italiano')->schema([
                        TextInput::make('title')->label('Titolo')->required(),
                    ]),
                    Tab::make('Inglese')->schema([
                        TextInput::make('title_en')->label('Titolo (EN)')->default(null)
                            ->hintAction(Action::make('copy')->label('Copia dall’italiano')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('title_en', $get('title')))),
                    ]),
                ])->columnSpanFull(),
                Grid::make(2)->columnSpanFull()->schema([
                    MediaUpload::make('gallery_files', 'gallery')
                        ->label('Galleria immagini e video')
                        ->columnSpanFull(),

                    DatePicker::make('section_date')->label('Data album'),
                    TextInput::make('sort_order')->label('Ordine')->required()->integer()->default(0)
                        ->helperText('I numeri più bassi compaiono prima. A parità di ordine, vengono prima gli album con la data più recente.'),
                    Select::make('translation_status')->label('Stato Traduzione')->options(['draft' => 'Bozza', 'missing' => 'Mancante', 'reviewed' => 'Revisionato'])->default('missing')->required(),
                ]),
            ]);
    }
}
