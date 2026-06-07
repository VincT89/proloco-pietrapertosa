"use client";

import React from "react";
import { useStore } from "./Store";

export default function Squadra() {
  const { openLightbox } = useStore();

  return (
    <section id="squadra">
      <div className="wrap">
        <div className="sec-top">
          <span className="lbl">le facce della Pro Loco</span>
        </div>

        <h2 className="wr" style={{ marginBottom: "clamp(36px,5vw,60px)" }}>
          Quelli che montano <em>il palco</em>
        </h2>

        <div className="team-due">
          <figure className="team-foto cur in" id="teamPhoto">
            <img
              src="/images/squadra1.jpg"
              onClick={() => openLightbox("/images/squadra1.jpg", "Il direttivo")}
              style={{
                width: "100%",
                cursor: "zoom-in",
                marginBottom: "12px",
              }}
              alt="Il direttivo"
            />
            <figcaption>La squadra al completo — i volontari della Pro Loco</figcaption>
          </figure>

          <div className="team-lista">
            <div className="riga-m fad"><span className="ruolo">Presidente</span><span className="nome-m">Laraia Marinella</span></div>
            <div className="riga-m fad"><span className="ruolo">Vicepresidente</span><span className="nome-m">Lucia Zottarelli</span></div>
            <div className="riga-m fad"><span className="ruolo">Segretario</span><span className="nome-m">Marilina Giannotta</span></div>
            <div className="riga-m fad"><span className="ruolo">Tesoriere</span><span className="nome-m">Marianna Giannotta</span></div>
            <div className="riga-m fad"><span className="ruolo">Consigliere</span><span className="nome-m">Marinella Pellettiere</span></div>
          </div>
        </div>
      </div>
    </section>
  );
}