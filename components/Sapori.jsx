"use client";

import React from "react";

export default function Sapori() {
  const sapori = [
    { 
      id: 1, 
      title: "Sapori d'Autunno", 
      desc: "L'evento enogastronomico per eccellenza, dove i sapori veri del bosco e della terra celebrano l'arrivo della stagione fredda.", 
      imgs: [
        "https://placehold.co/1200x600/222/d4af37?text=Autunno",
        "https://placehold.co/600x600/111/d4af37?text=Vino",
        "https://placehold.co/600x600/1a1a1a/d4af37?text=Castagne"
      ] 
    },
    { 
      id: 2, 
      title: "Prodotti genuini", 
      desc: "Formaggi, ricotta freschissima e l'inconfondibile pecorino locale, frutto del lavoro lento dei nostri pastori.", 
      imgs: [
        "https://placehold.co/800x800/222/d4af37?text=Formaggio",
        "https://placehold.co/400x400/111/d4af37?text=Ricotta",
        "https://placehold.co/400x400/1a1a1a/d4af37?text=Pascolo"
      ] 
    },
    { 
      id: 3, 
      title: "Cucina delle feste", 
      desc: "Pasta fatta in casa, dolci tradizionali e carni ricche che profumano le strade durante le ricorrenze più importanti.", 
      imgs: [
        "https://placehold.co/800x800/222/d4af37?text=Tavolata",
        "https://placehold.co/400x400/111/d4af37?text=Pasta",
        "https://placehold.co/400x400/1a1a1a/d4af37?text=Carne"
      ] 
    },
    { 
      id: 4, 
      title: "Tradizione contadina", 
      desc: "Piatti poveri ma incredibilmente ricchi di gusto, nati dall'ingegno di chi ha sempre vissuto in simbiosi con la natura.", 
      imgs: [
        "https://placehold.co/800x800/222/d4af37?text=Natura",
        "https://placehold.co/400x400/111/d4af37?text=Zuppa",
        "https://placehold.co/400x400/1a1a1a/d4af37?text=Orto"
      ] 
    },
    { 
      id: 5, 
      title: "Artigianato gastronomico", 
      desc: "Salumi e conserve preparati ancora secondo i metodi secolari, per preservare l'autenticità di ogni singolo ingrediente.", 
      img: "https://placehold.co/1200x800/222/d4af37?text=Artigianato" 
    },
  ];

  const heroItem = sapori[0];
  const gridItems = sapori.slice(1);

  return (
    <section id="sapori" className="paper-sec">
      <div className="wrap">
        <div className="sec-top">
          <span className="lbl">il gusto del territorio</span>
        </div>
        <h2 className="wr">
          Sapori e <em>Prodotti Locali</em>
        </h2>
        <p className="dc fad" style={{ marginBottom: "clamp(50px, 7vw, 100px)" }}>
          La vera essenza di Pietrapertosa si scopre a tavola, in un intreccio perfetto tra sapori, stagionalità, materie prime e tradizioni familiari.
        </p>

        {/* Hero Item (Side by Side) */}
        <div className="fad" style={{ 
          marginBottom: "clamp(60px, 8vw, 100px)", 
          display: "flex", 
          flexWrap: "wrap", 
          gap: "clamp(30px, 5vw, 60px)", 
          alignItems: "center" 
        }}>
          <div style={{ flex: "1 1 500px", aspectRatio: "16/9", maxHeight: "400px", overflow: "hidden" }}>
            <div style={{ display: "grid", gridTemplateColumns: "2fr 1fr", gridTemplateRows: "1fr 1fr", gap: "8px", width: "100%", height: "100%" }}>
              <div className="bgi" style={{ backgroundImage: `url('${heroItem.imgs[0]}')`, gridRow: "1 / span 2" }}></div>
              <div className="bgi" style={{ backgroundImage: `url('${heroItem.imgs[1]}')` }}></div>
              <div className="bgi" style={{ backgroundImage: `url('${heroItem.imgs[2]}')` }}></div>
            </div>
          </div>
          <div style={{ flex: "1 1 300px", maxWidth: "500px" }}>
            <span className="lbl-mut" style={{ fontSize: "0.85rem", marginBottom: "12px", display: "inline-block" }}>Evento di punta</span>
            <h3 className="wr" style={{ fontSize: "clamp(2rem, 3.5vw, 2.8rem)", margin: "0 0 16px 0", color: "var(--ink)" }}>{heroItem.title}</h3>
            <p style={{ margin: 0, color: "var(--stone)", lineHeight: "1.7", fontSize: "1.1rem" }}>{heroItem.desc}</p>
          </div>
        </div>

        {/* 2-Column Grid for the rest */}
        <div style={{ 
          display: "grid", 
          gridTemplateColumns: "repeat(auto-fit, minmax(350px, 1fr))", 
          gap: "60px 40px" 
        }}>
          {gridItems.map((sapore) => (
            <div key={sapore.id} className="fad" style={{ display: "flex", flexDirection: "column", gap: "24px" }}>
              <div style={{ width: "100%", aspectRatio: "4/3", overflow: "hidden" }}>
                {sapore.imgs ? (
                  <div style={{ display: "grid", gridTemplateColumns: "1.5fr 1fr", gridTemplateRows: "1fr 1fr", gap: "8px", width: "100%", height: "100%" }}>
                    <div className="bgi" style={{ backgroundImage: `url('${sapore.imgs[0]}')`, gridRow: "1 / span 2" }}></div>
                    <div className="bgi" style={{ backgroundImage: `url('${sapore.imgs[1]}')` }}></div>
                    <div className="bgi" style={{ backgroundImage: `url('${sapore.imgs[2]}')` }}></div>
                  </div>
                ) : (
                  <div className="bgi" style={{ backgroundImage: `url('${sapore.img}')`, width: "100%", height: "100%" }}></div>
                )}
              </div>
              <div>
                <span className="lbl-mut" style={{ fontSize: "0.75rem", marginBottom: "8px", display: "inline-block" }}>Materia prima</span>
                <h3 className="wr" style={{ fontSize: "1.8rem", margin: "0 0 12px 0", color: "var(--ink)" }}>{sapore.title}</h3>
                <p style={{ margin: 0, color: "var(--stone)", lineHeight: "1.6" }}>{sapore.desc}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
