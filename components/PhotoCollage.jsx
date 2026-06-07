"use client";

import React from 'react';
import { useStore } from '@/components/Store';

export default function PhotoCollage({ images = [] }) {
  const { openLightbox } = useStore();

  if (!images || images.length === 0) return null;

  return (
      <div className="photo-collage" style={{ 
        display: 'grid', 
        gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', 
        gap: '20px',
        margin: '0 0 40px 0'
      }}>
      {images.map((src, index) => {
        // Alternanza: grande, piccola, grande, piccola...
        // Una foto "grande" copre 2 colonne se lo spazio lo permette.
        const isLarge = index % 3 === 0; // Ogni 3ª foto è grande per creare asimmetria

        return (
          <div 
            key={index} 
            className="collage-item cur"
            onClick={() => openLightbox(src, "Galleria Pietrapertosa")}
            style={{
              aspectRatio: isLarge ? '16/9' : '4/3',
              gridColumn: isLarge ? 'span 2' : 'span 1',
              borderRadius: '0', // Niente bordi arrotondati come richiesto in precedenza
              overflow: 'hidden',
              position: 'relative'
            }}
          >
            <div 
              style={{
                width: '100%',
                height: '100%',
                backgroundImage: `url('${src}')`,
                backgroundSize: 'cover',
                backgroundPosition: 'center',
                transition: 'transform 0.5s ease',
              }}
              onMouseEnter={(e) => e.currentTarget.style.transform = 'scale(1.05)'}
              onMouseLeave={(e) => e.currentTarget.style.transform = 'scale(1)'}
            />
          </div>
        );
      })}
      
      {/* Media query per mobile: annulla lo span 2 */}
      <style dangerouslySetInnerHTML={{__html: `
        @media (max-width: 768px) {
          .collage-item {
            grid-column: span 1 !important;
            aspect-ratio: 4/3 !important;
          }
        }
      `}} />
    </div>
  );
}
