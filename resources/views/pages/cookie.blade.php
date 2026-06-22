@extends('layouts.app')

@section('title', __('legal.cookie') . ' · Pro Loco Pietrapertosana')

@section('content')
<div class="policy-hero">
    <div class="ed-wrap">
        <h1 class="policy-title">{{ __('legal.cookie') }}</h1>
        <div class="policy-subtitle">Pro Loco Pietrapertosana</div>
    </div>
</div>

<div class="policy-wrap">
    <div class="policy-content">
        <p>Questa Cookie Policy spiega cosa sono i cookie, come li utilizziamo sul nostro sito web e quali sono i tuoi diritti.</p>

        <h2>Cookie Tecnici Necessari</h2>
        <p>Il nostro sito utilizza esclusivamente cookie "tecnici", strettamente necessari per il corretto funzionamento e l'esplorazione sicura del sito. Nello specifico utilizziamo:</p>
        <ul>
            <li><strong>Sessione Laravel:</strong> necessari per mantenere le preferenze temporanee di navigazione.</li>
            <li><strong>CSRF Token:</strong> utilizzati dal framework Laravel per proteggere i form da vulnerabilità Cross-Site Request Forgery.</li>
            <li><strong>Login Admin:</strong> cookie tecnici necessari a mantenere l'autenticazione degli amministratori sul pannello Filament.</li>
            <li><strong>Scelta Cookie:</strong> un cookie tecnico (<code>proloco_cookie_consent</code>) per memorizzare la tua scelta se abilitare o meno i contenuti esterni, evitando di riproporti il banner.</li>
        </ul>

        <h2>Assenza di Analytics e Profilazione</h2>
        <p>Attualmente, su questo sito <strong>NON</strong> facciamo uso di script di tracciamento o analytics di prima o terza parte (come Google Analytics, Meta Pixel, o simili). Non viene profilata la tua navigazione.</p>

        <h2>Contenuti Esterni (Previa accettazione)</h2>
        <p>Alcune pagine contengono iframe o media incorporati provenienti da piattaforme di terze parti (Google Maps per le mappe geografiche, Facebook e Instagram per video e post sociali). Poiché questi fornitori potrebbero usare i propri cookie per tracciare la tua attività, li teniamo bloccati di default.</p>
        <p>Solo se decidi attivamente di "Accettare i contenuti esterni" tramite l'apposito banner, sbloccheremo gli iframe, e a quel punto Google e Meta potrebbero salvare i loro cookie nel tuo browser.</p>

        <h2>Cloudinary come CDN</h2>
        <p>Le immagini native caricate sul nostro sito sono fornite attraverso Cloudinary. Poiché Cloudinary funge da rete per la consegna dei contenuti (CDN), la semplice visualizzazione delle immagini comporta il passaggio dei dati attraverso i loro server, che potrebbero registrare log tecnici anonimi ai fini operativi.</p>

        <div class="mt-40 pt-40" style="border-top: 1px solid rgba(255,255,255,0.05);">
            <button onclick="window.manageCookiePreferences()" class="scopri-btn-outline" id="reopenCookieBanner">{{ __('legal.manage_preferences') }}</button>
        </div>
    </div>
</div>
@endsection
