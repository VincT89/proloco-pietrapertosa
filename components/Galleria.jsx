"use client";

import React, { useRef, useState, useEffect } from "react";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { useStore } from "./Store";

export default function Galleria() {
  const { posts, openLightbox } = useStore();
  const railRef = useRef(null);
  const [canScrollLeft, setCanScrollLeft] = useState(false);
  const [canScrollRight, setCanScrollRight] = useState(true);
  const [progress, setProgress] = useState(0);

  const fotos = posts.filter((p) => p.type === "foto").sort((a, b) => b.id - a.id);

  const updateScroll = () => {
    if (!railRef.current) return;
    const { scrollLeft, scrollWidth, clientWidth } = railRef.current;
    const max = scrollWidth - clientWidth;
    setCanScrollLeft(scrollLeft > 4);
    setCanScrollRight(scrollLeft < max - 4);
    setProgress(max > 0 ? (scrollLeft / max) * 100 : 0);
  };

  useEffect(() => {
    updateScroll();
    window.addEventListener("resize", updateScroll);
    return () => window.removeEventListener("resize", updateScroll);
  }, [fotos]);

  const galStep = () => {
    if (!railRef.current) return 340;
    const shot = railRef.current.querySelector(".shot");
    return shot ? shot.offsetWidth + 28 : 340;
  };

  const scrollLeft = () => railRef.current?.scrollBy({ left: -galStep(), behavior: "smooth" });
  const scrollRight = () => railRef.current?.scrollBy({ left: galStep(), behavior: "smooth" });

  return (
    <section className="gal" id="galleria">
      <div className="sec-top">
        <span className="lbl">l'album delle feste e del paese</span>
      </div>
      <div className="rail-wrap">
        <button 
          className="garr lato sx" 
          onClick={scrollLeft} 
          disabled={!canScrollLeft}
          aria-label="Foto precedenti"
        >
          <ChevronLeft className="ico" />
        </button>
        <button 
          className="garr lato dx" 
          onClick={scrollRight} 
          disabled={!canScrollRight}
          aria-label="Foto successive"
        >
          <ChevronRight className="ico" />
        </button>
        <div 
          className="rail" 
          id="rail" 
          ref={railRef} 
          onScroll={updateScroll}
        >
          {fotos.map((f) => (
            <figure key={f.id} className="shot">
              <div 
                className="frame cur in" 
                onClick={() => openLightbox(f.img, f.title)}
              >
                <img src={f.img} alt={f.title} loading="lazy" />
              </div>
              <div className="cap">
                <span className="t">{f.title}</span>
              </div>
            </figure>
          ))}
        </div>
      </div>
      
      {fotos.length === 0 && (
        <div className="news-empty" style={{ margin: "0 clamp(20px, 4.5vw, 64px)", borderColor: "var(--hair)", color: "var(--stone)" }}>
          Ancora nessuna foto — le prime arrivano presto.
        </div>
      )}
      
      <div className="gal-progress">
        <i style={{ width: `${progress}%` }}></i>
      </div>
    </section>
  );
}