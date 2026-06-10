import React from 'react';

export default function SectionHero({ title, subtitle, img, bgPosition = 'center' }) {
  return (
    <div className="section-hero">
      <div 
        className="section-hero-bg"
        style={{
          backgroundImage: `linear-gradient(to bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.8)), url('${img}')`,
          backgroundPosition: bgPosition,
        }}
      />
      <div className="section-hero-content fad">
        {subtitle && <span className="lbl-mut section-hero-subtitle">{subtitle}</span>}
        <h1 className="wr section-hero-title">
          {title}
        </h1>
      </div>
    </div>
  );
}
