<?php

namespace App\Filament\Components;

use App\Exceptions\MediaUploadException;
use App\Models\Media;
use App\Rules\MediaUploadRule;
use App\Services\MediaFingerprint;
use App\Services\MediaManager;
use Filament\Actions\Action;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Enums\Width;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class MediaPicker extends Field
{
    protected string $view = 'filament.components.media-picker';

    protected bool $multiple = false;

    protected array $mediaTypes = ['image'];

    protected array $mimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    protected int $uploadLimit = 10240;

    protected function setUp(): void
    {
        parent::setUp();
        $this->live()->columnSpanFull()->default(fn (self $component) => $component->isMultiple() ? [] : null);
        $this->rules([fn (self $component) => function ($attribute, $value, $fail) use ($component) {
            if (! $component->validSelection($value)) {
                $fail('Seleziona file disponibili nella libreria e adatti a questo campo.');
            }
        }]);
        $this->registerActions([
            Action::make('chooseMedia')->label('Scegli dalla libreria')
                ->modalHeading('Scegli dalla libreria')->modalWidth(Width::FiveExtraLarge)->stickyModalHeader()->stickyModalFooter()
                ->modalSubmitActionLabel('Usa la selezione')
                ->fillForm(fn (self $component) => [
                    'selection' => $component->selectedIds(), 'search' => '', 'page' => 1,
                ])
                ->schema(fn (self $component) => [
                    TextInput::make('search')->label('Cerca per nome o descrizione')
                        ->live(debounce: 350)->afterStateUpdated(fn (Set $set) => $set('page', 1)),
                    Hidden::make('page')->default(1),
                    MediaLibrarySelection::make('selection')->label('File disponibili')->hiddenLabel()
                        ->multiple($component->isMultiple())->mediaTypes($component->getMediaTypes())
                        ->mimeTypes($component->getMimeTypes()),
                ])
                ->action(function (array $data, self $component) {
                    $selection = $data['selection'] ?? [];
                    $value = $component->isMultiple() ? $selection : ($selection[0] ?? null);
                    if (! $component->validSelection($value)) {
                        throw ValidationException::withMessages(['selection' => 'La selezione contiene file non disponibili.']);
                    }
                    $component->state($value);
                    $component->callAfterStateUpdated();
                }),
            Action::make('uploadMedia')->label('Carica nuovi file')
                ->modalHeading('Carica e scegli i file')->modalWidth(Width::ThreeExtraLarge)->stickyModalFooter()
                ->modalSubmitActionLabel('Usa i file selezionati')
                ->schema(fn (self $component) => self::uploadSchema(
                    $component->getMimeTypes(), $component->getUploadLimit(), $component->isMultiple()
                ))
                ->action(function (array $data, self $component, Action $action) {
                    try {
                        $media = self::uploadFiles($data['files'] ?? []);
                    } catch (\Throwable $exception) {
                        report($exception);
                        Notification::make()->danger()->title('Caricamento non riuscito')
                            ->body(self::uploadErrorMessage($exception))->send();
                        $action->halt();

                        return;
                    }
                    $ids = $media->pluck('id')->all();
                    $component->state($component->isMultiple()
                        ? array_values(array_unique([...$component->selectedIds(), ...$ids]))
                        : ($ids[0] ?? null));
                    $component->callAfterStateUpdated();
                    self::notifyUpload($media);
                }),
        ]);
    }

    public function multiple(bool $multiple = true): static
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    public function getMediaTypes(): array
    {
        return $this->mediaTypes;
    }

    public function getMimeTypes(): array
    {
        return $this->mimeTypes;
    }

    public function getUploadLimit(): int
    {
        return $this->uploadLimit;
    }

    public function acceptedFiles(array $mimeTypes, int $limit = 10240): static
    {
        $this->mimeTypes = $mimeTypes;
        $this->uploadLimit = $limit;
        $this->mediaTypes = array_values(array_unique(array_map(fn ($mime) => str_starts_with($mime, 'image/') ? 'image' : (str_starts_with($mime, 'video/') ? 'video' : 'document'), $mimeTypes)));

        return $this;
    }

    public function collection(string $collection): static
    {
        return $this->multiple()->dehydrated(false)
            ->afterStateHydrated(function (self $component, $record) use ($collection) {
                $relation = $collection.'Media';
                $component->state($record ? $record->$relation()->pluck('media.id')->all() : []);
            })
            ->saveRelationshipsUsing(function (self $component, $record) use ($collection) {
                if (! $component->validSelection($component->getState())) {
                    throw ValidationException::withMessages([$component->getStatePath() => 'La selezione contiene file non disponibili.']);
                }
                $links = [];
                foreach ($component->selectedIds() as $order => $id) {
                    $links[$id] = ['collection' => $collection, 'order' => $order];
                }
                $record->{$collection.'Media'}()->sync($links);
                $record->unsetRelation($collection.'Media');
            });
    }

    public function selectedIds(): array
    {
        return array_values(array_unique(array_map('intval', array_filter(
            (array) ($this->getState() ?? []), fn ($id) => is_scalar($id) && ctype_digit((string) $id) && (int) $id > 0
        ))));
    }

    public function validSelection(mixed $value): bool
    {
        if ($value === null || $value === [] || $value === '') {
            return true;
        }
        if ($this->isMultiple() !== is_array($value)) {
            return false;
        }
        $ids = (array) $value;
        foreach ($ids as $id) {
            if (! is_scalar($id) || ! ctype_digit((string) $id) || (int) $id < 1) {
                return false;
            }
        }

        return Media::whereIn('id', $ids)->whereIn('type', $this->mediaTypes)
            ->acceptedDocuments($this->mimeTypes)->count() === count(array_unique($ids));
    }

    public function getSelectedMedia(): Collection
    {
        $ids = $this->selectedIds();
        $media = Media::whereIn('id', $ids)->get()->keyBy('id');

        return collect($ids)->map(fn ($id) => $media->get($id))->filter();
    }

    public static function uploadSchema(array $mimeTypes, int $limit, bool $multiple): array
    {
        return [
            FileUpload::make('files')->label('File dal computer')->multiple($multiple)
                ->acceptedFileTypes($mimeTypes)->maxSize($limit)->maxFiles($multiple ? 20 : 1)
                ->storeFiles(false)->required()->live()->imagePreviewHeight(140)
                ->rules([new MediaUploadRule])
                ->helperText(($multiple ? 'Fino a 20 file alla volta. ' : '').'Immagini e documenti: massimo 10 MB per file. Video: massimo 100 MB, nei campi che li accettano.'),
            Placeholder::make('duplicate_preview')->hiddenLabel()
                ->content(function (Get $get) {
                    if (! Schema::hasColumn('media', 'content_hash')) {
                        return view('filament.components.media-duplicates', [
                            'matches' => collect(), 'pending' => false,
                            'error' => 'La libreria richiede un aggiornamento del database. Esegui le migrazioni prima di caricare nuovi file.',
                        ]);
                    }
                    $files = $get('files');
                    $matches = collect(is_array($files) ? $files : [$files])->filter(fn ($file) => $file instanceof UploadedFile)
                        ->map(function ($file) {
                            try {
                                return app(MediaFingerprint::class)->findUpload($file);
                            } catch (MediaUploadException) {
                                // Let field validation explain expired uploads instead of breaking the modal render.
                                return null;
                            }
                        })->filter()->unique('id');

                    return view('filament.components.media-duplicates', [
                        'matches' => $matches,
                        'pending' => Media::whereNull('content_hash')->whereIn('provider', ['local', 'cloudinary'])->exists(),
                    ]);
                }),
        ];
    }

    public static function uploadFiles(mixed $files): Collection
    {
        $uploads = collect(is_array($files) ? $files : [$files])->filter(fn ($file) => $file instanceof UploadedFile);
        if ($uploads->isEmpty()) {
            throw new MediaUploadException('Nessun file pronto per il caricamento. Attendi la fine del trasferimento o seleziona di nuovo il file.');
        }
        if (! Schema::hasColumn('media', 'content_hash')) {
            throw new MediaUploadException('La libreria richiede un aggiornamento del database. Esegui le migrazioni prima di caricare nuovi file.');
        }

        return $uploads
            ->map(fn ($file) => app(MediaManager::class)->upload($file));
    }

    public static function uploadErrorMessage(\Throwable $exception): string
    {
        return match (true) {
            $exception instanceof MediaUploadException => $exception->getMessage(),
            $exception instanceof LockTimeoutException => 'Un altro caricamento dello stesso file è in corso. Attendi qualche secondo e riprova.',
            $exception instanceof ConnectException => 'Il servizio immagini non è raggiungibile. I file selezionati sono conservati: riprova tra poco.',
            default => 'Il caricamento non è stato completato. I dettagli sono stati registrati nel log del server; puoi riprovare senza duplicare i file già salvati.',
        };
    }

    public static function notifyUpload(Collection $media): void
    {
        $reused = $media->contains(fn ($item) => ! $item->wasRecentlyCreated);
        Notification::make()->success()->title($reused ? 'File già presenti riutilizzati' : 'File aggiunti alla libreria')
            ->body($reused ? 'Le copie già presenti sono state selezionate senza caricarle nuovamente.' : null)->send();
    }
}
