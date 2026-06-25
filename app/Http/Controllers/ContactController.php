<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProLocoContactRequest;
use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(ProLocoContactRequest $request)
    {
        // Silent honeypot check
        if (!blank($request->input('website_url'))) {
            \Illuminate\Support\Facades\Log::info('Honeypot triggered in contact form', ['ip' => $request->ip()]);
            return back()->with('success', 'Il tuo messaggio è stato inviato con successo. Ti risponderemo il prima possibile.');
        }

        $data = $request->validated();
        
        $toAddress = config('mail.proloco_contact_email');

        if (blank($toAddress)) {
            \Illuminate\Support\Facades\Log::error('PROLOCO_CONTACT_EMAIL non configurato');
            return back()->with('error', __('messages.contact_error'))->withInput();
        }

        try {
            Mail::to($toAddress)->send(new ContactMessage($data));

            return back()->with('success', __('messages.contact_success'));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Errore invio form Pro Loco', [
                'message' => $e->getMessage(),
            ]);

            return back()->with('error', __('messages.contact_error'))->withInput();
        }
    }
}
