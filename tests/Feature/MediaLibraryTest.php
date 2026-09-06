<?php

namespace Tests\Feature;

use App\Exceptions\MediaUploadException;
use App\Filament\Components\MediaPicker;
use App\Filament\Components\MediaRichEditor;
use App\Filament\Resources\DirectoryItems\Pages\CreateDirectoryItem;
use App\Filament\Resources\DirectoryItems\Pages\EditDirectoryItem;
use App\Filament\Resources\Events\Pages\EditEvent;
use App\Filament\Resources\GalleryAlbums\Pages\CreateGalleryAlbum;
use App\Filament\Resources\GalleryAlbums\Pages\EditGalleryAlbum;
use App\Filament\Resources\Media\Pages\ListMedia;
use App\Filament\Resources\News\Pages\EditNews;
use App\Models\DirectoryItem;
use App\Models\Event;
use App\Models\GalleryAlbum;
use App\Models\Media;
use App\Models\News;
use App\Models\PageSetting;
use App\Models\User;
use App\Services\CloudinaryService;
use App\Services\MediaFingerprint;
use App\Services\MediaManager;
use App\Services\MediaUsage;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class MediaLibraryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(['email' => 'admin@prolocopietrapertosana.it']));
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        Http::preventStrayRequests();
    }

    private function media(array $attributes = []): Media
    {
        return Media::create(array_replace([
            'type' => 'image', 'provider' => 'local', 'public_id' => (string) Str::uuid(),
            'url' => '/images/castello.jpg', 'original_name' => 'castello.jpg',
        ], $attributes));
    }

    private function mockUpload(int $times = 1): void
    {
        $this->mock(CloudinaryService::class, function ($mock) use ($times) {
            $mock->shouldReceive('uploadMedia')->times($times)->andReturnUsing(fn () => [
                'public_id' => (string) Str::uuid(), 'secure_url' => 'https://res.cloudinary.com/test/image/upload/'.Str::uuid().'.jpg',
                'resource_type' => 'image', 'format' => 'jpg',
            ]);
        });
    }

    public function test_library_accepts_a_livewire_temporary_upload_before_saving_the_action(): void
    {
        $this->mockUpload();
        $upload = UploadedFile::fake()->createWithContent('foto.png', file_get_contents(public_path('favicon-96x96.png')));
        Livewire::test(ListMedia::class)
            ->mountAction('upload')
            ->set('mountedActions.0.data.files', [$upload])
            ->callMountedAction()
            ->assertHasNoActionErrors()
            ->assertNotified('File aggiunti alla libreria');
        $this->assertDatabaseHas('media', ['original_name' => 'foto.png']);
    }

    public function test_new_gallery_album_uploads_photos_saves_them_and_displays_them_publicly(): void
    {
        $this->mockUpload(2);
        $first = UploadedFile::fake()->createWithContent('prima.png', file_get_contents(public_path('favicon-48x48.png')));
        $second = UploadedFile::fake()->createWithContent('seconda.png', file_get_contents(public_path('favicon-96x96.png')));
        Livewire::test(CreateGalleryAlbum::class)
            ->fillForm(['title' => 'Album di verifica'])
            ->mountAction(TestAction::make('uploadMedia')->schemaComponent('gallery_files'))
            ->set('mountedActions.0.data.files', [$first, $second])
            ->callMountedAction()->assertHasNoActionErrors()
            ->call('create')->assertHasNoFormErrors();

        $album = GalleryAlbum::where('title', 'Album di verifica')->firstOrFail();
        $this->assertSame(['prima.png', 'seconda.png'], $album->galleryMedia->pluck('original_name')->all());
        $this->get('/it/galleria')->assertOk()->assertSee('Album di verifica')->assertSee('Apri foto 2');
    }

    public function test_edit_gallery_upload_keeps_previous_photos_and_reuses_renamed_duplicates(): void
    {
        $this->mockUpload();
        $bytes = file_get_contents(public_path('favicon-96x96.png'));
        $existing = $this->media(['content_hash' => hash('sha256', $bytes), 'original_name' => 'esistente.png']);
        $album = GalleryAlbum::create(['title' => 'Album esistente']);
        $album->galleryMedia()->attach($existing->id, ['collection' => 'gallery', 'order' => 0]);
        Livewire::test(EditGalleryAlbum::class, ['record' => $album->id])
            ->mountAction(TestAction::make('uploadMedia')->schemaComponent('gallery_files'))
            ->set('mountedActions.0.data.files', [
                UploadedFile::fake()->createWithContent('rinominata.png', $bytes),
                UploadedFile::fake()->createWithContent('nuova.png', file_get_contents(public_path('favicon-48x48.png'))),
            ])
            ->callMountedAction()->assertHasNoActionErrors()
            ->call('save')->assertHasNoFormErrors();
        $this->assertSame(['esistente.png', 'nuova.png'], $album->fresh()->galleryMedia->pluck('original_name')->all());
        $this->assertDatabaseCount('media', 2);
    }

    public function test_library_rejects_a_document_over_ten_mb_before_calling_cloudinary(): void
    {
        $this->mock(CloudinaryService::class, fn ($mock) => $mock->shouldNotReceive('uploadMedia'));
        Livewire::test(ListMedia::class)->mountAction('upload')
            ->set('mountedActions.0.data.files', [UploadedFile::fake()->create('documento.pdf', 10241, 'application/pdf')])
            ->callMountedAction()->assertHasActionErrors(['files']);
        $this->assertDatabaseCount('media', 0);
    }

    public function test_an_empty_upload_does_not_report_success(): void
    {
        $this->expectException(MediaUploadException::class);
        MediaPicker::uploadFiles([]);
    }

    public function test_an_expired_file_has_an_actionable_error(): void
    {
        $file = UploadedFile::fake()->createWithContent('temporaneo.png', 'test');
        unlink($file->getRealPath());
        $this->expectException(MediaUploadException::class);
        $this->expectExceptionMessage('selezionalo di nuovo');
        app(MediaFingerprint::class)->forUpload($file);
    }

    public function test_upload_errors_do_not_expose_service_configuration(): void
    {
        $this->assertStringNotContainsString('secret-value', MediaPicker::uploadErrorMessage(new \InvalidArgumentException('api_secret=secret-value')));
    }

    public function test_identical_renamed_upload_reuses_media_and_preserves_its_description(): void
    {
        $this->mockUpload();
        $manager = app(MediaManager::class);
        $first = $manager->upload(UploadedFile::fake()->createWithContent('original.jpg', 'identical bytes'));
        $first->update(['alt' => 'Descrizione già usata sul sito']);
        $second = $manager->upload(UploadedFile::fake()->createWithContent('renamed.jpg', 'identical bytes'));
        $this->assertSame($first->id, $second->id);
        $this->assertSame('original.jpg', $second->original_name);
        $this->assertSame('Descrizione già usata sul sito', $second->alt);
        $this->assertDatabaseCount('media', 1);
    }

    public function test_different_files_with_the_same_name_are_both_saved(): void
    {
        $this->mockUpload(2);
        $manager = app(MediaManager::class);
        $a = $manager->upload(UploadedFile::fake()->createWithContent('photo.jpg', 'first photo'));
        $b = $manager->upload(UploadedFile::fake()->createWithContent('photo.jpg', 'second photo'));
        $this->assertNotSame($a->id, $b->id);
        $this->assertDatabaseCount('media', 2);
    }

    public function test_indexing_existing_local_file_prevents_another_upload(): void
    {
        $existing = $this->media(['original_name' => null, 'metadata' => ['original_name' => 'castello-originale.jpg']]);
        $this->artisan('media:index')->assertSuccessful();
        $this->mock(CloudinaryService::class, fn ($mock) => $mock->shouldNotReceive('uploadMedia'));
        $file = UploadedFile::fake()->createWithContent('renamed.jpg', file_get_contents(public_path('images/castello.jpg')));
        $reused = app(MediaManager::class)->upload($file);
        $this->assertSame($existing->id, $reused->id);
        $this->assertSame('castello-originale.jpg', $reused->original_name);
    }

    public function test_fingerprint_rejects_untrusted_urls_and_local_traversal(): void
    {
        $service = app(MediaFingerprint::class);
        $this->assertNull($service->localPath('/images/../../.env'));
        $this->expectException(\RuntimeException::class);
        $service->forUrl('http://169.254.169.254/latest/meta-data/');
    }

    public function test_gallery_selection_from_library_is_saved_once_in_the_chosen_order(): void
    {
        $first = $this->media();
        $second = $this->media(['url' => '/images/torre.jpg', 'original_name' => 'torre.jpg']);
        Livewire::test(CreateDirectoryItem::class)
            ->fillForm(['title' => 'Scheda di prova', 'category' => 'scopri_luoghi'])
            ->callAction(TestAction::make('chooseMedia')->schemaComponent('gallery_files'), data: [
                'selection' => [$second->id, $first->id, $first->id], 'search' => '', 'page' => 1,
            ])->assertHasNoActionErrors()
            ->call('create')->assertHasNoFormErrors();
        $record = DirectoryItem::firstOrFail();
        $this->assertSame([$second->id, $first->id], $record->galleryMedia()->pluck('media.id')->all());
        $this->assertSame(0, DB::table('mediables')->whereNull('collection')->count());
    }

    public function test_removing_a_photo_from_a_gallery_keeps_the_library_and_other_uses(): void
    {
        $photo = $this->media();
        $record = DirectoryItem::create(['title' => 'Prova', 'category' => 'comunita']);
        $record->galleryMedia()->attach($photo->id, ['collection' => 'gallery', 'order' => 0]);
        $album = GalleryAlbum::create(['title' => 'Album di prova']);
        $album->galleryMedia()->attach($photo->id, ['collection' => 'gallery', 'order' => 0]);
        Livewire::test(EditDirectoryItem::class, ['record' => $record->id])
            ->fillForm(['gallery_files' => []])->call('save')->assertHasNoFormErrors();
        $this->assertCount(0, $record->fresh()->galleryMedia);
        $this->assertCount(1, $album->fresh()->galleryMedia);
        $this->assertModelExists($photo);
    }

    public function test_album_rejects_documents_as_gallery_photos(): void
    {
        $document = $this->media(['type' => 'document']);
        Livewire::test(CreateGalleryAlbum::class)->fillForm(['title' => 'Prova', 'gallery_files' => [$document->id]])
            ->call('create')->assertHasFormErrors(['gallery_files']);
        $this->assertDatabaseCount('gallery_albums', 0);
    }

    public function test_upload_action_adds_new_files_to_the_existing_selection_and_saves_them(): void
    {
        $this->mockUpload();
        $existing = $this->media();
        $album = GalleryAlbum::create(['title' => 'Album di prova']);
        $album->galleryMedia()->attach($existing->id, ['collection' => 'gallery', 'order' => 0]);
        $upload = UploadedFile::fake()->createWithContent('nuova.png', file_get_contents(public_path('favicon-96x96.png')));
        Livewire::test(EditGalleryAlbum::class, ['record' => $album->id])
            ->callAction(TestAction::make('uploadMedia')->schemaComponent('gallery_files'), data: ['files' => [$upload]])
            ->assertHasNoActionErrors()->call('save')->assertHasNoFormErrors();
        $this->assertCount(2, $album->fresh()->galleryMedia);
        $this->assertSame('nuova.png', $album->fresh()->galleryMedia->last()->original_name);
    }

    public function test_usage_finds_covers_nested_page_images_and_editor_images(): void
    {
        $photo = $this->media();
        News::create(['title' => 'Notizia di prova', 'slug' => 'prova', 'content' => '<p><img src="/images/castello.jpg"></p>', 'cover_media_id' => $photo->id]);
        PageSetting::create(['page_slug' => 'home', 'data' => ['discover_items' => [['img_media_id' => $photo->id]]]]);
        $uses = app(MediaUsage::class)->forMedia(collect([$photo]))[$photo->id];
        $this->assertCount(3, $uses);
        $this->assertTrue($photo->isInUse());
        $this->mock(CloudinaryService::class, fn ($mock) => $mock->shouldNotReceive('deleteMedia'));
        $this->assertFalse(app(MediaManager::class)->delete($photo));
    }

    public function test_page_usage_does_not_match_a_different_id_prefix(): void
    {
        $photo = $this->media(['id' => 1]);
        PageSetting::create(['page_slug' => 'home', 'data' => ['discover_items' => [['img_media_id' => 10]]]]);
        $this->assertFalse($photo->isInUse());
    }

    public function test_library_search_uses_the_original_filename_even_with_a_different_alt(): void
    {
        $photo = $this->media(['original_name' => 'panorama-originale.jpg', 'alt' => 'Vista del borgo']);
        $this->assertSame([$photo->id], Media::searchLibrary('panorama-originale')->pluck('id')->all());
        Livewire::test(ListMedia::class)->searchTable('panorama-originale')->assertCanSeeTableRecords([$photo]);
    }

    public function test_library_picker_can_browse_older_photos_without_losing_the_selection(): void
    {
        $oldest = $this->media(['original_name' => 'foto-meno-recente.jpg']);
        for ($index = 0; $index < 18; $index++) {
            $latest = $this->media(['original_name' => "foto-recente-{$index}.jpg"]);
        }

        Livewire::test(CreateGalleryAlbum::class)
            ->mountAction(TestAction::make('chooseMedia')->schemaComponent('gallery_files'))
            ->assertMountedActionModalSee('Pagina 1 di 2')
            ->assertMountedActionModalSee($latest->original_name)->assertMountedActionModalDontSee($oldest->original_name)
            ->set('mountedActions.0.data.selection', [$latest->id])
            ->set('mountedActions.0.data.page', 2)
            ->assertMountedActionModalSee('Pagina 2 di 2')
            ->assertMountedActionModalSee($oldest->original_name)->assertMountedActionModalDontSee($latest->original_name)
            ->assertSet('mountedActions.0.data.selection', [$latest->id]);
    }

    public function test_library_picker_search_resets_the_page_and_finds_older_photos(): void
    {
        $oldest = $this->media(['original_name' => 'panorama-del-borgo.jpg', 'alt' => 'Vista del castello']);
        for ($index = 0; $index < 18; $index++) {
            $latest = $this->media(['original_name' => "foto-recente-{$index}.jpg"]);
        }

        $event = Event::create(['title' => 'Evento di prova', 'slug' => 'prova-libreria', 'start_date' => now()]);
        Livewire::test(EditEvent::class, ['record' => $event->id])
            ->mountAction(TestAction::make('chooseMedia')->schemaComponent('gallery_files'))
            ->set('mountedActions.0.data.page', 2)
            ->set('mountedActions.0.data.selection', [$latest->id])
            ->set('mountedActions.0.data.search', 'panorama-del-borgo')
            ->assertSet('mountedActions.0.data.page', 1)
            ->assertMountedActionModalSee($oldest->original_name)->assertMountedActionModalDontSee($latest->original_name)
            ->assertSet('mountedActions.0.data.selection', [$latest->id]);
    }

    public function test_rich_editor_resolves_central_library_images(): void
    {
        $photo = $this->media();
        $this->assertSame($photo->url, MediaRichEditor::make('content')->getDefaultFileAttachmentUrl('media:'.$photo->id));
    }

    public function test_pdf_picker_only_accepts_pdf_documents(): void
    {
        $pdf = $this->media(['type' => 'document', 'metadata' => ['mime_type' => 'application/pdf']]);
        $legacyPdf = $this->media(['type' => 'document', 'metadata' => ['format' => 'pdf']]);
        $zip = $this->media(['type' => 'document', 'metadata' => ['mime_type' => 'application/zip', 'format' => 'pdf']]);
        $picker = MediaPicker::make('media_id')->acceptedFiles(['application/pdf']);
        $this->assertTrue($picker->validSelection($pdf->id));
        $this->assertTrue($picker->validSelection($legacyPdf->id));
        $this->assertFalse($picker->validSelection($zip->id));
        $this->assertSame([$pdf->id, $legacyPdf->id], Media::where('type', 'document')->acceptedDocuments(['application/pdf'])->pluck('id')->all());
    }

    public function test_saving_news_keeps_gallery_and_attachments_in_separate_collections(): void
    {
        $photo = $this->media();
        $pdf = $this->media(['type' => 'document', 'metadata' => ['mime_type' => 'application/pdf']]);
        $news = News::create(['title' => 'Notizia di prova', 'slug' => 'prova', 'content' => '<p>Testo di prova.</p>']);
        Livewire::test(EditNews::class, ['record' => $news->id])
            ->fillForm(['gallery_files' => [$photo->id], 'attachments_files' => [$pdf->id]])
            ->call('save')->assertHasNoFormErrors();
        $this->assertSame([$photo->id], $news->fresh()->galleryMedia()->pluck('media.id')->all());
        $this->assertSame([$pdf->id], $news->fresh()->attachmentsMedia()->pluck('media.id')->all());
    }

    public function test_indexing_preserves_legacy_duplicates_and_is_repeatable(): void
    {
        $first = $this->media();
        $second = $this->media(['url' => url('/images/castello.jpg')]);
        $album = GalleryAlbum::create(['title' => 'Album di prova']);
        $album->galleryMedia()->attach($second->id, ['collection' => 'gallery', 'order' => 0]);
        $this->artisan('media:index')->assertSuccessful();
        $this->artisan('media:index')->assertSuccessful();
        $this->assertDatabaseCount('media', 2);
        $this->assertSame($first->fresh()->content_hash, $second->fresh()->content_hash);
        $this->assertSame([$second->id], $album->fresh()->galleryMedia()->pluck('media.id')->all());
    }

    public function test_importing_public_images_reuses_an_existing_file_with_a_relative_url(): void
    {
        $existing = $this->media();
        $this->artisan('media:index', ['--import-public-images' => true])->assertSuccessful();
        $this->assertSame(1, Media::where('content_hash', $existing->fresh()->content_hash)->count());
        $count = Media::count();
        $this->artisan('media:index', ['--import-public-images' => true])->assertSuccessful();
        $this->assertDatabaseCount('media', $count);
    }
}
