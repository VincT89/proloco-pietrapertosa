@extends('layouts.app')

@php
    
@endphp

@section('title', ($page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? 'Contacts' : 'Contatti')) . ' · Proloco Pietrapertosana')

@section('content')
    @include('components.section-hero', [
        'title' => $page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? "Contacts" : "Contatti"),
        'subtitle' => $page?->getTranslation('hero_subtitle') ?? ((app()->getLocale() === 'en') ? "COME AND VISIT US" : "VIENI A TROVARCI"),
        'img' => $page?->heroMedia?->url ?? asset('images/panorama.jpg')
    ])
    
    @include('components.page-intro', [
        'title' => $page?->getTranslation('intro_title') ?? ((app()->getLocale() === 'en') ? "We are here for you" : "Siamo qui per te"),
        'text' => $page?->getTranslation('intro_text') ?? ((app()->getLocale() === 'en') ? "The Pro Loco of Pietrapertosa is always available to help you plan your visit. Do not hesitate to contact us for any information on events, excursions and hospitality." : "La Pro Loco di Pietrapertosa è sempre a disposizione per aiutarti a pianificare la tua visita. Non esitare a contattarci per qualsiasi informazione su eventi, escursioni e accoglienza.")
    ])

    <section class="wrap pb-100">
        <div class="cont-wrap">
            <div class="cont">
                <div class="cc">
                    <svg width="24" height="24" class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    <h4>{{ (app()->getLocale() === 'en') ? "Registered Office" : "Sede legale" }}</h4>
                    <p>Via della Speranza, 159<br/>85010 Pietrapertosa (PZ)</p>
                    <p class="contact-label">
                        P.Iva: 01925320762<br/>
                        Cod. Fiscale: 96028030763
                    </p>
                </div>
                <div class="cc">
                    <svg width="24" height="24" class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <h4>{{ (app()->getLocale() === 'en') ? "Call Us" : "Chiamaci" }}</h4>
                    <p><a href="tel:+393429896770">342 989 6770</a></p>
                </div>
                <div class="cc">
                    <svg width="24" height="24" class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    <h4>{{ (app()->getLocale() === 'en') ? "Write Us" : "Scrivici" }}</h4>
                    <p><a href="mailto:prolocopietrapertosa@gmail.com">prolocopietrapertosa@gmail.com</a></p>
                </div>
                <div class="cc">
                    <svg width="24" height="24" class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                    <h4>{{ (app()->getLocale() === 'en') ? "Follow Us" : "Seguici" }}</h4>
                    <p>
                        <a href="#" target="_blank" rel="noreferrer" class="contact-link">Facebook</a>
                        <a href="#" target="_blank" rel="noreferrer" class="contact-link">Instagram</a>
                    </p>
                </div>
            </div>
            <div class="cont-photo">
                <x-external-embed 
                    provider="google-maps" 
                    title="Google Maps" 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12046.208151528646!2d16.0505193!3d40.5183377!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1338d41edec1d0db%3A0x88f572a1cd3de995!2s85010%20Pietrapertosa%20PZ!5e0!3m2!1sit!2sit!4v1700000000000!5m2!1sit!2sit" 
                    ratio="auto"
                />
            </div>
        </div>
    </section>

    @include('components.squadra')

    @include('components.page-cta', [
        'subtitle' => (app()->getLocale() === 'en') ? "Discover the territory" : "Scopri il territorio",
        'title' => (app()->getLocale() === 'en') ? "Back to Home" : "Torna alla Home",
        'text' => (app()->getLocale() === 'en') ? "Or continue exploring our wonderful village." : "Oppure continua a esplorare il nostro meraviglioso borgo.",
        'btnText' => "Home",
        'btnLink' => url("/" . app()->getLocale() . "")
    ])

@endsection
