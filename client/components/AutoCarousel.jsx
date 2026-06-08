"use client";

import React, { useState, useEffect } from "react";

export default function AutoCarousel({ images, interval = 4000, objectFit = "cover" }) {
  const [currentIndex, setCurrentIndex] = useState(0);

  useEffect(() => {
    if (!images || images.length <= 1) return;

    const timer = setInterval(() => {
      setCurrentIndex((prevIndex) => (prevIndex + 1) % images.length);
    }, interval);

    return () => clearInterval(timer);
  }, [images, interval]);

  if (!images || images.length === 0) return null;

  return (
    <div style={{ position: "relative", width: "100%", height: "100%", overflow: "hidden", background: "transparent" }}>
      {images.map((imgUrl, idx) => (
        <img
          key={idx}
          src={imgUrl}
          alt={`Immagine carosello ${idx + 1}`}
          style={{
            position: "absolute",
            top: 0,
            left: 0,
            width: "100%",
            height: "100%",
            objectFit: objectFit,
            opacity: idx === currentIndex ? 1 : 0,
            transition: "opacity 1.2s ease-in-out",
            zIndex: idx === currentIndex ? 1 : 0
          }}
        />
      ))}
      
      {images.length > 1 && (
        <div style={{ position: "absolute", bottom: "20px", left: "0", right: "0", display: "flex", justifyContent: "center", gap: "8px", zIndex: 2 }}>
          {images.map((_, idx) => (
            <div
              key={idx}
              onClick={() => setCurrentIndex(idx)}
              style={{
                width: "8px",
                height: "8px",
                borderRadius: "50%",
                background: idx === currentIndex ? "var(--gold)" : "rgba(255,255,255,0.4)",
                cursor: "pointer",
                transition: "background 0.3s ease"
              }}
            />
          ))}
        </div>
      )}
    </div>
  );
}
