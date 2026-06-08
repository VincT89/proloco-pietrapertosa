import React from 'react';

export default function SectionHero({ title, subtitle, img }) {
  return (
    <div style={{
      position: 'relative',
      width: '100%',
      height: 'clamp(400px, 50vh, 600px)',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      overflow: 'hidden',
      paddingTop: '60px' // offset interno per l'header, elimina la fascia nera sopra
    }}>
      <div 
        style={{
          position: 'absolute',
          top: 0, left: 0, width: '100%', height: '100%',
          backgroundImage: `url('${img}')`,
          backgroundSize: 'cover',
          backgroundPosition: 'center',
          filter: 'brightness(0.6)'
        }}
      />
      <div style={{ position: 'relative', zIndex: 1, textAlign: 'center', padding: '0 20px' }} className="fad">
        {subtitle && <span className="lbl-mut" style={{ marginBottom: '16px', display: 'block', color: 'var(--gold-soft)', fontSize: '0.85rem', letterSpacing: '0.3em', fontWeight: '700', textShadow: '0px 2px 8px rgba(0,0,0,0.9)' }}>{subtitle}</span>}
        <h1 className="wr" style={{ fontSize: 'clamp(2.5rem, 5vw, 4rem)', margin: 0, color: 'var(--paper)', textShadow: '0px 4px 16px rgba(0,0,0,0.6)' }}>
          {title}
        </h1>
      </div>
    </div>
  );
}
