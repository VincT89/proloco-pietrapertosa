<?php

namespace App\Filament\Components;

use App\Models\Media;
use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Pagination\LengthAwarePaginator;

class MediaLibrarySelection extends Field
{
    protected string $view = 'filament.components.media-library-selection';

    protected bool $multiple = false;

    protected array $types = ['image'];

    protected array $mimeTypes = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->default([])->rules(['array']);
    }

    public function multiple(bool $multiple): static
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    public function mediaTypes(array $types): static
    {
        $this->types = $types;

        return $this;
    }

    public function mimeTypes(array $mimeTypes): static
    {
        $this->mimeTypes = $mimeTypes;

        return $this;
    }

    public function getMediaPage(): LengthAwarePaginator
    {
        return $this->evaluate(function (Get $get) {
            return Media::whereIn('type', $this->types)->acceptedDocuments($this->mimeTypes)->searchLibrary((string) $get('search'))
                ->orderByDesc('id')->paginate(18, ['*'], 'page', max(1, (int) $get('page')));
        });
    }

    public function getPageStatePath(): string
    {
        return $this->getContainer()->getStatePath().'.page';
    }
}
