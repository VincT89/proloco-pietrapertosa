<?php

namespace Tests\Feature;

use App\Filament\Resources\News\Pages\ListNews;
use App\Filament\Support\ContentActions;
use App\Models\DirectoryItem;
use App\Models\Event;
use App\Models\GalleryAlbum;
use App\Models\News;
use App\Models\User;
use App\Repositories\Chatbot\ChatbotNewsRepository;
use App\Services\Chatbot\ChatbotSearchService;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use Tests\TestCase;

class ContentAdministrationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        return User::factory()->create(['email' => 'admin@prolocopietrapertosana.it']);
    }

    public function test_scheduled_news_stays_private_everywhere_until_its_publication_time(): void
    {
        $this->travelTo(now()->setDate(2026, 9, 7)->setTime(12, 0));
        $news = News::create([
            'title' => 'Notizia riservata domani', 'title_en' => 'Tomorrow announcement',
            'slug' => 'notizia-programmata', 'content' => 'Testo riservato domani',
            'status' => 'published', 'published_at' => now()->addDay(),
        ]);

        foreach (['/it', '/en', '/it/notizie', '/en/news'] as $url) {
            $this->get($url)->assertOk()->assertDontSee('notizia-programmata');
        }
        $this->get('/it/notizie/notizia-programmata')->assertNotFound();
        $this->get('/en/news/notizia-programmata')->assertNotFound();
        $this->get('/sitemap.xml')->assertOk()->assertDontSee('notizia-programmata');
        $this->assertEmpty(app(ChatbotNewsRepository::class)->getLatestNews());
        $this->assertEmpty(app(ChatbotNewsRepository::class)->searchNews('riservata', 'it'));
        $this->assertEmpty(app(ChatbotSearchService::class)->search('riservata', 'it'));
        $this->assertNull(ContentActions::publicUrl($news));

        $this->travelTo($news->published_at);
        $this->get('/it/notizie/notizia-programmata')->assertOk()->assertSee('Testo riservato domani');
        $this->get('/en/news/notizia-programmata')->assertOk()->assertSee('Tomorrow announcement');
        $this->get('/sitemap.xml')->assertSee('notizia-programmata');
        $this->assertSame($news->id, app(ChatbotNewsRepository::class)->getLatestNews()->first()->id);
        $this->assertNotNull(ContentActions::publicUrl($news));
    }

    public function test_news_without_a_date_remains_public_and_archived_news_remains_private(): void
    {
        $news = News::create(['title' => 'Avviso senza data', 'slug' => 'senza-data', 'status' => 'published', 'content' => 'Avviso di prova']);
        $this->get('/it/notizie/senza-data')->assertOk();
        $news->update(['status' => 'archived']);
        $this->get('/it/notizie/senza-data')->assertNotFound();
    }

    public function test_saved_previews_require_admin_access_and_do_not_publish_a_draft(): void
    {
        $news = News::create([
            'title' => 'Bozza privata', 'title_en' => 'Private draft',
            'slug' => 'bozza-privata', 'status' => 'draft', 'content' => '<p>Testo da verificare.</p>',
        ]);
        $itUrl = route('admin.content-preview', ['locale' => 'it', 'type' => 'news', 'record' => $news->id]);
        $enUrl = route('admin.content-preview', ['locale' => 'en', 'type' => 'news', 'record' => $news->id]);

        $this->get($itUrl)->assertRedirect(route('filament.admin.auth.login'));
        $this->actingAs(User::factory()->create())->get($itUrl)->assertForbidden();
        $this->actingAs($this->admin())->get($itUrl)->assertOk()
            ->assertSee('Testo da verificare.')
            ->assertSee('Anteprima dell’ultima versione salvata.')
            ->assertSee($enUrl, false)
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertHeader('Cache-Control', 'no-store, private')
            ->assertDontSee('rel="canonical"', false);
        $this->get($enUrl)->assertOk()->assertSee('Private draft')->assertSee($itUrl, false);
        $this->get('/it/notizie/bozza-privata')->assertNotFound();
        $this->assertSame('draft', $news->fresh()->status);
    }

    public function test_preview_also_respects_record_authorization(): void
    {
        $event = Event::create(['title' => 'Evento riservato', 'slug' => 'riservato', 'status' => 'draft', 'start_date' => now()->addDay()]);
        $this->actingAs($this->admin());
        Gate::policy(Event::class, DenyPreviewPolicy::class);
        $this->get(route('admin.content-preview', [
            'locale' => 'it', 'type' => 'event', 'record' => $event->id,
        ]))->assertForbidden();
    }

    public function test_an_admin_can_preview_a_saved_event_draft(): void
    {
        $event = Event::create(['title' => 'Anteprima evento', 'slug' => 'anteprima-evento', 'status' => 'draft', 'start_date' => now()->addDay()]);
        $this->actingAs($this->admin())->get(route('admin.content-preview', [
            'locale' => 'it', 'type' => 'event', 'record' => $event->id,
        ]))->assertOk()->assertSee('Anteprima evento');
        $this->get('/it/eventi/anteprima-evento')->assertNotFound();
    }

    public function test_directory_pages_respect_the_order_entered_in_the_back_office(): void
    {
        foreach (['comunita' => ['community', 'realta'], 'sapori_piatti' => ['tastes', 'piatti']] as $category => [$route, $data]) {
            $last = DirectoryItem::create(['title' => 'Ultimo', 'category' => $category, 'sort_order' => 8]);
            $first = DirectoryItem::create(['title' => 'Primo', 'category' => $category, 'sort_order' => 1]);
            foreach (['it', 'en'] as $locale) {
                $response = $this->get(route($route.'.'.$locale))->assertOk();
                $this->assertSame([$first->id, $last->id], $response->viewData($data)->modelKeys());
            }
        }
    }

    public function test_album_order_and_back_office_links_use_the_same_pagination(): void
    {
        $older = GalleryAlbum::create(['title' => 'Primo per ordine', 'sort_order' => 0, 'section_date' => '2025-01-01']);
        $newer = GalleryAlbum::create(['title' => 'Secondo per ordine', 'sort_order' => 1, 'section_date' => '2026-09-01']);
        for ($i = 2; $i <= 11; $i++) {
            GalleryAlbum::create(['title' => 'Album '.$i, 'sort_order' => $i]);
        }
        $last = GalleryAlbum::create(['title' => 'Ultimo album', 'sort_order' => 12]);
        $response = $this->get('/it/galleria')->assertOk();
        $this->assertSame([$older->id, $newer->id], $response->viewData('albums')->pluck('id')->take(2)->all());
        $url = ContentActions::publicUrl($last);
        $this->assertStringContainsString('?page=2#album-'.$last->id, $url);
        $response = $this->get($url)->assertOk();
        $this->assertSame([$last->id], $response->viewData('albums')->pluck('id')->all());
    }

    public function test_news_filters_distinguish_online_scheduled_and_draft_records(): void
    {
        $this->actingAs($this->admin());
        $online = News::create(['title' => 'Online', 'slug' => 'online', 'status' => 'published', 'content' => 'Avviso di prova']);
        $scheduled = News::create(['title' => 'Domani', 'slug' => 'domani', 'status' => 'published', 'published_at' => now()->addDay(), 'content' => 'Avviso di prova']);
        $draft = News::create(['title' => 'Bozza', 'slug' => 'bozza', 'status' => 'draft', 'content' => 'Avviso di prova']);
        Livewire::test(ListNews::class)
            ->filterTable('status', 'scheduled')
            ->assertCanSeeTableRecords([$scheduled])
            ->assertCanNotSeeTableRecords([$online, $draft])
            ->assertSee('Programmata')
            ->filterTable('status', 'published')
            ->assertCanSeeTableRecords([$online])
            ->assertCanNotSeeTableRecords([$scheduled, $draft]);
    }

    public function test_dashboard_event_count_excludes_past_and_unpublished_records(): void
    {
        $this->travelTo(now()->setDate(2026, 9, 7)->startOfDay());
        Event::create(['title' => 'Passato', 'slug' => 'passato', 'status' => 'published', 'start_date' => now()->subMonth()]);
        Event::create(['title' => 'Bozza', 'slug' => 'bozza', 'status' => 'draft', 'start_date' => now()->addDay()]);
        $ongoing = Event::create(['title' => 'In corso', 'slug' => 'in-corso', 'status' => 'published', 'start_date' => now()->subDay(), 'end_date' => now()->addDay()]);
        $future = Event::create(['title' => 'Futuro', 'slug' => 'futuro', 'status' => 'published', 'start_date' => now()->addWeek()]);
        $this->assertEqualsCanonicalizing([$ongoing->id, $future->id], Event::currentAndUpcoming()->pluck('id')->all());
        $this->assertCount(2, $this->get('/it')->viewData('events'));
    }
}

class DenyPreviewPolicy
{
    public function update(User $user, Event $event): bool
    {
        return false;
    }
}
