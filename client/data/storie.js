const storieDataIt = {
  hero: {
    title: "Le Nostre Storie",
    subtitle: "Pietrapertosa raccontata da chi la vive",
    img: "/images/arabata01.jpg",
    source: "Pro Loco",
    status: "verified"
  },
  intro: {
    title: "Archivio Comunitario",
    text: "Raccolta di testimonianze e storie locali per mantenere viva la memoria del paese.",
    source: "Archivio Pro Loco",
    status: "verified"
  },
  storie: [
    { 
      id: 1, 
      title: "Spazio Memoria: Interviste in lavorazione", 
      desc: "Sezione in fase di allestimento. Verranno pubblicate testimonianze reali degli abitanti.", 
      imgs: [
        "/images/torre.jpg",
        "/images/panorama.jpg"
      ],
      source: "-",
      status: "placeholder"
    },
    { 
      id: 2, 
      title: "Archivio Lavori Agricoli", 
      desc: "Sezione in fase di allestimento.", 
      imgs: [
        "/images/sapori_autunno.png",
        "/images/percorsi.jpg"
      ],
      source: "-",
      status: "placeholder"
    },
    { 
      id: 3, 
      title: "Archivio Musicale", 
      desc: "Sezione in fase di allestimento.", 
      imgs: [
        "/images/squadra1.jpg",
        "/images/sanGiacomo.jpg"
      ],
      source: "-",
      status: "placeholder"
    }
  ]
};

const storieDataEn = {
  hero: {
    title: "Our Stories",
    subtitle: "Pietrapertosa told by those who live it",
    img: "/images/arabata01.jpg",
    source: "Pro Loco",
    status: "verified"
  },
  intro: {
    title: "Community Archive",
    text: "Collection of testimonies and local stories to keep the memory of the village alive.",
    source: "Archivio Pro Loco",
    status: "verified"
  },
  storie: [
    { 
      id: 1, 
      title: "Memory Space: Interviews in progress", 
      desc: "Section under construction. Real testimonies from the inhabitants will be published.", 
      imgs: [
        "/images/torre.jpg",
        "/images/panorama.jpg"
      ],
      source: "-",
      status: "placeholder"
    },
    { 
      id: 2, 
      title: "Agricultural Work Archive", 
      desc: "Section under construction.", 
      imgs: [
        "/images/sapori_autunno.png",
        "/images/percorsi.jpg"
      ],
      source: "-",
      status: "placeholder"
    },
    { 
      id: 3, 
      title: "Musical Archive", 
      desc: "Section under construction.", 
      imgs: [
        "/images/squadra1.jpg",
        "/images/sanGiacomo.jpg"
      ],
      source: "-",
      status: "placeholder"
    }
  ]
};

export const storieData = {
  it: storieDataIt,
  en: storieDataEn,
  ...storieDataIt
};
