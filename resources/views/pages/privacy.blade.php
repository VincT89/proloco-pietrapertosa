@extends('layouts.app')

@section('title', __('legal.privacy') . ' · Pro Loco Pietrapertosana')

@section('content')
<div class="policy-hero">
    <div class="ed-wrap">
        <h1 class="policy-title">{{ __('legal.privacy') }}</h1>
        <div class="policy-subtitle">Pro Loco Pietrapertosana</div>
    </div>
</div>

<div class="policy-wrap">
    <div class="policy-content">
        <h2>Titolare del Trattamento</h2>
        <p>
            <strong>Pro Loco Pietrapertosana</strong><br>
            Via della Speranza, 159, 85010 Pietrapertosa (PZ)<br>
            Email: <a href="mailto:{{ config('services.proloco.contact_email', 'prolocopietrapertosa@gmail.com') }}" style="color: var(--gold-soft); text-decoration: none;">{{ config('services.proloco.contact_email', 'prolocopietrapertosa@gmail.com') }}</a>
        </p>

        <h2>Finalità e Base Giuridica del Trattamento</h2>
        <p>I dati raccolti tramite il form di contatto (Nome, Email, Oggetto, Messaggio) vengono utilizzati esclusivamente per la <strong>finalità</strong> di rispondere alle tue richieste di informazioni. La <strong>base giuridica</strong> del trattamento è l'esecuzione di misure precontrattuali adottate su richiesta dell'interessato (Art. 6, par. 1, lett. b, GDPR) e il legittimo interesse del Titolare a rispondere alle comunicazioni in entrata (Art. 6, par. 1, lett. f, GDPR).</p>

        <h2>Natura del Conferimento dei Dati</h2>
        <p>Il conferimento dei dati nel form di contatto è <strong>facoltativo</strong>. Tuttavia, il mancato conferimento dei campi obbligatori (come nome ed email) comporterà l'impossibilità di elaborare e rispondere alla tua richiesta.</p>

        <h2>Periodo di Conservazione</h2>
        <p>I tuoi dati verranno conservati per il tempo strettamente necessario a evadere la tua richiesta e, successivamente, per il tempo imposto da obblighi di legge o per tutelare i diritti del Titolare. Le email ricevute tramite form contatti vengono solitamente cancellate dopo <strong>12 mesi</strong> dalla chiusura della pratica, salvo diverse necessità legali.</p>

        <h2>Responsabili del Trattamento e Destinatari dei Dati</h2>
        <p>I dati forniti non saranno diffusi. Possono venire a conoscenza dei dati, in qualità di Responsabili del Trattamento (Art. 28 GDPR) o autorizzati:</p>
        <ul>
            <li>Il provider di servizi di hosting del sito web;</li>
            <li>Il provider dei servizi email utilizzato dalla Pro Loco per ricevere le comunicazioni;</li>
            <li><strong>Cloudinary:</strong> utilizzato come Content Delivery Network (CDN) per ottimizzare e distribuire immagini, documenti e video. Quando accedi al sito, i contenuti media vengono scaricati dai server Cloudinary, che potrebbe raccogliere dati tecnici (es. IP) per la fornitura del servizio.</li>
            <li>Incaricati interni all'associazione preposti alla gestione delle richieste.</li>
        </ul>

        <h2>Trasferimenti Extra-SEE</h2>
        <p>I dati personali gestiti (es. tramite Cloudinary o fornitori cloud americani) potrebbero essere trasferiti al di fuori dello Spazio Economico Europeo (SEE). In tal caso, il Titolare assicura che il trasferimento avverrà in conformità alle disposizioni di legge applicabili, ad esempio stipulando Clausole Contrattuali Tipo fornite dalla Commissione Europea o basandosi su decisioni di adeguatezza (es. Data Privacy Framework UE-USA).</p>

        <h2>Contenuti Esterni e Cookie</h2>
        <p>Previo il tuo <strong>consenso espresso (comando positivo)</strong> tramite l'apposito Cookie Banner, abilitiamo l'integrazione di servizi esterni quali Google Maps, Facebook e Instagram. Tali terze parti possono installare cookie di profilazione o tracciamento. In assenza di consenso (impostazione predefinita), i contenuti esterni sono bloccati e vengono mostrati solo dei placeholder inattivi. Utilizziamo inoltre cookie tecnici strettamente necessari (sessione, CSRF) per i quali non è richiesto il consenso.</p>

        <h2>Processi Decisionali Automatizzati</h2>
        <p>Il Titolare <strong>non adotta</strong> alcun processo decisionale automatizzato, compresa la profilazione, di cui all'articolo 22, paragrafi 1 e 4, del GDPR.</p>

        <h2>I tuoi Diritti e Reclamo al Garante</h2>
        <p>In ogni momento potrai esercitare i diritti previsti dagli Artt. 15 e ss. del GDPR, tra cui il diritto di accesso, rettifica, cancellazione, limitazione e opposizione al trattamento. Per farlo, contattaci all'indirizzo email indicato sopra.</p>
        <p>Inoltre, hai sempre il <strong>diritto di proporre reclamo</strong> all'Autorità Garante per la Protezione dei Dati Personali (www.garanteprivacy.it) se ritieni che il trattamento violi il Regolamento.</p>

        <h2>Aggiornamenti</h2>
        <p>Questa privacy policy può essere soggetta a modifiche per adempiere a nuove normative. Ti invitiamo a consultarla periodicamente.</p>
    </div>
</div>
@endsection
