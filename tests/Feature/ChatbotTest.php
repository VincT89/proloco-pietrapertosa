<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\DirectoryItem;
use App\Models\Event;
use App\Models\FinancialDocument;
use App\Models\Media;
use Carbon\Carbon;

class ChatbotTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_suggestions_returns_200()
    {
        $response = $this->get('/it/chatbot/suggestions');
        $response->assertStatus(200);
        $response->assertJsonIsArray();
        $this->assertContains('Qual è il prossimo evento?', $response->json());
    }

    public function test_en_suggestions_returns_200()
    {
        $response = $this->get('/en/chatbot/suggestions');
        $response->assertStatus(200);
        $response->assertJsonIsArray();
        $this->assertContains('What is the next event?', $response->json());
    }

    public function test_message_requires_text()
    {
        $response = $this->postJson('/it/chatbot/message', [
            'message' => ''
        ]);
        $response->assertStatus(422);
    }

    public function test_message_rejects_too_long_text()
    {
        $response = $this->postJson('/it/chatbot/message', [
            'message' => str_repeat('a', 501)
        ]);
        $response->assertStatus(422);
    }

    public function test_greeting_intent_returns_correct_response()
    {
        $response = $this->postJson('/it/chatbot/message', [
            'message' => 'ciao'
        ]);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'type' => 'text'
        ]);
        $this->assertStringContainsString('Posso rispondere alle tue domande', $response->json('reply'));
    }

    public function test_empty_query_after_stopwords_returns_specific_fallback()
    {
        $response = $this->postJson('/it/chatbot/message', [
            'message' => 'cosa posso'
        ]);
        $response->assertStatus(200);
        $this->assertStringContainsString('Non sono sicuro', $response->json('reply'));
    }

    public function test_unknown_question_returns_safe_fallback()
    {
        $response = $this->postJson('/it/chatbot/message', [
            'message' => 'Pizzaburger spaziale'
        ]);
        $response->assertStatus(200);
        $this->assertStringContainsString('Non ho trovato informazioni specifiche', $response->json('reply'));
    }

    public function test_where_to_eat_navigation()
    {
        $response = $this->postJson('/it/chatbot/message', [
            'message' => 'dove mangiare?'
        ]);
        $response->assertStatus(200);
        $this->assertStringContainsString('Visita la pagina Sapori oppure Eccellenze', $response->json('reply'));
        $this->assertStringContainsString('Il Borgo Racconta', $response->json('reply'));
        
        $links = collect($response->json('links'));
        $this->assertTrue($links->contains('label', 'Sapori'));
        $this->assertTrue($links->contains('label', 'Borgo Racconta'));
    }

    public function test_arabata_search_with_apostrophe()
    {
        Event::create([
            'title' => 'Festa dell\'Arabata',
            'slug' => 'festa-arabata',
            'start_date' => now()->addDays(5),
            'status' => 'published'
        ]);

        $response = $this->postJson('/it/chatbot/message', [
            'message' => 'cos\'è l\'arabata?'
        ]);
        
        $response->assertStatus(200);
        $this->assertStringContainsString('Ecco cosa ho trovato nel sito', $response->json('reply'));
        
        $cards = collect($response->json('cards'));
        $this->assertTrue($cards->contains('title', 'Festa dell\'Arabata'));
    }

    public function test_document_search_returns_link()
    {
        $media = Media::create([
            'type' => 'document',
            'provider' => 'cloudinary',
            'public_id' => 'bilancio-2023',
            'url' => 'https://res.cloudinary.com/bilancio.pdf',
            'resource_type' => 'raw'
        ]);

        FinancialDocument::create([
            'title' => 'Bilancio 2023',
            'year' => 2023,
            'type' => 'budget',
            'media_id' => $media->id,
            'is_published' => true
        ]);

        $response = $this->postJson('/it/chatbot/message', [
            'message' => 'bilancio 2023'
        ]);
        
        $response->assertStatus(200);
        $links = collect($response->json('links'));
        $this->assertTrue($links->contains('title', 'Bilancio 2023 (2023)') || $links->contains('label', 'Bilancio 2023 (2023)'));
    }

    public function test_where_to_sleep_navigation_contains_borgo_racconta()
    {
        $response = $this->postJson('/it/chatbot/message', [
            'message' => 'dove dormire?'
        ]);
        $response->assertStatus(200);
        $this->assertStringContainsString('Puoi visitare la sezione Ospitalità (Borgo Racconta)', $response->json('reply'));
        
        $links = collect($response->json('links'));
        $this->assertTrue($links->contains('label', 'Ospitalità (Borgo Racconta)'));
    }

    public function test_where_to_eat_en_navigation()
    {
        $response = $this->postJson('/en/chatbot/message', [
            'message' => 'where to eat?'
        ]);
        $response->assertStatus(200);
        $this->assertStringContainsString('Tastes or Excellences', $response->json('reply'));
        $this->assertStringContainsString('Borgo Racconta', $response->json('reply'));
    }

    public function test_ambiguous_query_returns_mixed_response()
    {
        $response = $this->postJson('/it/chatbot/message', [
            'message' => 'sapori ed eventi'
        ]);
        $response->assertStatus(200);
        $this->assertStringContainsString('Non sono sicuro', $response->json('reply'));
        
        $links = collect($response->json('links'));
        $this->assertTrue($links->contains('label', 'Scopri & Vivi'));
        $this->assertTrue($links->contains('label', 'Sapori'));
    }

    public function test_url_allowlist_rejects_malicious_links()
    {
        $media = Media::create([
            'type' => 'document',
            'provider' => 'cloudinary',
            'public_id' => 'hack',
            'url' => 'javascript:alert(1)',
            'resource_type' => 'raw'
        ]);

        FinancialDocument::create([
            'title' => 'Regolamento',
            'year' => 2023,
            'type' => 'other',
            'media_id' => $media->id,
            'is_published' => true
        ]);

        $response = $this->postJson('/it/chatbot/message', [
            'message' => 'regolamento'
        ]);
        
        $response->assertStatus(200);
        $links = collect($response->json('links'));
        $this->assertTrue($links->contains('url', '#'));
    }

    public function test_weekend_events_carbon_range()
    {
        // Start date is exactly next Sunday at 10 AM
        $nextSunday = Carbon::now()->next(Carbon::SATURDAY)->addDay()->setHour(10)->setMinute(0);

        Event::create([
            'title' => 'Festa nel weekend',
            'slug' => 'festa-weekend',
            'start_date' => $nextSunday,
            'status' => 'published'
        ]);

        // Send 'weekend_events' intent
        $response = $this->postJson('/it/chatbot/message', [
            'message' => 'eventi questo weekend'
        ]);
        
        $response->assertStatus(200);
        $cards = collect($response->json('cards'));
        $this->assertTrue($cards->contains('title', 'Festa nel weekend'));
    }

    public function test_rate_limit_applies()
    {
        // 20 requests per minute
        for ($i = 0; $i < 20; $i++) {
            $this->postJson('/it/chatbot/message', ['message' => 'ciao']);
        }

        $response = $this->postJson('/it/chatbot/message', ['message' => 'ciao']);
        $response->assertStatus(429); // Too Many Requests
    }
}
