"use client";

import React from "react";

export default function Eventi() {
  const eventi = [
    {
      id: 1,
      title: "Sulle Tracce degli Arabi",
      date: "Agosto",
      desc: "La rievocazione storica che trasforma il borgo. Profumi d'oriente, danze, mangiafuoco e figuranti riportano in vita l'antica dominazione saracena tra i vicoli dell'Arabata.",
      img: "https://placehold.co/600x400/222/d4af37?text=Arabi",
    },
    {
      id: 2,
      title: "Sapori d'Autunno",
      date: "Ottobre / Novembre",
      desc: "L'evento enogastronomico principale. Cantine aperte, caldarroste, vino novello e piatti della tradizione per celebrare i frutti della terra fredda.",
      img: "https://placehold.co/600x400/222/d4af37?text=Autunno",
    },
    {
      id: 3,
      title: "Tradizioni religiose",
      date: "Tutto l'anno",
      desc: "Dalle processioni storiche ai falò devozionali, riti millenari dove fede, folklore e memoria si fondono nel cuore della comunità.",
      img: "https://placehold.co/600x400/222/d4af37?text=Tradizioni",
    },
    {
      id: 4,
      title: "Estate nel borgo",
      date: "Luglio / Agosto",
      desc: "Musica dal vivo, teatro all'aperto, sagre di quartiere e serate sotto le stelle per vivere la magia delle Dolomiti Lucane quando cala la notte.",
      img: "https://placehold.co/600x400/222/d4af37?text=Estate",
    },
  ];

  return (
    <section id="eventi" className="dark-sec">
      <div className="wrap">
        <div className="sec-top">
          <span className="lbl">condivisione</span>
        </div>
        <h2 className="wr">
          Momenti che tengono viva la <em>comunità</em>
        </h2>
        <p className="dc fad" style={{ marginBottom: "clamp(36px, 5vw, 60px)" }}>
          Gli eventi non sono solo appuntamenti in calendario: sono occasioni in cui il paese si ritrova, accoglie e racconta la propria identità.
        </p>

        <div className="ev-grid">
          {eventi.map((ev) => (
            <div key={ev.id} className="evc cur fad">
              <div
                className="ev-bg"
                style={{ backgroundImage: `url(${ev.img})` }}
              ></div>
              <div className="ribbon">{ev.date}</div>
              <div className="ev-b">
                <h3>{ev.title}</h3>
                <p>{ev.desc}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}