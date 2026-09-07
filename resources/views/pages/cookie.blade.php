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
        @if(app()->getLocale() === 'en')
        <p>This Cookie Policy explains what cookies are, how we use them on our website and what your rights are.</p>

        <h2>Strictly Necessary Technical Cookies</h2>
        <p>Our website uses only technical cookies that are strictly necessary for the website to function correctly and for safe browsing. Specifically, we use:</p>
        <ul>
            <li><strong>Laravel session:</strong> necessary to maintain temporary browsing preferences.</li>
            <li><strong>CSRF token:</strong> used by Laravel to protect forms against Cross-Site Request Forgery vulnerabilities.</li>
            <li><strong>Admin login:</strong> technical cookies needed to keep administrators signed in to the Filament panel.</li>
            <li><strong>Cookie choice:</strong> a technical cookie (<code>proloco_cookie_consent</code>) that stores your decision about enabling external content, so that the banner does not need to appear again.</li>
        </ul>

        <h2>No Analytics or Profiling</h2>
        <p>We currently <strong>do not</strong> use first-party or third-party tracking or analytics scripts, such as Google Analytics or Meta Pixel, on this website. Your browsing activity is not profiled.</p>

        <h2>External Content, Subject to Consent</h2>
        <p>Some pages contain iframes or embedded media from third-party platforms: Google Maps for maps, and Facebook and Instagram for videos and social posts. As these providers may use their own cookies to track your activity, we block this content by default.</p>
        <p>We will only enable these iframes if you actively choose “Accept external contents” in the cookie banner. Google and Meta may then store their cookies in your browser.</p>

        <h2>Cloudinary as a CDN</h2>
        <p>Images uploaded to our website are delivered through Cloudinary. As Cloudinary acts as a Content Delivery Network (CDN), viewing images involves data passing through its servers, which may record anonymous technical logs for operational purposes.</p>
        @else
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
        @endif

        <div class="policy-preferences">
            <button onclick="window.manageCookiePreferences()" class="scopri-btn-outline" id="reopenCookieBanner">{{ __('legal.manage_preferences') }}</button>
        </div>
    </div>
</div>
@endsection
