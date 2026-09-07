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
        @if(app()->getLocale() === 'en')
        <h2>Data Controller</h2>
        <p>
            <strong>Pro Loco Pietrapertosana</strong><br>
            Via della Speranza, 159, 85010 Pietrapertosa (PZ)<br>
            Email: <a href="mailto:{{ config('services.proloco.contact_email', 'prolocopietrapertosa@gmail.com') }}">{{ config('services.proloco.contact_email', 'prolocopietrapertosa@gmail.com') }}</a>
        </p>

        <h2>Purposes and Legal Basis of Processing</h2>
        <p>The data collected through the contact form (name, email, subject and message) are used exclusively for the <strong>purpose</strong> of responding to your requests for information. The <strong>legal basis</strong> for processing is taking steps at the data subject’s request prior to entering into a contract (Article 6(1)(b) GDPR) and the Controller’s legitimate interest in responding to incoming communications (Article 6(1)(f) GDPR).</p>

        <h2>Providing Your Data</h2>
        <p>Providing data through the contact form is <strong>optional</strong>. However, if you do not provide the mandatory information, such as your name and email address, we will be unable to process and respond to your request.</p>

        <h2>Retention Period</h2>
        <p>Your data will be retained for the time strictly necessary to handle your request and, subsequently, for any period required by law or necessary to protect the Controller’s rights. Emails received through the contact form are normally deleted <strong>12 months</strong> after the matter has been closed, unless legal requirements make a different retention period necessary.</p>

        <h2>Data Processors and Recipients</h2>
        <p>Your data will not be made public. The following parties may have access to them as data processors (Article 28 GDPR) or authorised persons:</p>
        <ul>
            <li>The website hosting provider;</li>
            <li>The email service provider used by Pro Loco to receive communications;</li>
            <li><strong>Cloudinary:</strong> used as a Content Delivery Network (CDN) to optimise and deliver images, documents and videos. When you visit the website, media are downloaded from Cloudinary’s servers, which may collect technical data, such as IP addresses, to provide the service.</li>
            <li>Members of the association authorised to handle requests.</li>
        </ul>

        <h2>Transfers Outside the EEA</h2>
        <p>Personal data processed through services such as Cloudinary or US cloud providers may be transferred outside the European Economic Area (EEA). In these cases, the Controller ensures that transfers comply with applicable law, for example by using the European Commission’s Standard Contractual Clauses or relying on adequacy decisions such as the EU–US Data Privacy Framework.</p>

        <h2>External Content and Cookies</h2>
        <p>With your <strong>express consent through an affirmative action</strong> in the cookie banner, we enable external services such as Google Maps, Facebook and Instagram. These third parties may install profiling or tracking cookies. Without consent, which is the default setting, external content is blocked and only inactive placeholders are shown. We also use strictly necessary technical cookies for sessions and CSRF protection, which do not require consent.</p>

        <h2>Automated Decision-Making</h2>
        <p>The Controller <strong>does not use</strong> automated decision-making, including profiling, as referred to in Article 22(1) and (4) GDPR.</p>

        <h2>Your Rights and Complaints</h2>
        <p>You may exercise the rights provided by Articles 15 onwards of the GDPR at any time, including access, rectification, erasure, restriction of processing and the right to object. To do so, contact us at the email address above.</p>
        <p>You also have the <strong>right to lodge a complaint</strong> with the Italian Data Protection Authority (www.garanteprivacy.it) if you believe that processing infringes the Regulation.</p>

        <h2>Updates</h2>
        <p>This privacy policy may be amended to comply with new regulations. Please check it periodically.</p>
        @else
        <h2>Titolare del Trattamento</h2>
        <p>
            <strong>Pro Loco Pietrapertosana</strong><br>
            Via della Speranza, 159, 85010 Pietrapertosa (PZ)<br>
            Email: <a href="mailto:{{ config('services.proloco.contact_email', 'prolocopietrapertosa@gmail.com') }}">{{ config('services.proloco.contact_email', 'prolocopietrapertosa@gmail.com') }}</a>
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
        @endif
    </div>
</div>
@endsection
