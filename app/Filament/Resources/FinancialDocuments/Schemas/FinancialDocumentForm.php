<?php

namespace App\Filament\Resources\FinancialDocuments\Schemas;

use App\Enums\DocumentType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FinancialDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Tabs::make('Translations')
                    ->tabs([
                        \Filament\Schemas\Components\Tabs\Tab::make('Italiano')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Titolo')
                                    ->required(),
                                Textarea::make('description')
                                    ->label('Descrizione')
                                    ->default(null)
                                    ->columnSpanFull(),
                            ]),
                        \Filament\Schemas\Components\Tabs\Tab::make('English')
                            ->schema([
                                TextInput::make('title_en')
                                    ->label('Title (EN)')
                                    ->default(null)
                                    ->hintAction(\Filament\Actions\Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('title_en', $get('title')))),
                                Textarea::make('description_en')
                                    ->label('Description (EN)')
                                    ->default(null)
                                    ->columnSpanFull()
                                    ->hintAction(\Filament\Actions\Action::make('copy')->icon('heroicon-m-document-duplicate')->action(fn ($set, $get) => $set('description_en', $get('description')))),
                            ]),
                    ])->columnSpanFull(),
                
                \Filament\Schemas\Components\Grid::make(2)
                    ->schema([
                        TextInput::make('year')
                            ->label('Anno')
                            ->numeric()
                            ->required()
                            ->default(date('Y')),
                        Select::make('type')
                            ->label('Tipo Documento')
                            ->options(DocumentType::class)
                            ->required(),
                        Select::make('media_id')
                            ->label('File PDF (Libreria Cloudinary)')
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search) =>
                                \App\Models\Media::query()
                                    ->where('type', 'document')
                                    ->orWhere('type', 'image')
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
                                \Filament\Forms\Components\FileUpload::make('file')
                                    ->label('Carica nuovo documento')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->storeFiles(false)
                                    ->required(),
                                \Filament\Forms\Components\TextInput::make('alt')
                                    ->label('Testo Alternativo (Nome interno)'),
                            ])
                            ->createOptionUsing(function (array $data) {
                                $media = app(\App\Services\MediaManager::class)->upload($data['file']);
                                if (!empty($data['alt'])) {
                                    $media->update(['alt' => $data['alt']]);
                                }
                                return $media->id;
                            })->reactive(),
                        \Filament\Forms\Components\Placeholder::make('media_preview')
                            ->label('Anteprima Documento')
                            ->content(function (\Filament\Schemas\Components\Utilities\Get $get) {
                                $mediaId = $get('media_id');
                                if (!$mediaId) return null;
                                $m = \App\Models\Media::find($mediaId);
                                if (!$m) return null;
                                if ($m->type === 'image') {
                                    return new \Illuminate\Support\HtmlString('<img src="'.$m->optimizedUrl('small').'" style="max-height: 150px; border-radius: 8px; object-fit: contain;">');
                                }
                                return new \Illuminate\Support\HtmlString('<a href="'.$m->url.'" target="_blank" style="color: var(--color-primary); text-decoration: underline; display: flex; align-items: center; gap: 8px;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Apri PDF</a>');
                            }),
                        
                        Toggle::make('is_published')
                            ->label('Pubblicato')
                            ->required()
                            ->columnSpanFull(),
                        DateTimePicker::make('published_at')
                            ->label('Data di pubblicazione'),
                        TextInput::make('sort_order')
                            ->label('Ordine')
                            ->numeric()
                            ->required()
                            ->default(0),
                    ]),
            ]);
    }
}
