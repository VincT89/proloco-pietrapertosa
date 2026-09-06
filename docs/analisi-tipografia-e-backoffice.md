# Analisi della tipografia e del back office

Verifica del 6 settembre 2026 sul progetto locale, ramo `main_test`, e sul sito all'indirizzo `http://127.0.0.1:8001`.

Questo documento fotografa la situazione prima degli interventi. Per le modifiche applicate, il font finale Lora e le verifiche successive, vedere [Revisione tipografia e immagini](revisione-tipografia-e-immagini.md). I riferimenti di riga sottostanti si riferiscono al codice esaminato inizialmente.

## Valutazione

Il disagio visivo ha riscontri concreti: titoli dello stesso livello cambiano molto tra le pagine, alcuni titoli secondari superano quelli principali su smartphone, le etichette sono spesso piccole e molto spaziate, mentre il corpo del testo usa generalmente un peso leggero. Cambiare soltanto il carattere lascerebbe questi problemi.

L'identità attuale usa fotografie del borgo, fondi scuri, carta chiara e oro. È coerente con un racconto del territorio, ma l'enfasi editoriale occupa spesso più spazio delle informazioni. Per un sito rivolto a visitatori e cittadini, eventi, date, luoghi, documenti e contatti devono poter essere letti rapidamente. La revisione proposta mantiene colori e contenuti, intervenendo su gerarchia, peso e spaziature.

Il progetto effettivo è Laravel con Filament e Livewire, non WordPress. Un'eventuale implementazione deve agire sui componenti Blade e sui fogli di stile esistenti, mantenendo i contenuti collegati al back office.

## Misure rilevate nel browser

Valori in pixel CSS, con impostazioni predefinite del browser. Desktop: finestra di 1440 px; smartphone: finestra di 390 px. Sono esempi rappresentativi, non un inventario di ogni testo.

| Elemento | Desktop | Smartphone | Osservazione |
| --- | ---: | ---: | --- |
| Titolo principale delle pagine interne | 64 | 40 | Su desktop eredita un'interlinea di 1,7: la riga occupa 108,8 px. |
| Pro Loco: “La Nostra Storia” e “Statuto” | 48 | 48 | Su smartphone superano il titolo della pagina, che è 40 px. |
| Titolo di un album in Galleria | 40 | 40 | Su smartphone compete con il titolo della pagina. |
| Titoli delle sezioni nelle liste di contenuti | 43,2 | 32 | Su smartphone i titoli delle schede arrivano anch'essi a 32 px. |
| Home: “Ultime Notizie e Avvisi” | 46,08 | — | È un h2 come “Scopri Pietrapertosa”, che sul desktop misura 72 px. |
| Etichette `.ed-subtitle` | 9,92 | 9,92 | Maiuscolo con circa 3,17 px tra le lettere: piccolo e dispersivo. |
| Testo introduttivo `.page-intro-text` | 17,6 | 17,6 | Peso 300 e interlinea 1,8: circa 31,68 px per riga. |
| Testo della storia Pro Loco | 16,8 | 16,8 | Peso 300 e interlinea 1,7; il grigio e il tratto sottile lo rendono poco presente. |

Una variazione di dimensione non è automaticamente sbagliata: il titolo iniziale della home può avere maggiore enfasi. Il problema è l'assenza di una regola riconoscibile tra contenuti equivalenti.

## Cause nel codice

- `resources/css/app.css:608`: corpo del testo con peso 300 e interlinea globale 1,7.
- `resources/css/app.css:1100`: `.ed-title` torna al peso 300 e introduce margini inferiori variabili da 40 a 70 px.
- `resources/css/app.css:1103`: etichette di 0,62 rem con spaziatura di 0,32 em.
- `resources/css/app.css:1199`: `.section-hero-title` non stabilisce l'interlinea desktop; la correzione a 1,1 compare soltanto nella regola mobile a riga 2273.
- `resources/css/app.css:1237`: `.ed-title-large` crea una scala diversa per alcuni h2 della home.
- `resources/css/app.css:1842` e `2040`: titoli degli album e della pagina Pro Loco con dimensioni fisse.
- `resources/css/content.css:7`: liste e dettagli adottano un ulteriore sistema di titoli.

Le spaziature seguono sistemi diversi: ad esempio 60 px sotto `.ed-section-header`, 100 px sopra `.terr-section-1` e 120 px nella sezione iniziale di Scopri. Questi valori possono sommarsi ai margini dei titoli e al riempimento dei contenitori. Vanno valutati sul blocco completo: una riduzione globale indiscriminata rischierebbe di comprimere altre pagine.

