# Libreria media del back office

Tutti i campi per immagini usano la stessa libreria: copertine, gallerie, immagini delle pagine, loghi dei contributori e immagini nei testi, in italiano e inglese. Anche allegati e documenti possono riutilizzare i file compatibili già presenti.

Il comando **Scegli dalla libreria** mostra anteprime ingrandibili, nome originale quando disponibile e contenuti salvati che usano il file. La ricerca mantiene la selezione. Nelle gallerie è possibile aggiungere, rimuovere e riordinare le immagini; la modifica diventa effettiva con **Salva**. Rimuovere una foto dalla selezione non elimina il file dalla libreria.

**Carica nuovi file** confronta il contenuto del file con l'archivio indicizzato. Un file identico, anche rinominato, riutilizza il media esistente; due file diversi con lo stesso nome rimangono distinti. Il confronto usa SHA-256: ritagli, ricompressioni o altre modifiche non vengono riconosciuti come duplicati. Testo alternativo e didascalia del media riutilizzato restano invariati. Le immagini inserite nei testi possono avere una descrizione alternativa specifica per quell'inserimento.

## Attivazione su un ambiente esistente

Applicare la migrazione prima di aprire il back office con questo codice:

```sh
php artisan migrate
```

Indicizzare l'archivio e registrare anche le immagini presenti nei testi:

```sh
php artisan media:index --import-embedded
```

Per includere anche le immagini statiche del progetto in `public/images`:

```sh
php artisan media:index --import-embedded --import-public-images
```

Il comando legge i file locali pubblici e gli URL HTTPS di Cloudinary appartenenti all'account configurato. `APP_URL` deve corrispondere al sito per riconoscere i suoi URL locali assoluti. Non riscrive i contenuti, non elimina le vecchie copie e non unisce i loro collegamenti. I gruppi di file identici già presenti vengono segnalati con gli ID dei media; i caricamenti successivi riutilizzano il record più vecchio del gruppo.

La verifica è ripetibile: riprende dai record senza impronta. Per elaborare un lotto di media già registrati:

```sh
php artisan media:index --limit=100
```

`--limit` si applica alla verifica dei record esistenti, non alle opzioni di importazione. Gli originali non raggiungibili sono segnalati e rimangono da verificare. Finché esistono record da indicizzare, il caricamento mostra un avviso: il controllo non può riconoscere quei vecchi file. Il nome originale si recupera solo se era stato conservato nei metadati; altrimenti l'interfaccia usa una descrizione o il nome disponibile nell'URL.

La lettura di **Utilizzato in** comprende i collegamenti delle gallerie e degli allegati, copertine, immagini delle pagine e immagini nei testi salvati. Gli usi scritti direttamente nei template del sito non sono inclusi. Le impostazioni di cache devono supportare i lock; con più server usare una cache condivisa, così anche caricamenti simultanei identici riutilizzano lo stesso record.

## Verifica

I test di `tests/Feature/MediaLibraryTest.php` usano SQLite in memoria e simulano Cloudinary: non caricano file nell'account reale. Coprono duplicati rinominati, file diversi omonimi, indicizzazione dell'archivio, salvataggio e riordino delle gallerie, rimozione senza perdita del file, vincoli dei PDF, ricerca per nome e rilevamento degli utilizzi. La suite completa si esegue con:

```sh
php vendor/bin/phpunit
```

Per la verifica manuale: selezionare una foto, cercarne un'altra, confermare e salvare; riaprire la scheda e verificare l'ordine. Provare poi a caricare la stessa foto rinominata e verificare l'avviso, l'anteprima e l'assenza di nuove copie. Ripetere nelle copertine e nell'editor dei testi, anche su uno schermo piccolo.

## Esito sull'archivio locale del 5 settembre 2026

Migrazione applicata al database locale e 170 file indicizzati, comprese le immagini statiche riutilizzabili. Rilevati 29 gruppi di file identici, con 33 copie aggiuntive già presenti: i record preesistenti e i loro collegamenti sono stati conservati. Un video esterno Facebook è escluso dal confronto dei file.

Quattro vecchi record puntano a file locali mancanti. Non risultano collegati ai contenuti salvati verificati e rimangono segnalati come non indicizzati:

| Media | Percorso registrato |
| --- | --- |
| 4 | `/images/sapori_hero.png` |
| 6 | `/images/immaginePaese.png` |
| 13 | `/images/pietrapertosaHome.jpg` |
| 15 | `/images/pietrapertosaHome2.jpg` |

Questi riferimenti richiedono l'identificazione dell'originale prima di un'eventuale correzione. Il confronto automatico è attivo sui file verificati e sui nuovi caricamenti.
