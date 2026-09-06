# Revisione frontend su main_test

## Modifiche

- Notizie, eventi e tradizioni hanno pagine di dettaglio proprie, in italiano e inglese. I collegamenti della home aprono direttamente il contenuto; bozze e categorie estranee restano escluse.
- Testi, date, luoghi, documenti e immagini sono separati. Le copertine mantengono le proporzioni e non vengono ripetute nella galleria dello stesso contenuto.
- Gli eventi in programma precedono le tradizioni e l'archivio. Ogni appuntamento compare una sola volta nel proprio elenco.
- Gallerie con miniature più grandi, dialogo nativo, chiusura persistente, navigazione da tastiera e restituzione del focus. Il chatbot non copre il visualizzatore.
- Menu mobile con un solo comando di chiusura, gestione del focus e chiusura con Escape. Corrette persistenza del consenso, revoca dei contenuti esterni e collegamenti alle informative in inglese.
- Luoghi ed eccellenze si leggono senza rotazione automatica. Indice per nome, compatto e apribile su telefono. Spaziature, modulo e contatti adattati agli schermi piccoli.

## Caricamento delle foto negli album

La validazione controlla il limite effettivo di 10 MB per immagini e documenti prima dell'invio, anche nei campi misti che accettano video fino a 100 MB. I file temporanei non disponibili non interrompono l'anteprima dei duplicati; una selezione vuota non genera più una conferma di successo. Migrazioni mancanti, trasferimenti concorrenti e problemi di connessione ricevono messaggi specifici. Gli errori tecnici restano nel log del server senza esporre configurazioni nel messaggio mostrato.

Verificati automaticamente creazione di un album con due foto, modifica senza perdita delle foto precedenti, riutilizzo di duplicati rinominati e presenza delle immagini nella pagina pubblica. Questi test usano un database isolato e simulano Cloudinary.

Provato anche il servizio Cloudinary reale con una PNG e un PDF pubblico del progetto, in una cartella separata di verifica: entrambi caricati e subito rimossi. Nessun album reale è stato modificato. L'errore specifico segnalato nell'ambiente dell'utente non è stato ancora riprodotto; occorrono il messaggio o la fase precisa del caricamento per confermarne la causa.

## Verifica

Suite completa: 64 test superati, 234 asserzioni. Compilazione frontend riuscita. Verifica nel browser delle sezioni pubbliche, dettagli di notizie ed eventi, menu e galleria a larghezze desktop e mobile (1440, 390 e 360 pixel). Controllate la persistenza del rifiuto dei cookie tra pagine italiane e inglesi e la rimozione della mappa incorporata dopo la revoca.

Le modifiche riguardano main_test. Il sito pubblico non è stato distribuito né aggiornato. Prima di un futuro rilascio devono essere applicate le migrazioni della libreria già presenti nel progetto e compilate le risorse frontend.
