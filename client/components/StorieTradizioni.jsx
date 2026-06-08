"use client";

import React from "react";

export default function StorieTradizioni() {
  const storie = [
    { 
      id: 1, 
      title: "Arabata", 
      desc: "Il rione più antico del borgo, nato durante la dominazione araba e ancora oggi simbolo dell'identità storica di Pietrapertosa.", 
      img: "/images/arabata.jpeg" 
    },
    { 
      id: 2, 
      title: "Sulle Tracce degli Arabi", 
      desc: "La rievocazione storica che riporta in vita profumi, suoni e atmosfere di quando i Saraceni abitavano le nostre rocce.", 
      imgs: [
        "https://placehold.co/600x800/111/d4af37?text=Main",
        "https://placehold.co/400x400/222/d4af37?text=Dettaglio+1",
        "https://placehold.co/400x400/1a1a1a/d4af37?text=Dettaglio+2"
      ] 
    },
    { 
      id: 3, 
      title: "Castello Saraceno", 
      desc: "Una fortezza inespugnabile incastonata nella pietra, testimone silenzioso della storia araba e normanno-sveva.", 
      img: "/images/castello.jpg" 
    },
    { 
      id: 4, 
      title: "Tradizioni popolari", 
      desc: "I canti, i balli e i riti agresti che segnavano i raccolti e che ancora oggi animano le nostre piazze.", 
      imgs: [
        "https://placehold.co/600x800/111/d4af37?text=Festa",
        "https://placehold.co/400x400/222/d4af37?text=Musica",
        "https://placehold.co/400x400/1a1a1a/d4af37?text=Danza"
      ] 
    },
    { 
      id: 5, 
      title: "Memoria locale", 
      desc: "I racconti tramandati davanti al focolare, le voci degli anziani e le leggende nate all'ombra delle Dolomiti Lucane.", 
      imgs: [
        "https://placehold.co/600x800/111/d4af37?text=Anziani",
        "https://placehold.co/400x400/222/d4af37?text=Storie",
        "https://placehold.co/400x400/1a1a1a/d4af37?text=Roccia"
      ] 
    },
    { 
      id: 6, 
      title: "Identità del borgo", 
      desc: "L'essenza vera di Pietrapertosa: un legame indissolubile tra l'uomo, la roccia e la storia millenaria.", 
      imgs: [
        "https://placehold.co/600x800/111/d4af37?text=Paese",
        "https://placehold.co/400x400/222/d4af37?text=Volto",
        "https://placehold.co/400x400/1a1a1a/d4af37?text=Dettaglio"
      ] 
    },
  ];

  return (
    <section id="storie" className="dark-sec">
      <div className="wrap">
        <div className="sec-top">
          <span className="lbl-mut">Radici profonde</span>
        </div>
        <h2 className="wr">
          Storie e <em>Tradizioni</em>
        </h2>
        <p className="dc fad" style={{ marginBottom: "clamp(40px, 6vw, 80px)" }}>
          La pietra di Pietrapertosa non è muta. È incisa dalle dominazioni, dalle battaglie e dalla vita quotidiana di chi l'ha abitata nei secoli.
        </p>

        {/* 3-Column Magazine Layout (No zig-zag, no cards) */}
        <div style={{ 
          display: "grid", 
          gridTemplateColumns: "repeat(auto-fit, minmax(320px, 1fr))", 
          gap: "60px 40px" 
        }}>
          {storie.map((storia) => (
            <div key={storia.id} className="fad" style={{ display: "flex", flexDirection: "column", gap: "20px" }}>
              <div style={{ width: "100%", aspectRatio: "4/5", overflow: "hidden", background: "rgba(255,255,255,0.02)" }}>
                {storia.imgs ? (
                  <div style={{ display: "grid", gridTemplateColumns: "1.8fr 1fr", gridTemplateRows: "1fr 1fr", gap: "0px", width: "100%", height: "100%" }}>
                    <div className="bgi" style={{ backgroundImage: `url('${storia.imgs[0]}')`, gridRow: "1 / span 2" }}></div>
                    <div className="bgi" style={{ backgroundImage: `url('${storia.imgs[1]}')` }}></div>
                    <div className="bgi" style={{ backgroundImage: `url('${storia.imgs[2]}')` }}></div>
                  </div>
                ) : (
                  <div className="bgi" style={{ backgroundImage: `url('${storia.img}')`, width: "100%", height: "100%" }}></div>
                )}
              </div>
              <div>
                <span className="lbl-mut" style={{ fontSize: "0.75rem", marginBottom: "8px", display: "inline-block" }}>Storia e Identità</span>
                <h3 className="wr" style={{ fontSize: "1.6rem", margin: "0 0 12px 0", color: "var(--paper)" }}>{storia.title}</h3>
                <p style={{ margin: 0, color: "var(--stone)", lineHeight: "1.6" }}>{storia.desc}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
