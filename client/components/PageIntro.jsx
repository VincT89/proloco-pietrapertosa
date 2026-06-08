import React from 'react';

export default function PageIntro({ title, text }) {
  return (
    <div className="wrap" style={{ paddingTop: 'clamp(80px, 10vw, 120px)', paddingBottom: '0' }}>
      <div style={{ maxWidth: '840px', margin: '0 auto', textAlign: 'center' }} className="fad">
        
        {/* Elegante linea decorativa verticale dorata */}
        <div style={{ display: 'flex', justifyContent: 'center', marginBottom: '32px' }}>
          <div style={{ width: '1px', height: '50px', backgroundColor: 'var(--gold)' }}></div>
        </div>

        <h2 className="wr" style={{ fontSize: 'clamp(2rem, 4vw, 2.8rem)', marginBottom: '24px', color: 'var(--paper)' }}>
          {title}
        </h2>
        
        <p style={{ fontSize: '1.1rem', lineHeight: '1.8', color: 'rgba(255, 255, 255, 0.8)', fontWeight: '300' }}>
          {text}
        </p>
      </div>
    </div>
  );
}
