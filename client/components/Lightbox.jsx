"use client";

import React, { useState, useEffect } from "react";
import { X, ChevronLeft, ChevronRight } from "lucide-react";
import { useStore } from "./Store";

export default function Lightbox() {
  const { lightboxImg, closeLightbox } = useStore();
  const [currentIndex, setCurrentIndex] = useState(0);

  useEffect(() => {
    if (lightboxImg && lightboxImg.isGallery) {
      setCurrentIndex(lightboxImg.currentIndex || 0);
    }
  }, [lightboxImg]);

  if (!lightboxImg) return null;

  const isGallery = lightboxImg.isGallery;
  const images = lightboxImg.images || [];

  const next = (e) => {
    e.stopPropagation();
    setCurrentIndex((prev) => (prev + 1) % images.length);
  };

  const prev = (e) => {
    e.stopPropagation();
    setCurrentIndex((prev) => (prev - 1 + images.length) % images.length);
  };

  const currentImg = isGallery ? images[currentIndex] : lightboxImg;
  const caption = currentImg.cap || currentImg.alt || "Galleria";

  return (
    <div className="modal open" id="lightbox" style={{ display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
      <div className="ov" onClick={closeLightbox}></div>
      <div className="img-wrap" style={{ position: 'relative', display: 'flex', alignItems: 'center', justifyContent: 'center', maxWidth: '90vw', maxHeight: '90vh' }}>
        
        {isGallery && images.length > 1 && (
          <button 
            onClick={prev} 
            style={{ position: 'absolute', left: '-60px', background: 'rgba(0,0,0,0.5)', border: 'none', color: 'white', cursor: 'pointer', borderRadius: '50%', padding: '10px', display: 'flex', zIndex: 20 }}
            aria-label="Precedente"
          >
            <ChevronLeft size={32} />
          </button>
        )}

        <div style={{ position: 'relative', display: 'inline-block' }}>
          <button className="x" onClick={closeLightbox} aria-label="Chiudi" style={{ zIndex: 30, right: '-15px', top: '-15px' }}>
            <X className="ico" />
          </button>
          
          <img id="lbImg" src={currentImg.src} alt={caption} style={{ maxHeight: '85vh', maxWidth: '100%', objectFit: 'contain' }} />
          
          <div className="lb-cap" id="lbCap" style={{ background: 'rgba(0,0,0,0.7)', position: 'absolute', bottom: 0, left: 0, right: 0, padding: '15px' }}>
            {caption}
            {isGallery && images.length > 1 && <span style={{ marginLeft: '10px', color: 'var(--gold-soft)', fontSize: '0.85em' }}>({currentIndex + 1} / {images.length})</span>}
          </div>
        </div>

        {isGallery && images.length > 1 && (
          <button 
            onClick={next} 
            style={{ position: 'absolute', right: '-60px', background: 'rgba(0,0,0,0.5)', border: 'none', color: 'white', cursor: 'pointer', borderRadius: '50%', padding: '10px', display: 'flex', zIndex: 20 }}
            aria-label="Successiva"
          >
            <ChevronRight size={32} />
          </button>
        )}

      </div>
    </div>
  );
}