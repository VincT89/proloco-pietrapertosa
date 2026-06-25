<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\News;
use App\Models\PageSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CoreFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Initialize page settings
        PageSetting::create(['page_slug' => 'notizie']);
        PageSetting::create(['page_slug' => 'eventi']);
    }

    public function test_draft_news_and_events_are_not_visible_to_public()
    {
        $draftNews = News::create([
            'title' => 'Draft News',
            'slug' => 'draft-news',
            'content' => 'Content',
            'status' => 'draft',
        ]);

        $publishedNews = News::create([
            'title' => 'Published News',
            'slug' => 'published-news',
            'content' => 'Content',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get('/it/notizie');
        $response->assertStatus(200);
        $response->assertSee('Published News');
        $response->assertDontSee('Draft News');

        $draftEvent = Event::create([
            'title' => 'Draft Event',
            'slug' => 'draft-event',
            'status' => 'draft',
            'start_date' => now(),
        ]);

        $response = $this->get('/it/eventi');
        $response->assertStatus(200);
        $response->assertDontSee('Draft Event');
    }

    public function test_contact_form_honeypot_is_silently_blocked()
    {
        Log::shouldReceive('info')
            ->once()
            ->with('Honeypot triggered in contact form', \Mockery::any());

        $response = $this->post('/it/pro-loco/contact', [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'message' => 'Spam message',
            'website_url' => 'http://spam.com', // Honeypot filled
            'privacy' => true,
        ]);

        $response->assertSessionHas('success');
    }

    public function test_news_pagination()
    {
        for ($i = 1; $i <= 10; $i++) {
            News::create([
                'title' => "News $i",
                'slug' => "news-$i",
                'content' => 'Content',
                'status' => 'published',
                'published_at' => now()->subDays($i),
            ]);
        }

        $response = $this->get('/it/notizie');
        $response->assertStatus(200);
        
        // Ensure pagination is happening (9 per page)
        $this->assertCount(9, $response->viewData('news'));
    }
}
