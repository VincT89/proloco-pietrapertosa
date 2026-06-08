"use client";

import React from 'react';
import { BookOpen, Compass, HeartHandshake, Utensils, Users, Fingerprint } from 'lucide-react';

export default function TerritorioValue() {
  const pilastri = [
    { icon: <BookOpen />, title: "Storie", desc: "Vicende e racconti che attraversano secoli di memoria." },
    { icon: <Compass />, title: "Tradizioni", desc: "Gesti antichi tramandati di generazione in generazione." },
    { icon: <Utensils />, title: "Sapori", desc: "Prodotti genuini e ricette che raccontano la terra." },
    { icon: <HeartHandshake />, title: "Esperienze", desc: "Vivere il borgo tra avventura e ritmi lenti." },
    { icon: <Users />, title: "Comunità", desc: "Persone, volti e il calore di un'accoglienza vera." },
    { icon: <Fingerprint />, title: "Identità", desc: "L'anima autentica di chi vive tra queste pietre." }
  ];

  return (
    <section id="territorio" style={{ padding: "clamp(80px, 10vw, 120px) 0" }}>
      <div className="wrap">
        <div style={{ textAlign: "center", marginBottom: "clamp(60px, 8vw, 80px)" }}>
          <span className="lbl fad" style={{ marginBottom: "16px" }}>Il manifesto del nostro borgo</span>
          <h2 className="wr" style={{ fontSize: "clamp(2.4rem, 4.5vw, 4rem)", maxWidth: "800px", margin: "0 auto", lineHeight: "1.1" }}>
            Molto più di un <em>borgo tra le rocce</em>
          </h2>
        </div>

        {/* Architectural Grid (Sharp, seamless 1px borders) */}
        <div className="fad" style={{ 
          display: "grid",
          gridTemplateColumns: "repeat(auto-fit, minmax(300px, 1fr))",
          borderTop: "1px solid var(--hair-d)",
          borderLeft: "1px solid var(--hair-d)",
          maxWidth: "1100px",
          margin: "0 auto",
          background: "var(--paper)"
        }}>
          {pilastri.map((p, idx) => (
            <div key={idx} style={{
              padding: "40px",
              borderRight: "1px solid var(--hair-d)",
              borderBottom: "1px solid var(--hair-d)",
              display: "flex",
              flexDirection: "column",
              gap: "16px",
              transition: "background 0.3s ease",
            }}
            onMouseEnter={(e) => e.currentTarget.style.background = "var(--ink)"}
            onMouseLeave={(e) => e.currentTarget.style.background = "var(--paper)"}
            className="manifesto-cell"
            >
              <div style={{ color: "var(--gold-soft)", width: "32px", height: "32px" }}>
                {p.icon}
              </div>
              <h4 className="wr" style={{ 
                fontSize: "1.8rem", 
                margin: 0,
                transition: "color 0.3s ease"
              }}>
                {p.title}
              </h4>
              <p style={{ 
                color: "var(--stone)", 
                fontSize: "1.05rem", 
                lineHeight: "1.6", 
                margin: 0,
                transition: "color 0.3s ease"
              }}>
                {p.desc}
              </p>
            </div>
          ))}
        </div>
      </div>
      <style dangerouslySetInnerHTML={{__html: `
        .manifesto-cell:hover h4 { color: var(--paper) !important; }
        .manifesto-cell:hover p { color: rgba(255,255,255,0.7) !important; }
      `}} />
    </section>
  );
}
