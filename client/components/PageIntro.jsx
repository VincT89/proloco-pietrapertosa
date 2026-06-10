import React from 'react';

export default function PageIntro({ title, text }) {
  return (
    <div className="wrap page-intro-wrap">
      <div className="page-intro-inner fad">
        
        {/* Elegante linea decorativa verticale dorata */}
        <div className="page-intro-line-wrapper">
          <div className="page-intro-line"></div>
        </div>

        <h2 className="wr page-intro-title">
          {title}
        </h2>
        
        <p className="page-intro-text">
          {text}
        </p>
      </div>
    </div>
  );
}
