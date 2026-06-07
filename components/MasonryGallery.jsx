"use client";

import React, { useState, useEffect } from 'react';
import { useStore } from '@/components/Store';

export default function MasonryGallery({ images = [] }) {
  const [cols, setCols] = useState(3);
  const { openLightbox } = useStore();

  useEffect(() => {
    const handleResize = () => {
      if (window.innerWidth < 600) setCols(1);
      else if (window.innerWidth < 900) setCols(2);
      else setCols(3);
    };
    handleResize();
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  if (!images || images.length === 0) return null;

  // Distribuzione masonry manuale in colonne
  const columns = Array.from({ length: cols }, () => []);
  images.forEach((img, i) => {
    columns[i % cols].push(img);
  });

  return (
    <div style={{ display: 'flex', gap: '20px', margin: '40px 0' }}>
      {columns.map((col, colIndex) => (
        <div key={colIndex} style={{ display: 'flex', flexDirection: 'column', gap: '20px', flex: 1 }}>
          {col.map((img) => (
            <div key={img.id} className="cur fad" style={{ position: 'relative', width: '100%', overflow: 'hidden' }} onClick={() => openLightbox(img.src, img.alt)}>
              <img 
                src={img.src} 
                alt={img.alt || "Galleria Pietrapertosa"} 
                style={{ width: '100%', display: 'block', transition: 'transform 0.4s ease' }} 
                onMouseEnter={(e) => e.currentTarget.style.transform = 'scale(1.03)'}
                onMouseLeave={(e) => e.currentTarget.style.transform = 'scale(1)'}
              />
              <div style={{ position: 'absolute', bottom: 0, left: 0, width: '100%', padding: '24px 16px 16px', background: 'linear-gradient(transparent, rgba(0,0,0,0.9))', color: 'white', opacity: 0, transition: 'opacity 0.3s ease', pointerEvents: 'none' }} 
                   onMouseEnter={(e) => e.currentTarget.style.opacity = 1}
                   onMouseLeave={(e) => e.currentTarget.style.opacity = 0}>
                <span style={{ fontSize: '0.8rem', textTransform: 'uppercase', letterSpacing: '1px', color: 'var(--gold-soft)', fontWeight: '600' }}>{img.category}</span>
              </div>
            </div>
          ))}
        </div>
      ))}
    </div>
  );
}
