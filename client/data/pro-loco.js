const proLocoDataIt = {
  hero: {
    title: "La Pro Loco",
    subtitle: "MOTORE DELLA NOSTRA COMUNITÀ",
    img: "/images/arabata01.jpg"
  },
  intro: {
    title: "Chi Siamo"
  },
  squadra: {
    titolo: "Quelli che montano il palco",
    sottotitolo: "Il Direttivo Pro Loco",
    membri: [
      { nome: "Carmela Coviello", ruolo: "PRESIDENTE" },
      { nome: "Rocco De Rosa", ruolo: "VICE PRESIDENTE" },
      { nome: "Martina Caturano", ruolo: "SEGRETARIO" },
      { nome: "Viviana Caturano", ruolo: "TESORIERE" },
      { nome: "Vincenzo Palladino", ruolo: "CONSIGLIERE" }
    ],
    img: "/images/squadra1.jpg"
  }
};

const proLocoDataEn = {
  hero: {
    title: "The Pro Loco",
    subtitle: "THE ENGINE OF OUR COMMUNITY",
    img: "/images/arabata01.jpg"
  },
  intro: {
    title: "Who We Are"
  },
  squadra: {
    titolo: "The ones who set up the stage",
    sottotitolo: "The Pro Loco Board",
    membri: [
      { nome: "Carmela Coviello", ruolo: "PRESIDENT" },
      { nome: "Rocco De Rosa", ruolo: "VICE PRESIDENT" },
      { nome: "Martina Caturano", ruolo: "SECRETARY" },
      { nome: "Viviana Caturano", ruolo: "TREASURER" },
      { nome: "Vincenzo Palladino", ruolo: "COUNCILOR" }
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
