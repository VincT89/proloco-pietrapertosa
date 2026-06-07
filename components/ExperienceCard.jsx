"use client";
import React from 'react';
import { useStore } from '@/components/Store';

export default function ExperienceCard({ esperienza, reverse }) {
  const { openLightbox } = useStore();
  // Ripristinato l'uso esatto delle classi CSS dell'HTML (.exp-row, .exp-img, .exp-txt, .exp-data)
  return (
    <div className={`exp-row ${reverse ? 'rev' : ''}`}>
      <div className="exp-img cur" onClick={() => openLightbox(esperienza.img, esperienza.title)}>
        <div className="bgi" style={{ backgroundImage: `url('${esperienza.img}')` }}></div>
      </div>
      <div className="exp-txt">
        <span className="lbl-mut fad">{esperienza.subtitle}</span>
        <h3 className="wr">{esperienza.title}</h3>
        <p className="fad">{esperienza.desc}</p>
        
        {esperienza.stats && (
          <div className="exp-data fad">
            {esperienza.stats.map((stat, idx) => (
              <div key={idx}>
                <div className="v">{stat.value}</div>
                <div className="c">{stat.label}</div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
