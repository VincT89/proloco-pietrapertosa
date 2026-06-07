"use client";

import React from "react";
import { X } from "lucide-react";
import { useStore } from "./Store";

export default function Lightbox() {
  const { lightboxImg, closeLightbox } = useStore();

  if (!lightboxImg) return null;

  return (
    <div className="modal open" id="lightbox">
      <div className="ov" onClick={closeLightbox}></div>
      <div className="img-wrap">
        <button className="x" onClick={closeLightbox} aria-label="Chiudi">
          <X className="ico" />
        </button>
        <img id="lbImg" src={lightboxImg.src} alt={lightboxImg.cap} />
        <div className="lb-cap" id="lbCap">
          {lightboxImg.cap}
        </div>
      </div>
    </div>
  );
}