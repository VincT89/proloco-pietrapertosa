"use client";
import React, { useState } from 'react';

export default function InteractiveCollage({ images, altText }) {
  const [ranks, setRanks] = useState([0, 1, 2]);

  if (!images || images.length === 0) return null;

  const handleSwap = (clickedOriginalIdx) => {
    const clickedRank = ranks[clickedOriginalIdx];
    if (clickedRank === 0) return; // already main

    setRanks(prevRanks => {
      return prevRanks.map((rank) => {
        if (rank === clickedRank) return 0; // clicked becomes main
        if (rank === 0) return clickedRank; // old main takes clicked place
        return rank; // third stays the same
      });
    });
  };

  const getStyleForRank = (rank) => {
    switch(rank) {
      case 0: // Immagine Principale (Più Grande)
        return { 
          width: '80%', height: '85%', top: '0', left: '0', 
          bottom: 'auto', right: 'auto', zIndex: 1, 
          border: 'none', boxShadow: 'none' 
        };
      case 1: // Secondaria in basso a destra
        return { 
          width: '50%', height: '50%', bottom: '0', right: '0', 
          top: 'auto', left: 'auto', zIndex: 2, 
          border: '8px solid var(--ink)', boxShadow: 'none' 
        };
      case 2: // Terziaria in alto a destra
        return { 
          width: '35%', height: '35%', top: '15%', right: '-2%', 
          bottom: 'auto', left: 'auto', zIndex: 3, 
          border: '8px solid var(--ink)', boxShadow: '0 20px 40px rgba(0,0,0,0.5)' 
        };
      default:
        return { display: 'none' };
    }
  };

  const displayImages = images.slice(0, 3);

  return (
    <div className="borgo-imgs" style={{ minHeight: '480px', width: '100%', direction: 'ltr', position: 'relative' }}>
      {displayImages.map((imgUrl, originalIdx) => {
        const rank = ranks[originalIdx];
        const dynamicStyle = getStyleForRank(rank);

        return (
          <div 
            key={originalIdx}
            onClick={() => handleSwap(originalIdx)}
            style={{ 
              position: 'absolute',
              transition: 'all 0.6s cubic-bezier(0.16, 1, 0.3, 1)',
              cursor: rank === 0 ? 'default' : 'pointer',
              overflow: 'hidden',
              ...dynamicStyle
            }}
          >
            <img src={imgUrl} alt={`${altText} ${originalIdx + 1}`} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
            
            {/* Indicatore click overlay sulle foto piccole */}
            {rank !== 0 && (
              <div style={{ position: 'absolute', inset: 0, background: 'rgba(0,0,0,0.2)', transition: '0.3s', opacity: 0 }} 
                   onMouseEnter={(e) => e.currentTarget.style.opacity = 1}
                   onMouseLeave={(e) => e.currentTarget.style.opacity = 0}
              >
                <div style={{ position: 'absolute', top: '50%', left: '50%', transform: 'translate(-50%, -50%)', color: '#fff', fontSize: '0.7rem', fontWeight: 'bold', textTransform: 'uppercase', letterSpacing: '2px', background: 'rgba(0,0,0,0.6)', padding: '6px 12px', borderRadius: '4px' }}>
                  Vedi
                </div>
              </div>
            )}
          </div>
        );
      })}
    </div>
  );
}
