const vivereDataIt = {
  servizi: [
    {
      id: 1,
      nome: "Infopoint I.A.T. / Pro Loco",
      info: "Informazioni Turistiche e Accoglienza",
      contatto: "Tel: 0971 983002 | Cell: 342 989 6770",
      source: "Ricerca Web",
      status: "verified"
    },
    {
      id: 2,
      nome: "Comune di Pietrapertosa",
      info: "Centralino e Uffici Comunali",
      contatto: "Tel: 0971 983052",
      source: "Ricerca Web",
      status: "verified"
    },
    {
      id: 3,
      nome: "Volo dell'Angelo",
      info: "Biglietteria e Informazioni Impianti",
      contatto: "Tel: 0971 983110 | Cell: 342 989 6770",
      source: "Ricerca Web",
      status: "verified"
    }
  ]
};

const vivereDataEn = {
  servizi: [
    {
      id: 1,
      nome: "Infopoint I.A.T. / Pro Loco",
      info: "Tourist Information and Reception",
      contatto: "Tel: +39 0971 983002 | Mobile: +39 342 989 6770",
      source: "Ricerca Web",
      status: "verified"
    },
    {
      id: 2,
      nome: "Municipality of Pietrapertosa",
      info: "Switchboard and Municipal Offices",
      contatto: "Tel: +39 0971 983052",
      source: "Ricerca Web",
      status: "verified"
    },
    {
      id: 3,
      nome: "Flight of the Angel",
      info: "Ticket Office and Facility Information",
      contatto: "Tel: +39 0971 983110 | Mobile: +39 342 989 6770",
      source: "Ricerca Web",
      status: "verified"
    }
  ]
};

export const vivereData = {
  it: vivereDataIt,
  en: vivereDataEn,
  ...vivereDataIt
};