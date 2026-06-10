"use client";

import React from "react";
import { useStore } from "./Store";
import { proLocoData } from "@/data/pro-loco";
import { useSearchParams } from 'next/navigation';
import { getLang } from '@/utils/lang';

export default function Squadra() {
  const searchParams = useSearchParams();
  const lang = getLang(searchParams);
  const isEn = lang === 'en';
  const data = isEn ? proLocoData.en : proLocoData.it;
  const s = data.squadra;
  const { openLightbox } = useStore();

  return (
    <section id="squadra">
      <div className="wrap">
        <div className="sec-top">
          <span className="lbl">{isEn ? "the faces of Pro Loco" : "le facce della Pro Loco"}</span>
        </div>

        <h2 className="wr" style={{ marginBottom: "clamp(36px,5vw,60px)" }} dangerouslySetInnerHTML={{ __html: s.titolo.replace('il palco', '<em>il palco</em>').replace('the stage', '<em>the stage</em>') }}>
        </h2>

        <div className="team-due">
          <figure className="team-foto cur in" id="teamPhoto">
            <img
              src={s.img}
              onClick={() => openLightbox(s.img, s.sottotitolo)}
              style={{
                width: "100%",
                cursor: "zoom-in",
                marginBottom: "12px",
              }}
              alt={s.sottotitolo}
            />
            <figcaption>{isEn ? "The complete team — Pro Loco volunteers" : "La squadra al completo — i volontari della Pro Loco"}</figcaption>
          </figure>

          <div className="team-lista">
            {s.membri.map((m, i) => (
              <div className="riga-m fad" key={i}>
                <span className="ruolo">{m.ruolo}</span>
                <span className="nome-m">{m.nome}</span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}