Sono presenti regole storiche, componenti più recenti e stili nelle viste. Selettori generici come `.section-title` o `.card h3` non raggiungono automaticamente `.content-card`, `.ed-title` e `.gal-title`. Serve ricondurre i componenti realmente usati a pochi valori condivisi, evitando un ulteriore strato di correzioni in fondo al foglio.

I due file principali di Cormorant Garamond e Source Sans 3 rispondono correttamente e contengono font variabili con asse del peso. Il riutilizzo dello stesso file in più dichiarazioni non dimostra quindi un carattere mancante o un peso inesistente. Si possono semplificare le dichiarazioni, ma non è la causa accertata del disagio visivo.

## Proposta da valutare

Prima scelta: **Source Serif 4 per i titoli**, peso 500, e **Source Sans 3 per i testi**, peso 400. Il primo conserva una voce editoriale adatta al territorio, con un disegno più solido rispetto al Cormorant leggero attuale; il secondo è già presente nel progetto. Questa è una scelta progettuale da confrontare visivamente, non una conclusione tecnica obbligata.

L'alternativa più conservativa mantiene Cormorant Garamond, aumentando il peso e applicando la stessa scala corretta. Un'impostazione interamente Source Sans 3 sarebbe più informativa e meno editoriale. Il confronto preparato permette di valutare queste varianti su testi già presenti nella pagina Pro Loco; è un campione tipografico, non una schermata integrale del sito.

| Ruolo | Desktop proposto | Smartphone proposto |
| --- | --- | --- |
| Titolo iniziale della home | Fino a 64 px | Circa 40 px |
| Titolo di una pagina interna | 48 px | 36 px |
| Titolo di sezione | 32–36 px | 28 px |
| Titolo di scheda o sottosezione | 24–26 px | 22–24 px |
| Corpo del testo | 18 px, interlinea 1,6 | 17–18 px, interlinea 1,6 |
| Date e informazioni secondarie | 14–15 px | 14–15 px |

Per le spaziature, partirei da 16–20 px tra titolo e testo, circa 16 px tra paragrafi, 56–72 px tra sezioni desktop e 36–48 px su smartphone. I testi lunghi dovrebbero avere una larghezza di lettura indicativamente entro 65 caratteri. Le etichette utili devono restare leggibili senza ricorrere a maiuscolo e spaziatura estrema.

Questi valori sono una base da verificare sulle pagine effettive, anche con titoli lunghi e contenuti inglesi. Non sono stati applicati al sito durante questa analisi.

