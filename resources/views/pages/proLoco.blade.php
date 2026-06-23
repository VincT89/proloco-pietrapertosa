@extends('layouts.app')

@php
    

@endphp

@section('title', ($page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? 'The Pro Loco' : 'La Pro Loco')) . ' · Proloco Pietrapertosana')

@section('content')
    @include('components.section-hero', [
        'title' => $page?->getTranslation('hero_title') ?? ((app()->getLocale() === 'en') ? "The Pro Loco" : "La Pro Loco"),
        'subtitle' => $page?->getTranslation('hero_subtitle') ?? ((app()->getLocale() === 'en') ? "THE ENGINE OF OUR COMMUNITY" : "MOTORE DELLA NOSTRA COMUNITÀ"),
        'img' => $page?->heroMedia?->optimizedUrl('hero') ?? asset('images/pietrapertosaProloco.jpg')
    ])
    
    @include('components.page-intro', [
        'title' => $page?->getTranslation('intro_title') ?? ((app()->getLocale() === 'en') ? "Who We Are" : "Chi Siamo"),
        'text' => $page?->getTranslation('intro_text') ?? ((app()->getLocale() === 'en') ? "We are a group of citizens in love with our village, united by the goal of promoting and enhancing the cultural, historical and human heritage of Pietrapertosa." : "Siamo un gruppo di cittadini innamorati del proprio paese, uniti dall'obiettivo di promuovere e valorizzare il patrimonio culturale, storico e umano di Pietrapertosa.")
    ])

    <section class="ed-sec">
        <div class="ed-wrap">
            <div class="ed-split">
                <div>
                    <span class="ed-subtitle">{{ (app()->getLocale() === 'en') ? "The Roots" : "Le Radici" }}</span>
                    <h2 class="ed-title proloco-title">{{ (app()->getLocale() === 'en') ? "Our History" : "La Nostra Storia" }}</h2>
                    <p class="proloco-desc">
                        {{ (app()->getLocale() === 'en') ? "Founded in 1998, the Pro Loco of Pietrapertosa was born from the desire of a group of citizens to preserve and share the traditions of our village. For over twenty years we have been organizing cultural events, historical reenactments and moments of aggregation that unite all generations." : "Fondata nel 1998, la Pro Loco di Pietrapertosa è nata dal desiderio di un gruppo di cittadini di preservare e condividere le tradizioni del nostro borgo. Da oltre vent'anni organizziamo eventi culturali, manifestazioni storiche e momenti di aggregazione che uniscono tutte le generazioni." }}
                    </p>
                </div>
                <div>
                    <span class="ed-subtitle">{{ (app()->getLocale() === 'en') ? "Transparency" : "Trasparenza" }}</span>
                    <h2 class="ed-title proloco-title">{{ (app()->getLocale() === 'en') ? "Statute" : "Statuto" }}</h2>
                    <p class="proloco-desc-short">
                        {{ (app()->getLocale() === 'en') ? "We operate in full compliance with the regulations for the Third Sector. The official statute and the minutes of the assemblies are always available to our members." : "Operiamo nel massimo rispetto delle normative per il Terzo Settore. Lo statuto ufficiale e i verbali delle assemblee sono sempre a disposizione dei nostri tesserati." }}
                    </p>
                    <a href="{{ asset('images/Statuto_ProLoco_Pietrapertosa_Leggibile.pdf') }}" target="_blank" class="ed-btn proloco-btn-pad">
                        <svg width="18" height="18" class="proloco-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                        {{ (app()->getLocale() === 'en') ? "View the Statute (PDF)" : "Visualizza lo Statuto (PDF)" }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    @if(isset($financialDocuments) && $financialDocuments->count() > 0)
    <section class="ed-sec bg-mut">
        <div class="ed-wrap">
            <div class="proloco-center-box" style="margin-bottom: 40px;">
                <span class="ed-subtitle">{{ (app()->getLocale() === 'en') ? "Transparency" : "Trasparenza" }}</span>
                <h2 class="ed-title">{{ (app()->getLocale() === 'en') ? "Financial Reports & Documents" : "Bilanci e Rendiconti" }}</h2>
                <p class="proloco-center-desc" style="max-width: 600px; margin: 0 auto;">
                    {{ (app()->getLocale() === 'en') ? "Financial documents and reports of the Pro Loco Pietrapertosana." : "Bilanci e documenti della Pro Loco Pietrapertosana per la trasparenza amministrativa." }}
                </p>
            </div>

            <div style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 40px;">
                @foreach($financialDocuments as $year => $documents)
                    <div>
                        <h3 style="font-size: 1.5rem; color: var(--color-primary); margin-bottom: 20px; border-bottom: 1px solid var(--color-border); padding-bottom: 10px;">{{ $year }}</h3>
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            @foreach($documents as $doc)
                                <a href="{{ $doc->media ? $doc->media->url : '#' }}" target="_blank" style="display: flex; align-items: center; justify-content: space-between; padding: 20px; background: var(--color-surface); border-radius: 8px; text-decoration: none; border: 1px solid var(--color-border); transition: all 0.2s ease;" onmouseover="this.style.borderColor='var(--color-primary)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='var(--color-border)'; this.style.transform='translateY(0)';">
                                    <div>
                                        <div style="font-weight: 600; color: var(--color-text); font-size: 1.1rem; margin-bottom: 4px;">{{ $doc->getTranslation('title', app()->getLocale(), false) ?: $doc->title }}</div>
                                        <div style="font-size: 0.9rem; color: var(--color-text-mut);">
                                            <span style="display: inline-block; padding: 2px 8px; background: rgba(184, 135, 70, 0.1); color: var(--color-primary); border-radius: 4px; font-weight: 500; font-size: 0.8rem; margin-right: 10px;">{{ $doc->type->getLabel() }}</span>
                                            @if($doc->getTranslation('description', app()->getLocale(), false) ?? $doc->description)
                                                {{ $doc->getTranslation('description', app()->getLocale(), false) ?? $doc->description }}
                                            @endif
                                        </div>
                                    </div>
                                    <div style="color: var(--color-primary);">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Squadra -->
    @include('components.squadra')

    <section class="ed-sec paper">
        <div class="ed-wrap">
            <div class="ed-form-box">
                <div class="proloco-center-box">
                    <span class="ed-subtitle">{{ (app()->getLocale() === 'en') ? "Participate" : "Partecipa" }}</span>
                    <h2 class="ed-title proloco-form-title">{{ (app()->getLocale() === 'en') ? "Become a Member" : "Diventa Socio" }}</h2>
                    <p class="proloco-center-desc">
                        {{ (app()->getLocale() === 'en') ? "Join us to actively support the community. Fill out the request to receive the Pro Loco membership card and participate in the assemblies." : "Unisciti a noi per supportare attivamente la comunità. Compila la richiesta per ricevere la tessera della Pro Loco e partecipare alle assemblee." }}
                    </p>
                </div>
                <form class="proloco-form" method="POST" action="{{ route('contact.send.' . app()->getLocale()) }}">
                    @csrf
                    <!-- Honeypot -->
                    <div class="d-none">
                        <input type="text" name="website" value="" tabindex="-1" autocomplete="off" />
                    </div>

                    @if(session('success'))
                        <div class="form-success-msg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="form-error-msg" style="margin-bottom: 15px;">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="proloco-form-grid">
                        <input type="text" name="name" required placeholder="{{ (app()->getLocale() === 'en') ? 'Full Name' : 'Nome e Cognome' }}" class="ed-input" value="{{ old('name') }}" />
                        <input type="email" name="email" required placeholder="Email" class="ed-input" value="{{ old('email') }}" />
                    </div>
                    @error('name')<div class="form-error-msg">{{ $message }}</div>@enderror
                    @error('email')<div class="form-error-msg">{{ $message }}</div>@enderror

                    <input type="text" name="subject" placeholder="{{ (app()->getLocale() === 'en') ? 'Subject (e.g. Membership)' : 'Oggetto (es. Tesseramento)' }}" class="ed-input mt-15" value="{{ old('subject') }}" />
                    @error('subject')<div class="form-error-msg">{{ $message }}</div>@enderror

                    <textarea name="message" required placeholder="{{ (app()->getLocale() === 'en') ? 'Your message' : 'Il tuo messaggio' }}" class="ed-input mt-15" rows="4">{{ old('message') }}</textarea>
                    @error('message')<div class="form-error-msg">{{ $message }}</div>@enderror

                    <div class="proloco-form-submit mt-15">
                        <button type="submit" class="ed-btn">{{ (app()->getLocale() === 'en') ? "Send Request" : "Invia Richiesta" }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="ed-sec alt proloco-contact-sec">
        <div class="ed-wrap">
            <div class="cont-wrap">
                <div class="cont">
                    <div class="cc proloco-cc">
                        <svg width="24" height="24" class="ico proloco-cc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <h4 class="proloco-cc-title">{{ (app()->getLocale() === 'en') ? "Headquarters" : "La Sede" }}</h4>
                        <p class="proloco-cc-text">Via della Speranza, 159<br/>85010 Pietrapertosa (PZ)</p>
                    </div>
                    <div class="cc proloco-cc-bot">
                        <svg width="24" height="24" class="ico proloco-cc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                        <h4 class="proloco-cc-title">{{ (app()->getLocale() === 'en') ? "Tax Info" : "Dati Fiscali" }}</h4>
                        <p class="proloco-cc-text">P.Iva: 01925320762<br/>Cod. Fiscale: 96028030763</p>
                    </div>
                    <div class="cc proloco-cc-right">
                        <svg width="24" height="24" class="ico proloco-cc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <h4 class="proloco-cc-title">{{ (app()->getLocale() === 'en') ? "Call Us" : "Chiamaci" }}</h4>
                        <p><a href="tel:+393429896770" class="proloco-cc-link">342 989 6770</a></p>
                    </div>
                    <div class="cc proloco-cc-none">
                        <svg width="24" height="24" class="ico proloco-cc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        <h4 class="proloco-cc-title">{{ (app()->getLocale() === 'en') ? "Write Us" : "Scrivici" }}</h4>
                        <p><a href="mailto:prolocopietrapertosa@gmail.com" class="proloco-cc-link">prolocopietrapertosa@gmail.com</a><br/><a href="mailto:prolocopietrapertosa@pec.it" class="proloco-cc-link">prolocopietrapertosa@pec.it</a></p>
                    </div>
                </div>
                <div class="cont-photo proloco-map-wrap">
                    <x-external-embed 
                        provider="google-maps" 
                        title="Google Maps" 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12046.208151528646!2d16.0505193!3d40.5183377!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1338d41edec1d0db%3A0x88f572a1cd3de995!2s85010%20Pietrapertosa%20PZ!5e0!3m2!1sit!2sit!4v1700000000000!5m2!1sit!2sit" 
                        ratio="auto"
                    />
                </div>
            </div>
        </div>
    </section>
@endsection

