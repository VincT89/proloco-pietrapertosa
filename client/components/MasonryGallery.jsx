"use client";

import React from 'react';
import { useStore } from '@/components/Store';

export default function MasonryGallery({ images = [] }) {
  const { openLightbox } = useStore();

  if (!images || images.length === 0) return null;

  return (
    <div style={{ 
      display: 'grid', 
      gridTemplateColumns: 'repeat(auto-fill, minmax(180px, 1fr))', 
      gap: '12px', 
      marginBottom: '60px' 
    }}>
      {images.map((img, index) => (
        <div key={img.id} className="cur fad" style={{ position: 'relative', width: '100%', aspectRatio: '1', overflow: 'hidden', borderRadius: '4px' }} onClick={() => openLightbox(images, index)}>
          <img 
            src={img.src} 
            alt={img.alt || "Galleria Pietrapertosa"} 
            style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block', transition: 'transform 0.4s ease' }} 
            onMouseEnter={(e) => e.currentTarget.style.transform = 'scale(1.05)'}
            onMouseLeave={(e) => e.currentTarget.style.transform = 'scale(1)'}
          />
          <div style={{ position: 'absolute', bottom: 0, left: 0, width: '100%', padding: '24px 12px 12px', background: 'linear-gradient(transparent, rgba(0,0,0,0.8))', color: 'white', opacity: 0, transition: 'opacity 0.3s ease', pointerEvents: 'none' }} 
               onMouseEnter={(e) => e.currentTarget.style.opacity = 1}
               onMouseLeave={(e) => e.currentTarget.style.opacity = 0}>
            <span style={{ fontSize: '0.75rem', textTransform: 'uppercase', letterSpacing: '1px', color: 'var(--gold-soft)', fontWeight: '600' }}>{img.category}</span>
          </div>
        </div>
      ))}
    </div>
  );
}
