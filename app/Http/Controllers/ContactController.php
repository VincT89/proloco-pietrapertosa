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
        if (!blank($request->input('website'))) {
            \Illuminate\Support\Facades\Log::info('Honeypot triggered in contact form', ['ip' => $request->ip()]);
            return back()->with('success', 'Il tuo messaggio è stato inviato con successo. Ti risponderemo il prima possibile.');
        }

        $data = $request->validated();
        
        $toAddress = config('services.proloco.contact_email');
        if (empty($toAddress)) {
            $toAddress = 'info@prolocopietrapertosana.it';
        }

        try {
            Mail::to($toAddress)->send(new ContactMessage($data));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Contact form email failed: ' . $e->getMessage());
            if (app()->environment('testing')) {
                throw $e;
            }
            // Generic anti-enumeration response
        }

        return back()->with('success', 'Il tuo messaggio è stato inviato con successo. Ti risponderemo il prima possibile.');
    }
}
