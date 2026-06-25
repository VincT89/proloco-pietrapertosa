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
            return back()->with('success', __('messages.contact_success'));
        }

        $data = $request->validated();
        
        $toAddress = config('mail.proloco_contact_email');

        if (blank($toAddress)) {
            \Illuminate\Support\Facades\Log::error('PROLOCO_CONTACT_EMAIL non configurato', [
                'route' => request()->route()->getName(),
                'locale' => app()->getLocale(),
                'recipient_configured' => 'no'
            ]);
            return back()->with('error', __('messages.contact_error'))->withInput();
        }

        try {
            Mail::to($toAddress)->send(new ContactMessage($data));

            return back()->with('success', __('messages.contact_success'));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Errore tecnico invio form Pro Loco', [
                'message' => $e->getMessage(),
                'exception_class' => get_class($e),
                'route' => request()->route()->getName(),
                'locale' => app()->getLocale(),
                'mail_mailer' => config('mail.default'),
                'mail_host' => config('mail.mailers.smtp.host'),
            ]);

            return back()->with('error', __('messages.contact_error'))->withInput();
        }
    }
}
