const homeDataIt = {
  hero: {
    title: "Pietrapertosa vive tutto l'anno",
    subtitle: "Eventi, tradizioni, associazioni, persone e iniziative che animano il paese.",
    img: "/images/immaginePaese.png",
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
        testo: "Sono ufficialmente aperte le iscrizioni per il tesseramento alla Pro Loco di Pietrapertosa per l'anno 2026.",
        source: "Pro Loco", 
        status: "verified" 
      },
      { 
        id: 2, 
        tipo: "Avviso", 
        titolo: "Nuovi orari estivi Infopoint I.A.T.", 
        data: "1 Giugno", 
        testo: "Si comunica a tutti i residenti e visitatori che l'Infopoint Turistico (I.A.T.) osserverà il nuovo orario estivo.",
        source: "Pro Loco", 
        status: "verified" 
      },
      { 
        id: 3, 
        tipo: "Iniziativa", 
        titolo: "Bando di partecipazione: Mercatini dell'artigianato", 
        data: "5 Giugno", 
        testo: "La Pro Loco ha indetto un bando aperto a tutti gli artigiani locali.",
        source: "Pro Loco", 
        status: "verified" 
      }
    ]
  },
  scopri: {
    titolo: "Scopri Pietrapertosa",
    items: [
      { id: 1, nome: "L'Arabata", img: "/images/arabata01.jpg", source: "Borgo Racconta", status: "verified" },
      { id: 2, nome: "Castello Normanno-Svevo", img: "/images/castello.jpg", source: "Borgo Racconta", status: "verified" },
      { id: 3, nome: "Sentiero delle Sette Pietre", img: "/images/percorsi.jpg", source: "Borgo Racconta", status: "verified" },
      { id: 4, nome: "Volo dell'Angelo", img: "/images/voloAngelo.jpg", source: "Borgo Racconta", status: "verified" }
    ]
  }
};

const homeDataEn = {
  hero: {
    title: "Pietrapertosa is alive all year round",
    subtitle: "Events, traditions, associations, people and initiatives that animate the village.",
    img: "/images/immaginePaese.jpg",
    source: "Pro Loco Pietrapertosa",
    status: "verified"
  },
  eventi: {
    titolo: "Upcoming Events",
    items: [
      { id: 1, nome: "Tastes of Autumn", data: "November", img: "/images/sapori_autunno.png", source: "Archivio Pro Loco", status: "verified" },
      { id: 2, nome: "On the Traces of Arabs", data: "August", img: "/images/eventi_arabi.png", source: "Archivio Pro Loco", status: "verified" },
      { id: 3, nome: "Village Festival", data: "July", img: "/images/eventi_estate.png", source: "Archivio Pro Loco", status: "verified" },
      { id: 4, nome: "Band Concerts", data: "TBD", img: "/images/squadra1.jpg", source: "Associazione Musicale", status: "to-confirm" }
    ]
  },
  comunita: {
    titolo: "Community Highlights",
    items: [
      { id: 1, categoria: "Association", nome: "Sounds of Dolomites", img: "/images/sanGiacomo.jpg", source: "Anagrafica Associazioni", status: "verified" },
      { id: 2, categoria: "Volunteer", nome: "Civil Protection", img: "/images/protezione_civile.png", source: "Dato Pubblico", status: "verified" },
      { id: 3, categoria: "Volunteer", nome: "Local Groups", img: "/images/campanileSanGiacomo.jpg", source: "Pro Loco", status: "to-confirm" }
    ]
  },
  notizie: {
    titolo: "Latest News and Notices",
    items: [
      { id: 1, tipo: "Notice", titolo: "Opening of Pro Loco Membership 2026", data: "May 15", testo: "Registration for the Pro Loco di Pietrapertosa membership for the year 2026 is officially open.", source: "Pro Loco", status: "verified" },
      { id: 2, tipo: "Notice", titolo: "New summer hours Infopoint I.A.T.", data: "June 1", testo: "We inform all residents and visitors that the Tourist Infopoint will observe the new summer hours.", source: "Pro Loco", status: "verified" },
      { id: 3, tipo: "Initiative", titolo: "Call for participation: Craft markets", data: "June 5", testo: "The Pro Loco has issued a call open to all local artisans.", source: "Pro Loco", status: "verified" }
    ]
  },
  scopri: {
    titolo: "Discover Pietrapertosa",
    items: [
      { id: 1, nome: "The Arabata", img: "/images/arabata01.jpg", source: "Borgo Racconta", status: "verified" },
      { id: 2, nome: "Norman-Swabian Castle", img: "/images/castello.jpg", source: "Borgo Racconta", status: "verified" },
      { id: 3, nome: "Path of the Seven Stones", img: "/images/percorsi.jpg", source: "Borgo Racconta", status: "verified" },
      { id: 4, nome: "Flight of the Angel", img: "/images/voloAngelo.jpg", source: "Borgo Racconta", status: "verified" }
    ]
  }
};

export const getHomeData = (lang) => lang === 'en' ? homeDataEn : homeDataIt;
// Maintain backwards compatibility if something imports homeData directly
export const homeData = homeDataIt;
