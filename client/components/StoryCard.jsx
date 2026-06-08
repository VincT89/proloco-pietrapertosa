"use client";
import React from 'react';
import { useStore } from '@/components/Store';

export default function StoryCard({ storia, reverse }) {
  const { openLightbox } = useStore();
  // Se la storia ha 3 immagini, usa bi1, bi2, bi3. Altrimenti usa solo bi1 a tutto schermo
  const hasMultipleImgs = storia.imgs && storia.imgs.length >= 3;

  return (
    <div className={`borgo-grid ${reverse ? 'rev' : ''}`} style={{ marginBottom: "clamp(80px, 12vw, 140px)" }}>
      <div className="borgo-imgs">
        {hasMultipleImgs ? (
          <>
            <div className="bi1 cur" onClick={() => openLightbox(storia.imgs[0], storia.title)}><img src={storia.imgs[0]} alt={storia.title} loading="lazy" style={{ borderRadius: '0' }} /></div>
            <div className="bi2 cur" onClick={() => openLightbox(storia.imgs[1], "Dettaglio 1")}><img src={storia.imgs[1]} alt="Dettaglio 1" loading="lazy" style={{ borderRadius: '0' }} /></div>
            <div className="bi3 cur" onClick={() => openLightbox(storia.imgs[2], "Dettaglio 2")}><img src={storia.imgs[2]} alt="Dettaglio 2" loading="lazy" style={{ borderRadius: '0' }} /></div>
          </>
        ) : (
          <div className="bi1 cur" onClick={() => openLightbox(storia.img, storia.title)} style={{ width: '100%', height: '100%', borderRadius: '0' }}>
            <img src={storia.img} alt={storia.title} loading="lazy" style={{ width: '100%', height: '100%', objectFit: 'cover', borderRadius: '0' }} />
          </div>
        )}
        <div className="bi-cap fad">{storia.title}</div>
      </div>
      <div className="borgo-txt">
        <h2 className="wr" style={{ fontSize: "clamp(2rem, 3.5vw, 2.8rem)" }}>{storia.title}</h2>
        <p className="dc fad" style={{ fontSize: "1.1rem", color: "rgba(255, 255, 255, 0.75)" }}>{storia.desc}</p>
      </div>
    </div>
  );
}