Riferimenti dei caratteri: [Source Serif 4](https://fonts.google.com/specimen/Source+Serif+4), [Source Sans 3](https://fonts.google.com/specimen/Source+Sans+3).

## Errore “Nuovo Album” nel back office

**Causa non ancora identificata; errore non dichiarato risolto.**

Il testo dello screenshot corrisponde alla notifica generica di Filament per le richieste Livewire fallite. `vendor/filament/filament/resources/js/error-notifications.js` intercetta il fallimento e sceglie la notifica associata allo stato HTTP oppure quella predefinita. Lo screenshot non espone quello stato e non permette di distinguere, per esempio, una sessione scaduta da un'eccezione sul server. Il titolo “errore durante il caricamento” non prova che il problema sia il caricamento iniziale della pagina o l'upload di un file.

Verifiche effettuate:

- Le 17 migrazioni del database locale risultano applicate, inclusa quella delle impronte dei media.
- Le rotte Livewire previste sono presenti.
- Una richiesta Livewire valida, costruita dalla pagina di accesso con la propria sessione e il proprio token, ha restituito HTTP 200. Questo verifica il trasporto di base, non il modulo Album autenticato.
- La suite esistente comprende creazione e modifica degli album, associazione delle foto e riuso dei media; passa. I test usano un ambiente isolato e simulano alcuni servizi, quindi non certificano un upload reale nell'ambiente in cui è stato visto lo screenshot.
- Nei log consultati non è emersa una nuova eccezione attribuibile con certezza all'episodio segnalato. Gli errori storici e quelli intenzionalmente prodotti dai test non sono stati assunti come causa attuale.
- Il browser dell'analisi arriva alla pagina di accesso del pannello. Senza una sessione autenticata non è stato possibile ripetere l'azione nello stesso modulo.

Per completare la diagnosi occorre accedere al pannello e ripetere l'azione precisa: apertura di “Nuovo Album”, scelta dalla libreria, caricamento di file oppure salvataggio. A quel punto vanno correlati risposta della richiesta fallita e log dello stesso momento. Se l'errore riguarda un'installazione diversa da quella locale, serve verificarlo in quell'ambiente. Non c'è evidenza sufficiente per cambiare limiti di upload, token, identificativi o configurazioni.

## Altri rilievi funzionali

**Ordine degli album non applicato al sito.** Il campo “Ordine” esiste in `app/Filament/Resources/GalleryAlbums/Schemas/GalleryAlbumForm.php:36`, ma la pagina pubblica ordina per data della sezione e data di creazione (`app/Http/Controllers/PublicController.php:158`). Il valore inserito nel pannello non influenza quell'elenco. Anche Comunità e Piatti non applicano `sort_order` nelle rispettive query. Va stabilita la precedenza desiderata tra ordine manuale e data prima di modificarla.

**Personalizzazione della voce attiva nel pannello non compatibile con il markup corrente.** `public/css/admin-custom.css:40` usa `.fi-sidebar-item-active` e `.fi-sidebar-item-button`. Filament installato genera `.fi-active` e `.fi-sidebar-item-btn` (`vendor/filament/filament/resources/views/components/sidebar/item.blade.php:28` e `50`). La regola personalizzata non trova quindi l'elemento previsto. È coerente con lo sfondo chiaro della voce attiva nello screenshot, ma non spiega la notifica di errore.

**Il chatbot salta il fine settimana in corso.** `app/Repositories/Chatbot/ChatbotEventRepository.php:21` usa `next(Carbon::SATURDAY)`: sabato 5 e domenica 6 settembre 2026 seleziona sabato 12 settembre. Se la domanda riguarda questo fine settimana, gli eventi correnti vengono esclusi. Il test esistente ripete la stessa formula e non intercetta il problema semantico. Inoltre, la ricerca per gli eventi di oggi considera solo la data di inizio: un evento iniziato ieri e ancora in corso non viene incluso. Il filtro del fine settimana può escludere anche eventi che iniziano prima del sabato e finiscono dopo la domenica.

**La data di pubblicazione delle notizie non programma la visibilità.** Home, elenco e dettaglio controllano lo stato `published`, senza richiedere che `published_at` sia già trascorso (`app/Http/Controllers/PublicController.php:34`, `96`, `105`). Una notizia pubblicata con una data futura è quindi visibile subito. Se il campo deve servire alla programmazione, manca quel comportamento; se è una data editoriale, il comportamento va reso chiaro nel pannello.

**Margini irregolari nei ringraziamenti sui telefoni piccoli.** A 360 px, la griglia dei collaboratori ha 305 px disponibili, ma la scheda conserva una larghezza minima di 320 px. Supera quindi il contenitore di 15 px, riducendo il margine destro. La causa è `minmax(320px, 1fr)` nello stile della griglia (`resources/views/pages/photo-thanks.blade.php:26`). Nel caso verificato il testo rimane visibile; è un difetto di adattamento e allineamento, non uno scorrimento orizzontale dell'intera pagina.

## Verifiche e limiti

Ho esaminato il codice applicativo delle pagine pubbliche, gli stili e gli script, le risorse del pannello, il flusso album/media, i modelli, i servizi coinvolti, le rotte, le migrazioni e i test pertinenti. Non è un audit riga per riga di tutte le dipendenze esterne né una certificazione di sicurezza.

- Navigazione delle sezioni principali italiane su desktop e smartphone: Home, Eventi, Comunità, Notizie, Eccellenze, Sapori, Scopri, Galleria e Pro Loco.
- Apertura delle corrispondenti pagine inglesi e delle pagine informative; controllo di dettagli di notizie, eventi e tradizioni.
- Prove nel browser del menu mobile, chiusura con Escape e ritorno del focus, cambio lingua sul dettaglio di una notizia, apertura della galleria, foto successiva e chiusura con ritorno alla miniatura.
- Nei campioni principali verificati a 390 px non sono emersi scorrimenti orizzontali o titoli tagliati. Ciò non sostituisce le prove future a tutte le larghezze e con tutti i contenuti.
- Suite automatica: **64 test superati, 234 asserzioni**.
- Controllo sintattico: **135 file PHP** e gli script JavaScript principali, senza errori.
- Compilazione degli asset completata. Rimangono avvisi di Vite per riferimenti risolti a runtime e un riferimento storico all'immagine `pietrapertosaPrimaFooter.jpg`; i due font principali sono stati verificati separatamente via HTTP. Non è stata attribuita a questi avvisi una rottura visiva non riprodotta.

Non sono stati modificati i sorgenti dell'applicazione, pubblicati contenuti, inviati messaggi dal modulo contatti o alterati record del sito per questa analisi. Sono stati prodotti questo rapporto e il confronto tipografico. La compilazione ha rigenerato gli asset locali esclusi dal controllo versione.
