const galleryDataIt = {
  hero: {
    title: "Archivio Fotografico",
    subtitle: "Pietrapertosa in immagini",
    img: "/images/pietrapertosaGalleria.png",
    source: "Archivio Pro Loco",
    status: "verified"
  },
  images: [
    { id: 1, src: "/images/immaginePaese.jpg", category: "Borgo", alt: "Panorama di Pietrapertosa", source: "Pro Loco", status: "verified" },
    { id: 2, src: "/images/arabata.jpeg", category: "Storia", alt: "Scorcio dell'Arabata", source: "Pro Loco", status: "verified" },
    { id: 3, src: "/images/castello.jpg", category: "Storia", alt: "Castello Saraceno", source: "Pro Loco", status: "verified" },
    { id: 4, src: "/images/percorsi.jpg", category: "Natura", alt: "Sentiero nel bosco", source: "Pro Loco", status: "verified" },
    { id: 5, src: "/images/voloAngelo.jpg", category: "Esperienze", alt: "Il Volo dell'Angelo", source: "Operatore", status: "verified" },
    { id: 6, src: "/images/viaFerrata.jpg", category: "Esperienze", alt: "Via Ferrata", source: "Operatore", status: "verified" }
  ]
};

const galleryDataEn = {
  hero: {
    title: "Photo Archive",
    subtitle: "Pietrapertosa in pictures",
    img: "/images/pietrapertosaGalleria.png",
    source: "Archivio Pro Loco",
    status: "verified"
  },
  images: [
    { id: 1, src: "/images/immaginePaese.jpg", category: "Village", alt: "Pietrapertosa Panorama", source: "Pro Loco", status: "verified" },
    { id: 2, src: "/images/arabata.jpeg", category: "History", alt: "Glimpse of Arabata", source: "Pro Loco", status: "verified" },
    { id: 3, src: "/images/castello.jpg", category: "History", alt: "Saracen Castle", source: "Pro Loco", status: "verified" },
    { id: 4, src: "/images/percorsi.jpg", category: "Nature", alt: "Forest trail", source: "Pro Loco", status: "verified" },
    { id: 5, src: "/images/voloAngelo.jpg", category: "Experiences", alt: "Flight of the Angel", source: "Operatore", status: "verified" },
    { id: 6, src: "/images/viaFerrata.jpg", category: "Experiences", alt: "Via Ferrata", source: "Operatore", status: "verified" }
  ]
};

export const galleryData = {
  it: galleryDataIt,
  en: galleryDataEn,
  ...galleryDataIt
};
