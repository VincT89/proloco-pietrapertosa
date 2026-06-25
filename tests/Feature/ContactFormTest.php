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
            'website_url' => '', // honeypot
            'privacy' => true,
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
            'website_url' => 'http://spam.com', // honeypot filled
            'privacy' => true,
        ]);

        $response->assertSessionHas('success');
        Mail::assertNothingSent();
    }

    public function test_contact_form_fails_without_privacy(): void
    {
        $response = $this->post('/it/pro-loco/contact', [
            'name' => 'Mario Rossi',
            'email' => 'mario@example.com',
            'message' => 'Vorrei maggiori informazioni.',
            'website_url' => '',
        ]);

        $response->assertSessionHasErrors('privacy');
    }

    public function test_contact_form_fails_if_recipient_missing(): void
    {
        config(['mail.proloco_contact_email' => null]);
        
        $response = $this->post('/it/pro-loco/contact', [
            'name' => 'Mario Rossi',
            'email' => 'mario@example.com',
            'message' => 'Vorrei maggiori informazioni.',
            'website_url' => '',
            'privacy' => true,
        ]);

        $response->assertSessionHas('error');
    }

    public function test_contact_form_handles_smtp_error(): void
    {
        Mail::shouldReceive('to')->andThrow(new \Exception('SMTP Error'));

        $response = $this->post('/it/pro-loco/contact', [
            'name' => 'Mario Rossi',
            'email' => 'mario@example.com',
            'message' => 'Vorrei maggiori informazioni.',
            'website_url' => '',
            'privacy' => true,
        ]);

        $response->assertSessionHas('error');
    }
}
