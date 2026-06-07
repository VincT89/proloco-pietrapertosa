export const esperienzeData = {
  hero: {
    title: "Vivere il Territorio",
    subtitle: "Dall'adrenalina al ritmo lento dei cammini",
    img: "/images/voloAngelo.jpg",
    source: "Pro Loco",
    status: "verified"
  },
  intro: {
    title: "Tra cielo e terra",
    text: "Pietrapertosa offre esperienze sospese tra le Dolomiti Lucane. Dal volo sulle funi all'arrampicata, fino alla camminata nei boschi.",
    source: "Descrizione Operatori",
    status: "verified"
  },
  esperienze: [
    {
      id: "volo-angelo",
      title: "Il Volo dell'Angelo",
      subtitle: "Per i più coraggiosi",
      desc: "Cavo d'acciaio che collega Pietrapertosa e Castelmezzano per un volo sopra la valle.",
      img: "/images/voloAngelo.jpg",
      source: "Sito Volo dell'Angelo",
      status: "verified",
      stats: [
        { label: "Di volo", value: "~1,5 km" },
        { label: "Velocità max", value: "120 km/h" },
        { label: "Andata e ritorno", value: "2 linee" }
      ]
    },
    {
      id: "via-ferrata",
      title: "Le vie ferrate",
      subtitle: "Mani sulla roccia",
      desc: "La Via Ferrata delle Dolomiti Lucane si articola in due rami: la Via Salemm e la Via Marcirosa.",
      img: "/images/viaFerrata.jpg",
      source: "Guide Alpine",
      status: "verified",
      stats: [
        { label: "Lunghezza max", value: "1.778 m" },
        { label: "Punto di Partenza", value: "Ponte Romano" },
        { label: "Livello", value: "EEA" }
      ]
    },
    {
      id: "cammini",
      title: "Cammini ed Escursioni",
      subtitle: "Turismo lento",
      desc: "Il Parco Regionale di Gallipoli Cognato offre chilometri di sentieri e vecchi tratturi.",
      img: "/images/percorsi.jpg",
      source: "Ente Parco",
      status: "verified",
      stats: [
        { label: "Natura", value: "Tutelata" },
        { label: "Trekking", value: "Segnalato" },
        { label: "Parco", value: "Gallipoli Cognato" }
      ]
    },
    {
      id: "ebike",
      title: "Attività Locali in aggiornamento",
      subtitle: "Esplorazione su due ruote",
      desc: "Servizi di noleggio e-bike sul territorio.",
      img: "https://placehold.co/600x400/222/d4af37?text=Placeholder",
      source: "-",
      status: "placeholder",
      stats: [
        { label: "Servizi", value: "Da verificare" },
        { label: "Contatti", value: "-" },
        { label: "Disponibilità", value: "-" }
      ]
    }
  ]
};
