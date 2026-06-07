export const homeData = {
  hero: {
    title: "Pietrapertosa vive tutto l'anno",
    subtitle: "Eventi, tradizioni, associazioni, persone e iniziative che animano il paese.",
    img: "/images/arabata.jpeg",
    source: "Pro Loco Pietrapertosa",
    status: "verified"
  },
  eventi: {
    titolo: "Prossimi Eventi",
    items: [
      { id: 1, nome: "Sapori d'Autunno", data: "Novembre", img: "/images/sapori_autunno.png", source: "Archivio Pro Loco", status: "verified" },
      { id: 2, nome: "Sulle Tracce degli Arabi", data: "Agosto", img: "/images/eventi_arabi.png", source: "Archivio Pro Loco", status: "verified" },
      { id: 3, nome: "Borgo Festival", data: "Luglio", img: "/images/eventi_estate.png", source: "Archivio Pro Loco", status: "verified" },
      { id: 4, nome: "Concerti della Banda", data: "Date da definire", img: "/images/squadra1.jpg", source: "Associazione Musicale", status: "to-confirm" }
    ]
  },
  comunita: {
    titolo: "La Comunità in Primo Piano",
    items: [
      { id: 1, categoria: "Associazione", nome: "I Suoni delle Dolomiti", img: "/images/sanGiacomo.jpg", source: "Anagrafica Associazioni", status: "verified" },
      { id: 2, categoria: "Volontariato", nome: "Protezione Civile", img: "/images/protezione_civile.png", source: "Dato Pubblico", status: "verified" },
      { id: 3, categoria: "Volontariato", nome: "Gruppi Locali", img: "/images/campanileSanGiacomo.jpg", source: "Pro Loco", status: "to-confirm" }
    ]
  },
  notizie: {
    titolo: "Ultime Notizie e Avvisi",
    items: [
      { 
        id: 1, 
        tipo: "Comunicazione", 
        titolo: "Apertura Tesseramento Pro Loco 2026", 
        data: "15 Maggio", 
        testo: "Sono ufficialmente aperte le iscrizioni per il tesseramento alla Pro Loco di Pietrapertosa per l'anno 2026. La tessera garantisce la possibilità di partecipare attivamente alle assemblee, avere sconti sugli eventi a pagamento e contribuire direttamente alla valorizzazione del nostro borgo. È possibile ritirare e compilare i moduli presso la sede centrale in Via della Speranza tutti i martedì e giovedì dalle 17:00 alle 19:00.",
        source: "Pro Loco", 
        status: "verified" 
      },
      { 
        id: 2, 
        tipo: "Avviso", 
        titolo: "Nuovi orari estivi Infopoint I.A.T.", 
        data: "1 Giugno", 
        testo: "Si comunica a tutti i residenti e visitatori che, a partire dal 15 Giugno, l'Infopoint Turistico (I.A.T.) osserverà il nuovo orario estivo: aperto tutti i giorni, dal lunedì alla domenica, con orario continuato dalle 09:30 alle 18:30. Presso l'ufficio sarà possibile prenotare escursioni guidate e acquistare i biglietti per il Volo dell'Angelo.",
        source: "Pro Loco", 
        status: "verified" 
      },
      { 
        id: 3, 
        tipo: "Iniziativa", 
        titolo: "Bando di partecipazione: Mercatini dell'artigianato", 
        data: "5 Giugno", 
        testo: "La Pro Loco ha indetto un bando aperto a tutti gli artigiani locali e hobbisti per l'assegnazione delle postazioni espositive durante le festività patronali di San Giacomo. L'obiettivo è valorizzare i prodotti tipici e il fatto a mano locale. La scadenza per l'invio delle candidature è fissata per il 30 Giugno. Per scaricare il modulo, rivolgersi alla segreteria.",
        source: "Pro Loco", 
        status: "verified" 
      }
    ]
  },
  scopri: {
    titolo: "Scopri Pietrapertosa",
    items: [
      { id: 1, nome: "L'Arabatana", img: "/images/arabata01.jpg", source: "Borgo Racconta", status: "verified" },
      { id: 2, nome: "Castello Normanno-Svevo", img: "/images/castello.jpg", source: "Borgo Racconta", status: "verified" },
      { id: 3, nome: "Sentiero delle Sette Pietre", img: "/images/percorsi.jpg", source: "Borgo Racconta", status: "verified" },
      { id: 4, nome: "Volo dell'Angelo", img: "/images/voloAngelo.jpg", source: "Borgo Racconta", status: "verified" }
    ]
  }
};
