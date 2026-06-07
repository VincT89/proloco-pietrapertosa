"use client";

import React from "react";

export default function Comunita() {
  const persone = [
    { id: 1, title: "Volontari", desc: "Il motore silenzioso di ogni iniziativa, pronti a donare tempo e cuore per mantenere vivo e accogliente il nostro paese.", img: "https://placehold.co/600x600/333/d4af37?text=Volontari" },
    { id: 2, title: "Associazioni", desc: "Le anime attive che collaborano per creare esperienze culturali e sociali che arricchiscono la vita della comunità.", img: "https://placehold.co/600x600/333/d4af37?text=Associazioni" },
    { id: 3, title: "Artigiani", desc: "Le mani che lavorano la pietra, il legno e i tessuti, tramandando mestieri antichi e memorie preziose.", img: "https://placehold.co/600x600/333/d4af37?text=Artigiani" }
  ];

  return (
    <section id="comunita">
      <div className="wrap">
        <div className="sec-top">
          <span className="lbl">il cuore umano</span>
        </div>
        <h2 className="wr">
          Chi vive il <em>territorio</em>
        </h2>
        <p className="dc fad" style={{ marginBottom: "clamp(40px, 6vw, 80px)" }}>
          Un borgo vive grazie alle persone che ogni giorno ne custodiscono la memoria, le tradizioni e l'accoglienza.
        </p>

        <div style={{ 
          display: "grid", 
          gridTemplateColumns: "repeat(auto-fit, minmax(280px, 1fr))", 
          gap: "40px 30px" 
        }}>
          {persone.map((persona) => (
            <div key={persona.id} className="fad" style={{ display: "flex", flexDirection: "column", gap: "16px" }}>
              <div style={{ width: "100%", aspectRatio: "1/1", overflow: "hidden" }}>
                <img src={persona.img} alt={persona.title} loading="lazy" style={{ width: "100%", height: "100%", objectFit: "cover" }} />
              </div>
              <div>
                <h3 className="wr" style={{ fontSize: "1.4rem", margin: "0 0 8px 0" }}>{persona.title}</h3>
                <p style={{ margin: 0, color: "var(--stone)", lineHeight: "1.5" }}>{persona.desc}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
