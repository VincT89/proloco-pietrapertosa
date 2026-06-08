"use client";
import React from 'react';
import { useStore } from '@/components/Store';

export default function EventCard({ event }) {
  const { openLightbox } = useStore();
  // Ripristinato l'uso esatto delle classi CSS dell'HTML (.evc, .poster, .ribbon)
  return (
    <div className="evc">
      <div className="ribbon">{event.date}</div>
      <div className="poster cur" onClick={() => openLightbox(event.img || "https://placehold.co/400x533/14181f/d9aa63?text=Locandina", event.title)}>
        <img src={event.img || "https://placehold.co/400x533/14181f/d9aa63?text=Locandina"} alt={event.title} loading="lazy" />
        <div className="zoom"><svg className="ico" viewBox="0 0 24 24"><use href="#i-zoom" /></svg></div>
      </div>
      <div className="body">
        <h3 className="wr">{event.title}</h3>
        <p className="fad">{event.desc}</p>
      </div>
    </div>
  );
}
