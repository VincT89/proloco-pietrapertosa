const proLocoDataIt = {
  hero: {
    title: "La Pro Loco",
    subtitle: "MOTORE DELLA NOSTRA COMUNITÀ",
    img: "/images/pietrapertosaProloco.png"
  },
  intro: {
    title: "Chi Siamo"
  },
  squadra: {
    titolo: "Quelli che montano il palco",
    sottotitolo: "Il Direttivo Pro Loco",
    membri: [
      { nome: "Laraia Marinella", ruolo: "PRESIDENTE" },
      { nome: "Lucia Zottarelli", ruolo: "VICE PRESIDENTE" },
      { nome: "Marilina Giannotta", ruolo: "SEGRETARIO" },
      { nome: "Marianna Giannotta", ruolo: "TESORIERE" },
      { nome: "Marinella Pellettiere", ruolo: "CONSIGLIERE" }
    ],
    img: "/images/squadra1.jpg"
  }
};

const proLocoDataEn = {
  hero: {
    title: "The Pro Loco",
    subtitle: "THE ENGINE OF OUR COMMUNITY",
    img: "/images/pietrapertosaProloco.png"
  },
  intro: {
    title: "Who We Are"
  },
  squadra: {
    titolo: "The ones who set up the stage",
    sottotitolo: "The Pro Loco Board",
    membri: [
      { nome: "Laraia Marinella", ruolo: "PRESIDENT" },
      { nome: "Lucia Zottarelli", ruolo: "VICE PRESIDENT" },
      { nome: "Marilina Giannotta", ruolo: "SECRETARY" },
      { nome: "Marianna Giannotta", ruolo: "TREASURER" },
      { nome: "Marinella Pellettiere", ruolo: "COUNCILOR" }
    ],
    img: "/images/squadra1.jpg"
  }
};

export const proLocoData = {
  it: proLocoDataIt,
  en: proLocoDataEn,
  // Maintain backward compatibility
  ...proLocoDataIt
};
