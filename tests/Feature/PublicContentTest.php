<?php

namespace Tests\Feature;

use App\Models\DirectoryItem;
use App\Models\Event;
use App\Models\Media;
use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_has_a_translated_shareable_detail_and_drafts_remain_private(): void
    {
        $news = News::create(['title' => 'Avviso pubblico', 'title_en' => 'Public announcement', 'slug' => 'avviso', 'status' => 'published', 'content' => '<p>Primo paragrafo.</p><p>Ultimo paragrafo.</p>']);
        $this->get('/it/notizie/avviso')->assertOk()->assertSee('Ultimo paragrafo.')
            ->assertSee(route('news.show.en', 'avviso'), false)->assertSee('Tutte le notizie');
        $this->get('/en/news/avviso')->assertOk()->assertSee('Public announcement')->assertSee('All news');
        $this->get('/it/notizie/avviso?news=altro')->assertOk()->assertSee(route('news.show.en', 'avviso'), false);
        $news->update(['status' => 'draft']);
        $this->get('/it/notizie/avviso')->assertNotFound();
        $this->get('/en/news/avviso')->assertNotFound();
    }

    public function test_events_include_the_full_description_dates_and_location(): void
    {
        $event = Event::create(['title' => 'Evento di prova', 'slug' => 'evento', 'status' => 'published', 'start_date' => '2026-09-08', 'end_date' => '2026-09-10', 'location' => 'Luogo di prova', 'description' => '<p>'.str_repeat('Descrizione completa. ', 100).'</p><p>Fine programma.</p>']);
        $this->get('/it/eventi/evento')->assertOk()->assertSee('Luogo di prova')->assertSee('Fine programma.')->assertSee('2026-09-10');
        $this->get('/en/events/evento')->assertOk();
        $event->update(['status' => 'draft']);
        $this->get('/it/eventi/evento')->assertNotFound();
    }

    public function test_tradition_routes_do_not_expose_other_directory_categories(): void
    {
        $item = DirectoryItem::create(['title' => 'Tradizione di prova', 'category' => 'eventi_annuali']);
        $this->get('/it/eventi/tradizioni/'.$item->id)->assertOk()->assertSee('/en/events/traditions/'.$item->id, false);
        $item->update(['category' => 'scopri_luoghi']);
        $this->get('/it/eventi/tradizioni/'.$item->id)->assertNotFound();
    }

    public function test_home_links_to_details_and_current_events_are_separate_from_the_archive(): void
    {
        $this->travelTo(now()->setDate(2026, 9, 6)->startOfDay());
        Event::create(['title' => 'Prossimo evento', 'slug' => 'prossimo', 'status' => 'published', 'start_date' => now()->addDay()]);
        Event::create(['title' => 'Evento passato', 'slug' => 'passato', 'status' => 'published', 'start_date' => now()->subMonth()]);
        News::create(['title' => 'Nuova notizia', 'slug' => 'nuova', 'status' => 'published', 'content' => 'Testo di prova']);
        $this->get('/it')->assertOk()->assertSee(route('events.show.it', 'prossimo'), false)->assertSee(route('news.show.it', 'nuova'), false);
        $response = $this->get('/it/eventi')->assertOk();
        $this->assertSame(['prossimo'], $response->viewData('events')->pluck('slug')->all());
        $this->assertSame(['passato'], $response->viewData('pastEvents')->pluck('slug')->all());
    }

    public function test_cover_is_not_repeated_in_the_gallery_and_attachments_remain_linked(): void
    {
        $cover = Media::create(['type' => 'image', 'provider' => 'local', 'url' => '/images/castello.jpg']);
        $document = Media::create(['type' => 'document', 'provider' => 'local', 'url' => '/test.pdf', 'alt' => 'Bando']);
        $news = News::create(['title' => 'Avviso', 'slug' => 'avviso', 'status' => 'published', 'content' => 'Testo di prova', 'cover_media_id' => $cover->id]);
        $news->galleryMedia()->attach($cover, ['collection' => 'gallery']);
        $news->attachmentsMedia()->attach($document, ['collection' => 'attachments']);
        $this->get('/it/notizie/avviso')->assertOk()->assertSee('/test.pdf', false)->assertDontSee('id="detail-gallery-title"', false);
    }
}
