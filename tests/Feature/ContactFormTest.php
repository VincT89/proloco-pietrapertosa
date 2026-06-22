<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    public function test_contact_form_sends_email_with_valid_data(): void
    {
        Mail::fake();

        $response = $this->post('/it/pro-loco/contact', [
            'name' => 'Mario Rossi',
            'email' => 'mario@example.com',
            'subject' => 'Informazioni',
            'message' => 'Vorrei maggiori informazioni.',
            'website' => '', // honeypot
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect();

        Mail::assertSent(ContactMessage::class);
    }

    public function test_contact_form_fails_if_honeypot_filled(): void
    {
        Mail::fake();

        $response = $this->post('/it/pro-loco/contact', [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'message' => 'Spam message',
            'website' => 'http://spam.com', // honeypot filled
        ]);

        $response->assertSessionHas('success');
        Mail::assertNothingSent();
    }
}
